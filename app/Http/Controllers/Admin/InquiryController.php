<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\PaskaayRequest;

class InquiryController extends Controller
{
    /**
     * Display a listing of the service requests (Pasabuy or Paskaay).
     */
    public function index()
    {
        $inquiries = Order::where('is_pasabuy_request', true)
            ->with('buyer')
            ->latest()
            ->get();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Handle the form submission from the buyer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:pasabuy,paskaay',
            'product_name' => 'required|string',
            'quantity' => 'required|integer',
            'address' => 'required|string',
        ]);

        $order = Order::create([
            'buyer_id' => auth()->id(),
            'seller_id' => 2,
            'total_price' => 0,
            'status' => 'pending_request',
            'product_name' => $validated['product_name'],
            'product_type' => $validated['service_type'],
            'quantity' => $validated['quantity'],
            'address' => $validated['address'],
            'is_pasabuy_request' => true,
        ]);

        $order->messages()->create([
            'user_id' => auth()->id(),
            'message' => "New Service Request: " . strtoupper($validated['service_type']) .
                "\nProduct: {$validated['product_name']}" .
                "\nQty: {$validated['quantity']}" .
                "\nDelivery: {$validated['address']}",
        ]);

        return redirect()
            ->route('chat.show', $order->id)
            ->with(
                'success',
                'Inquiry sent! A rider will be assigned to your ' .
                strtoupper($validated['service_type']) .
                ' request.'
            );
    }

    /**
     * Store a Paskaay rider booking.
     */
    public function storeRiderBooking(Request $request)
    {
        $validated = $request->validate([
            'pickup_address' => 'required|string',
            'destination_address' => 'required|string',
            'pickup_lat' => 'required|numeric',
            'pickup_lng' => 'required|numeric',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
            'note' => 'nullable|string',
            'fare' => 'required|numeric|min:0',
            'vehicle_type' => 'required|string|in:motorcycle,car',
        ]);

        $paskaay = PaskaayRequest::create([
            'user_id' => auth()->id(),
            'pickup_address' => $validated['pickup_address'],
            'destination_address' => $validated['destination_address'],
            'pickup_lat' => $validated['pickup_lat'],
            'pickup_lng' => $validated['pickup_lng'],
            'dest_lat' => $validated['dest_lat'],
            'dest_lng' => $validated['dest_lng'],
            'note' => $validated['note'] ?? null,
            'fare' => $validated['fare'],
            'vehicle_type' => $validated['vehicle_type'],
            'status' => 'searching_rider',
        ]);

        return response()->json([
            'status' => 'success',
            'booking_id' => $paskaay->id,
            'vehicle_type' => $paskaay->vehicle_type,
        ]);
    }
}
