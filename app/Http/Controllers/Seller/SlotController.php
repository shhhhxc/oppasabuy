<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ReservationSlot;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlotController extends Controller
{
    public function index()
    {
        // 1. Fetch all slots for this seller, eager-loading products
        $slots = ReservationSlot::where('seller_id', Auth::id())
                    ->with('products') 
                    ->orderBy('date', 'asc')
                    ->get();

        // 2. Group these slots by the 'channel' of the associated products.
        // We use a collection groupBy here.
        $groupedSlots = $slots->groupBy(function($slot) {
            // Take the first product's channel, or default to 'General'
            return $slot->products->first()->channel ?? 'General';
        });

        // 3. Fetch the seller's products for the creation form
        $products = Product::where('seller_id', Auth::id())->get();

        return view('seller.slots.index', [
            'groupedSlots' => $groupedSlots, // Pass the grouped data
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'available_slots' => 'required|integer|min:1',
            'dp_amount' => 'required|numeric|min:0',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $slot = ReservationSlot::create([
            'seller_id' => Auth::id(),
            'date' => $request->date,
            'max_slots' => $request->available_slots,
            'available_slots' => $request->available_slots,
            'dp_amount' => $request->dp_amount,
        ]);

        if ($request->has('product_ids')) {
            $slot->products()->attach($request->product_ids);
        }

        return back()->with('success', 'Distribution slot and products scheduled successfully!');
    }
}