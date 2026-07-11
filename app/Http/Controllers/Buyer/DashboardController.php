<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
 public function index()
{
    $user = Auth::user();

    $stats = [
        'awaiting_video' => Order::where('buyer_id', $user->id)->where('status', 'awaiting_video')->count(),
        'video_ready'   => Order::where('buyer_id', $user->id)->where('status', 'video_uploaded')->count(),
        'completed'      => Order::where('buyer_id', $user->id)->whereIn('status', ['completed', 'paid'])->count()
    ];

    $orders = Order::where('buyer_id', $user->id)
                ->with(['seller', 'items.product']) 
                ->latest()
                ->get();

    // ADD THIS: Fetch sellers and their verification details (store name/logo)
    $favoriteSellers = User::where('role', 'seller')
                ->with('sellerVerification') // Ensure this relationship exists in User model
                ->take(10)
                ->get();

    // Add 'favoriteSellers' to the compact function
    return view('buyer.dashboard', compact('user', 'stats', 'orders', 'favoriteSellers'));
}

    /**
     * Update the buyer's shipping address
     */
    public function updateAddress(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:500',
        ]);

        /** @var User $user */
        $user = Auth::user();
        
        // Update the user record
        $user->update([
            'address' => $request->address
        ]);

        return back()->with('success', 'Shipping address updated successfully!');
    }
}