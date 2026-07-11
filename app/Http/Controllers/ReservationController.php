<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Display the reservation calendar.
     */
    public function index(Request $request)
    {
        $sellers = User::where('role', 'seller')
            ->with('sellerVerification')
            ->get();

        // Now that Request $request is injected, this will work correctly
        $preselectedProductId = $request->query('product_id');

        return view('buyer.calendar', compact('sellers', 'preselectedProductId'));
    }

    /**
     * Return available slots for a specific seller.
     */
    public function getSlots($seller_id)
    {
        $slots = ReservationSlot::where('seller_id', $seller_id)
                    ->with('products:id,name') 
                    ->where('date', '>=', now()->toDateString())
                    ->get();

        $events = $slots->map(function($slot) {
            $isFull = $slot->available_slots <= 0;

            return [
                'id'    => $slot->id,
                'title' => $isFull ? 'FULLY BOOKED' : "₱" . number_format($slot->dp_amount, 2) . " | " . $slot->available_slots . " Slots",
                'start' => $slot->date, 
                'allDay' => true,
                
                'backgroundColor' => $isFull ? '#fee2e2' : '#0f4c97', 
                'borderColor'     => $isFull ? '#ef4444' : '#0f4c97',
                'textColor'       => $isFull ? '#991b1b' : '#ffffff',
                
                'extendedProps' => [
                    'price' => $slot->dp_amount,
                    'remaining' => $slot->available_slots,
                    'isFull' => $isFull,
                    'products' => $slot->products->map(function($p) {
                        return [
                            'id' => $p->id,
                            'name' => $p->name
                        ];
                    })
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Store a new reservation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:reservation_slots,id',
            'product_id' => 'required|exists:products,id',
            'payment_proof' => 'required|image|mimes:jpg,png,jpeg|max:5120',
        ]);

        try {
            $slot = ReservationSlot::findOrFail($request->slot_id);

            if ($slot->available_slots <= 0) {
                return response()->json([
                    'success' => false, 
                    'message' => 'This date was just fully booked. Please select another date.'
                ], 422);
            }

            $existingCount = Reservation::where('slot_id', $slot->id)->count();
            $assignedSlotNumber = $existingCount + 1;

            $path = $request->file('payment_proof')->store('reservations/payments', 'public');

            $reservation = Reservation::create([
                'buyer_id'      => Auth::id(),
                'seller_id'     => $slot->seller_id,
                'slot_id'       => $slot->id,
                'product_id'    => $request->product_id,
                'slot_number'   => $assignedSlotNumber,
                'status'        => 'pending', 
                'payment_proof' => $path,
            ]);

            $slot->decrement('available_slots');

            return response()->json([
                'success' => true, 
                'message' => 'Reservation received! We will verify your payment proof shortly.'
            ]);

        } catch (\Exception $e) {
            Log::error("Reservation Store Error: " . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage() 
            ], 500);
        }
    }
}