<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SellerVerification;
use App\Models\BuyerVerification;
use App\Models\Product; 
use App\Models\Order; 
use App\Models\Message; 
use App\Models\Slot; 
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Ad;
use App\Models\Rider;
use App\Models\BibleVerse;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Show pending Seller and Buyer verification requests.
     */
   public function verify()
{
    $sellerVerifications = SellerVerification::with('user')
        ->where('status', 'pending')
        ->latest()
        ->get();

    $buyerVerifications = BuyerVerification::with('user')
        ->where('status', 'pending')
        ->latest()
        ->get();

    $riderVerifications = Rider::with('user')
        ->where('status', 'pending')
        ->latest()
        ->get();

    return view('admin.verify', compact(
        'sellerVerifications',
        'buyerVerifications',
        'riderVerifications'
    ));
}

    /**
     * Update verification status for Buyer or Seller.
     */
    public function updateVerification(Request $request, $id, $type)
    {
        $model = ($type === 'seller') 
            ? SellerVerification::findOrFail($id) 
            : BuyerVerification::findOrFail($id);
        
        $model->update([
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason ?? null
        ]);

        if ($request->status === 'approved') {
            $user = $model->user;
            if ($type === 'seller') {
                $user->update(['role' => 'seller', 'status' => 'active']);
            } else {
                $user->update(['status' => 'active']);
            }
        }

        return back()->with('success', ucfirst($type) . ' verification updated successfully.');
    }

    /**
     * Display communication and engagement chat logs.
     */
    public function chatLogs(Request $request)
    {
        $sellerId = $request->get('seller_id');

        $sellers = User::whereHas('sellerVerification', function($q){
            $q->where('status', 'approved');
        })->get();

        // 1. Order Chats: Must have an order_id
        $orderChats = Order::has('messages')
            ->with(['seller', 'buyer', 'messages' => fn($q) => $q->latest()])
            ->when($sellerId, fn($q) => $q->where('seller_id', $sellerId))
            ->latest()
            ->get();

        // 2. Group Rooms: Only rooms that have social messages
        $socialChats = Room::has('messages')
            ->with(['creator', 'messages' => fn($q) => $q->latest()])
            ->when($sellerId, function($q) use ($sellerId) {
                $q->where('creator_id', $sellerId)
                  ->orWhereHas('users', fn($u) => $u->where('users.id', $sellerId));
            })
            ->latest()
            ->get();

        return view('admin.chats.index', compact('orderChats', 'socialChats', 'sellers'));
    }

    /**
     * View dedicated single room social thread conversation.
     */
    public function viewRoomConversation($id)
    {
        $room = Room::with('creator')->findOrFail($id);
        
        // Strict separation: only show messages belonging to this room ID where order_id is null
        $messages = Message::where('room_id', $id)
            ->whereNull('order_id')
            ->with('user')
            ->oldest()
            ->get();
        
        return view('admin.chats.show_room', compact('room', 'messages'));
    }

    /**
     * View specific private transactional store order conversation details.
     */
    public function viewConversation($orderId)
    {
        $order = Order::with(['seller', 'buyer'])->findOrFail($orderId);
        
        // Strict separation: only show messages belonging to this order ID
        $messages = Message::where('order_id', $orderId)
            ->with('user')
            ->oldest()
            ->get();
        
        return view('admin.chats.show', compact('order', 'messages'));
    }

    /**
     * Display platform analytical charts and income tracking maps.
     */
    public function sellerAnalytics($seller_id = null)
    {
        try {
            if (!$seller_id) {
                $sellers = User::whereHas('sellerVerification', function($query) {
                    $query->where('status', 'approved');
                })
                ->withCount(['sellerOrders as products_sold_count' => function ($query) {
                    $query->where('status', 'paid');
                }])
                ->withSum(['sellerOrders as total_income' => function ($query) {
                    $query->where('status', 'paid'); 
                }], 'total_price') 
                ->get();

                foreach ($sellers as $seller) {
                    // Unique chats logic
                    $seller->unique_customers_count = Message::where('order_id', '!=', null)
                        ->where(function($q) use ($seller) {
                            $q->whereHas('order', function($sub) use ($seller) {
                                $sub->where('seller_id', $seller->id);
                            });
                        })
                        ->distinct('user_id')
                        ->count();

                    // Profit Trend Calculation
                    $trend = [];
                    for ($i = 6; $i >= 0; $i--) {
                        $date = now()->subDays($i)->format('Y-m-d');
                        $trend[] = (float)Order::where('seller_id', $seller->id)
                            ->where('status', 'paid')
                            ->whereDate('created_at', $date)
                            ->sum('total_price');
                    }
                    $seller->profit_trend = $trend;
                }

                return view('admin.analytics_index', compact('sellers'));
            }

            $seller = User::where('id', $seller_id)->firstOrFail();
            $sellers = collect([$seller]); 
            return view('admin.analytics_index', compact('sellers'));

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper calculation for response engagement metrics.
     */
    private function calculateChatEngagement($sellerId)
    {
        $received = Message::whereHas('order', function($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->where('user_id', '!=', $sellerId)->count();

        $sent = Message::where('user_id', $sellerId)->count();

        if ($received === 0) return 100;
        
        $rate = ($sent / $received) * 100;
        return min(round($rate, 1), 100);
    }

    public function userList()
    {
        $users = User::with([
            'sellerVerification',
            'rider'
        ])
            ->latest()
            ->get();

        $buyerVerifications = BuyerVerification::whereIn(
            'user_id',
            $users->pluck('id')
        )
            ->get()
            ->keyBy('user_id');

        foreach ($users as $user) {
            $user->setRelation(
                'buyerVerification',
                $buyerVerifications->get($user->id)
            );
        }

        return view('admin.users.index', compact('users'));
    }

    public function showUser(User $user)
    {
        $user->load([
            'sellerVerification',
            'rider'
        ]);

        $buyerVerification = BuyerVerification::where(
            'user_id',
            $user->id
        )->first();

        $user->setRelation(
            'buyerVerification',
            $buyerVerification
        );

        return view('admin.users.show', compact('user'));
    }

public function lockUser(User $user)
{
    if ($user->role === 'admin') {
        return back()->with('error', 'Administrator accounts cannot be locked.');
    }

    $user->account_status = 'locked';
    $user->save();

    return back()->with('success', 'User account has been locked.');
}

public function disableUser(User $user)
{
    if ($user->role === 'admin') {
        return back()->with('error', 'Administrator accounts cannot be disabled.');
    }

    $user->account_status = 'disabled';
    $user->save();

    return back()->with('success', 'User account has been disabled.');
}

public function activateUser(User $user)
{
    $user->account_status = 'active';
    $user->save();

    return back()->with('success', 'User account has been reactivated.');
}

    /*
    |--------------------------------------------------------------------------
    | Curation Hub Engine & Homepage Management Pipelines
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created promotional ad/banner (Image and/or Video).
     */
    public function storeAd(Request $request)
    {
        $request->validate([
            'ad_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'ad_video'   => 'nullable|mimes:mp4,webm|max:20480',
            'title'      => 'nullable|string|max:255',
            'target_url' => 'nullable|url|max:255',
        ]);

        if (!$request->hasFile('ad_image') && !$request->hasFile('ad_video')) {
            return redirect()->back()->with('error', 'Please provide at least an image or a video.');
        }

        $data = [
            'title'      => $request->title,
            'target_url' => $request->target_url,
            'is_active'  => true,
        ];

        if ($request->hasFile('ad_image')) {
            $data['image_path'] = $request->file('ad_image')->store('ads/images', 'public');
        }

        if ($request->hasFile('ad_video')) {
            $data['video_path'] = $request->file('ad_video')->store('ads/videos', 'public');
            $data['type']       = 'video';
        }

        Ad::create($data);

        return redirect()->back()->with('success', 'Promotional asset published successfully!');
    }

    /**
     * Store a newly created Bible verse entry in storage.
     */
    public function storeVerse(Request $request)
    {
        $request->validate([
            'display_type' => 'required|in:text,image',
            'verse_text'   => 'required_if:display_type,text|nullable|string',
            'reference'    => 'required_if:display_type,text|nullable|string|max:255',
            'verse_image'  => 'required_if:display_type,image|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'display_type' => $request->display_type,
            'is_published' => true,
        ];

        if ($request->display_type === 'text') {
            $data['verse_text'] = $request->verse_text;
            $data['reference']  = $request->reference;
        } elseif ($request->display_type === 'image' && $request->hasFile('verse_image')) {
            $data['image_path'] = $request->file('verse_image')->store('verses', 'public');
        }

        BibleVerse::create($data);

        return redirect()->back()->with('success', 'Scripture feed synchronized successfully!');
    }

    public function storeEvent(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'event_date' => 'required|date'
    ]);

    $path = $request->file('image')->store('events', 'public');

    \App\Models\Event::create([
        'title' => $request->title,
        'image_path' => $path,
        'event_date' => $request->event_date
    ]);

    return back()->with('success', 'Event banner uploaded successfully!');
}
}