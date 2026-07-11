<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Message;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\OrderChatUpdated;

class OrderChatController extends Controller
{
    private function userOrdersQuery($user)
    {
        return Order::where(function($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhere('seller_id', $user->id)
                  ->orWhereHas('users', function($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        })
        ->with(['items.product', 'buyer', 'seller.sellerVerification'])
        ->withCount(['messages as unread_messages_count' => function ($query) use ($user) {
            $query->where('user_id', '!=', $user->id)
                ->whereRaw("
                    messages.created_at > COALESCE(
                        CASE
                            WHEN orders.buyer_id = ? THEN orders.buyer_last_read_at
                            WHEN orders.seller_id = ? THEN orders.seller_last_read_at
                        END,
                        '1970-01-01 00:00:00'
                    )
                ", [$user->id, $user->id]);
        }])
        ->latest();
    }

    public function index()
    {
        $user = Auth::user();

        $userOrders = $this->userOrdersQuery($user)->get();

        $userRooms = Room::whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->withCount('users')
            ->latest()
            ->get();

        $availableUsers = User::where('id', '!=', $user->id)->get();

        $isVerified = $user->sellerVerification &&
            $user->sellerVerification->status === 'approved';

        $hasVideo = $user->sellerVerification &&
            !empty($user->sellerVerification->video_path);

        return view('chat.chat', [
            'order' => null,
            'messages' => collect(),
            'userOrders' => $userOrders,
            'userRooms' => $userRooms,
            'availableUsers' => $availableUsers,
            'isVerified' => $isVerified,
            'hasVideo' => $hasVideo,
        ]);
    }

    public function show(Order $order)
    {
        $user = Auth::user();

        if ($user->id === $order->buyer_id) {
            $order->update([
                'buyer_last_read_at' => now()
            ]);
        }

        if ($user->id === $order->seller_id) {
            $order->update([
                'seller_last_read_at' => now()
            ]);
        }

        $order->refresh();

        $userOrders = $this->userOrdersQuery($user)->get();

        $userRooms = Room::whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->withCount('users')
            ->latest()
            ->get();

        $order->load(['items.product', 'seller.sellerVerification', 'buyer', 'users']);
        $messages = $order->messages()->with('user.sellerVerification')->oldest()->get();
        $availableUsers = User::where('id', '!=', $user->id)->get();

        $isVerified = $user->sellerVerification && $user->sellerVerification->status === 'approved';
        $hasVideo = $user->sellerVerification && !empty($user->sellerVerification->video_path);

        return view('chat.chat', compact(
            'order', 'messages', 'userOrders', 'userRooms', 'availableUsers', 'isVerified', 'hasVideo'
        ));
    }

    public function sendMessage(Request $request, Order $order)
    {
        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:mp4,mov,avi|max:20480',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = [
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ];

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chat_attachments', 'public');
            $data['video_path'] = $path;

            if (auth()->id() === $order->seller_id && $order->status !== 'paid') {
                $order->update(['status' => 'video_uploaded']);
            }
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_attachments', 'public');
            $data['image_path'] = $path;

            if (auth()->id() === $order->buyer_id && $order->status === 'awaiting_payment_qr') {
                $order->update(['status' => 'receipt_uploaded']);
            }
        }

        $message = $order->messages()->create($data);

        if (auth()->id() === $order->buyer_id) {
            $order->update([
                'buyer_last_read_at' => now()
            ]);
        }

        if (auth()->id() === $order->seller_id) {
            $order->update([
                'seller_last_read_at' => now()
            ]);
        }

        broadcast(new OrderChatUpdated($message))->toOthers();

        return back();
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'payment_method' => 'nullable|string'
        ]);

        $updateData = [
            'status' => $request->status,
            'payment_method' => $request->payment_method ?? $order->payment_method
        ];

        if ($request->status === 'paid') {
            $updateData['paid_at'] = now();
        }

        $order->update($updateData);

        $statusText = str_replace('_', ' ', $request->status);
        $method = $request->payment_method ? " via " . $request->payment_method : "";

        $systemMessage = $order->messages()->create([
            'user_id' => auth()->id(),
            'message' => "📢 Order status updated to: " . strtoupper($statusText) . $method,
        ]);

        if (auth()->id() === $order->buyer_id) {
            $order->update([
                'buyer_last_read_at' => now()
            ]);
        }

        if (auth()->id() === $order->seller_id) {
            $order->update([
                'seller_last_read_at' => now()
            ]);
        }

        broadcast(new OrderChatUpdated($systemMessage))->toOthers();

        return back()->with('success', 'Order status updated.');
    }

    public function completeOrder(Order $order)
    {
        if (auth()->id() !== $order->seller_id) {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($order->status === 'paid') {
            return back()->with('info', 'This order is already completed.');
        }

        try {
            DB::transaction(function () use ($order) {
                $order->update([
                    'status' => 'paid',
                    'seller_last_read_at' => now(),
                    'paid_at' => now()
                ]);

                $seller = $order->seller;

                if ($seller) {
                    $seller->increment('balance', $order->total_price);
                }

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->decrement('stock', $item->quantity);
                    }
                }

                $completionMessage = $order->messages()->create([
                    'user_id' => auth()->id(),
                    'message' => "✅ Payment Confirmed! ₱" . number_format($order->total_price, 2) . " added to dashboard.",
                ]);

                broadcast(new OrderChatUpdated($completionMessage))->toOthers();
            });

            return back()->with('success', 'Payment confirmed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    public function sellerIndex()
    {
        $user = Auth::user();
        $store = $user->sellerVerification;

        $allOrders = $this->userOrdersQuery($user)->get();

        return view('seller.orders.index', compact('allOrders', 'store'));
    }

    public function storePasabuyRequest(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required',
            'product_type' => 'required',
            'quantity' => 'required|integer',
            'address' => 'required',
        ]);

        $order = Order::create([
            'buyer_id' => auth()->id(),
            'seller_id' => 1,
            'status' => 'pending_request',
            'product_name' => $validated['product_name'],
            'product_type' => $validated['product_type'],
            'quantity' => $validated['quantity'],
            'address' => $validated['address'],
            'is_pasabuy_request' => true,
            'buyer_last_read_at' => now(),
        ]);

        $order->messages()->create([
            'user_id' => auth()->id(),
            'message' => "I would like to request a pasabuy item: {$validated['product_name']} ({$validated['product_type']}). Quantity: {$validated['quantity']}. Delivery Address: {$validated['address']}"
        ]);

        return redirect()->route('chat.show', $order->id);
    }
}