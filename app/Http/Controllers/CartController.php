<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('buyer.cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "product_id" => $id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image_path,
                "seller_id" => $product->seller_id
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Added to cart!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from bag.');
    }

    public function checkout(Request $request)
    {
        if ($request->has('order_id')) {
            $order = Order::findOrFail($request->order_id);

            if (auth()->id() !== $order->buyer_id) {
                abort(403);
            }

            $order->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);

            $order->messages()->create([
                'user_id' => auth()->id(),
                'message' => '💸 PAYMENT COMPLETED: I have successfully paid for this order. Please proceed with shipping!',
            ]);

            return redirect()->route('chat.order', $order->id)->with('success', 'Payment successful! Seller has been notified.');
        }

        $cart = session()->get('cart', []);

        if (!$cart) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $buyerId = auth()->id();
        $lastOrderId = null;

        DB::transaction(function () use ($cart, $buyerId, &$lastOrderId) {
            $groupedItems = collect($cart)->groupBy('seller_id');

            foreach ($groupedItems as $sellerId => $items) {
                $newItemsTotal = $items->sum(fn($item) => $item['price'] * $item['quantity']);

                $existingOrder = Order::where('buyer_id', $buyerId)
                    ->where('seller_id', $sellerId)
                    ->oldest()
                    ->first();

                if ($existingOrder) {
                    $order = $existingOrder;

                    $order->items()->delete();

                    $order->update([
                        'total_price' => $newItemsTotal,
                        'status' => 'awaiting_video',
                        'payment_method' => null,
                        'video_proof_path' => null,
                        'paid_at' => null,
                    ]);

                    $appendDetails = [];

                    foreach ($items as $details) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $details['product_id'],
                            'quantity' => $details['quantity'],
                            'price' => $details['price']
                        ]);

                        $appendDetails[] = "• {$details['name']} (x{$details['quantity']}) - PHP " . number_format($details['price'] * $details['quantity'], 2);
                    }

                    $order->messages()->create([
                        'user_id' => $buyerId,
                        'message' => "🛒 [NEW ORDER ADDED]\n\nGusto ko sanang mag-verify at bumili ng mga sumusunod na produkto:\n" . implode("\n", $appendDetails) . "\n\nKabuuang Halaga Ng Bagong Checkout: PHP " . number_format($newItemsTotal, 2)
                    ]);
                } else {
                    $order = Order::create([
                        'buyer_id' => $buyerId,
                        'seller_id' => $sellerId,
                        'total_price' => $newItemsTotal,
                        'status' => 'awaiting_video',
                        'paid_at' => null,
                    ]);

                    $newDetails = [];

                    foreach ($items as $details) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $details['product_id'],
                            'quantity' => $details['quantity'],
                            'price' => $details['price']
                        ]);

                        $newDetails[] = "• {$details['name']} (x{$details['quantity']}) - PHP " . number_format($details['price'] * $details['quantity'], 2);
                    }

                    $order->messages()->create([
                        'user_id' => $buyerId,
                        'message' => "👋 Magandang araw! Gusto ko sanang mag-verify at bumili ng mga sumusunod na produkto mula sa inyong tindahan:\n" . implode("\n", $newDetails) . "\n\nKabuuang Halaga: PHP " . number_format($order->total_price, 2)
                    ]);
                }

                $lastOrderId = $order->id;
            }
        });

        session()->forget('cart');

        if ($lastOrderId) {
            return redirect()->route('chat.order', $lastOrderId)->with('success', 'Cart successfully added to your existing order chat!');
        }

        return redirect()->route('buyer.dashboard')->with('success', 'Order request processed! Check your inbox logs for confirmations.');
    }
}