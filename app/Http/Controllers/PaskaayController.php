<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaskaayRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Rider;
use App\Models\User;

class PaskaayController extends Controller
{
    public function index()
    {
        $paskaay = PaskaayRequest::where('user_id', Auth::id())
            ->whereIn('status', ['searching_rider', 'accepted'])
            ->latest()
            ->first();

        return view('hatid express.hatid-express', compact('paskaay'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickup_address' => 'required|string',
            'destination_address' => 'required|string',
            'pickup_lat' => 'required|numeric',
            'pickup_lng' => 'required|numeric',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
            'fare' => 'required|numeric|min:0',
            'vehicle_type' => 'required|string|in:motorcycle,car',
            'note' => 'nullable|string',
        ]);

        $paskaay = PaskaayRequest::create([
            'user_id' => Auth::id(),
            'pickup_address' => $validated['pickup_address'],
            'destination_address' => $validated['destination_address'],
            'pickup_lat' => $validated['pickup_lat'],
            'pickup_lng' => $validated['pickup_lng'],
            'dest_lat' => $validated['dest_lat'],
            'dest_lng' => $validated['dest_lng'],
            'fare' => $validated['fare'],
            'vehicle_type' => $validated['vehicle_type'],
            'note' => $validated['note'] ?? null,
            'status' => 'searching_rider',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'booking_id' => $paskaay->id,
                'vehicle_type' => $paskaay->vehicle_type,
            ]);
        }

        return redirect()->route('paskaay.searching', $paskaay->id);
    }

    public function searching($id)
    {
        $paskaay = PaskaayRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('paskaay.searching', compact('paskaay'));
    }

    public function checkStatus($id)
    {
        $paskaay = PaskaayRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $response = [
            'status' => $paskaay->status,
            'requested_vehicle_type' => $paskaay->vehicle_type,
            'rider_name' => null,
            'vehicle' => null,
            'vehicle_type' => null,
            'vehicle_brand' => null,
            'vehicle_model' => null,
            'vehicle_color' => null,
            'plate_number' => null,
        ];

        if ($paskaay->status === 'accepted' && $paskaay->rider_id) {
            $riderUser = User::find($paskaay->rider_id);
            $rider = Rider::where('user_id', $paskaay->rider_id)->first();

            $vehicleParts = array_filter([
                $rider?->vehicle_brand,
                $rider?->vehicle_model,
            ]);

            $response['rider_name'] = $riderUser?->full_name
                ?? $riderUser?->name
                ?? 'Your OppaDriver';

            $response['vehicle'] = !empty($vehicleParts)
                ? implode(' ', $vehicleParts)
                : ($rider?->vehicle_type ?? 'OppaDriver Vehicle');

            $response['vehicle_type'] = $rider?->vehicle_type;
            $response['vehicle_brand'] = $rider?->vehicle_brand;
            $response['vehicle_model'] = $rider?->vehicle_model;
            $response['vehicle_color'] = $rider?->vehicle_color;
            $response['plate_number'] = $rider?->vehicle_plate ?? 'To be confirmed';
        }

        return response()->json($response);
    }

    public function tracking($id)
    {
        $paskaay = PaskaayRequest::with('rider.user')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('paskaay.tracking', compact('paskaay'));
    }

    public function getLocation($id)
    {
        $paskaay = PaskaayRequest::findOrFail($id);

        return response()->json([
            'lat' => $paskaay->latitude,
            'lng' => $paskaay->longitude,
            'status' => $paskaay->status,
        ]);
    }

    public function updateLocation(Request $request, $id)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $paskaay = PaskaayRequest::findOrFail($id);

        $paskaay->update([
            'latitude' => $validated['lat'],
            'longitude' => $validated['lng'],
        ]);

        return response()->json(['status' => 'success']);
    }

    public function showReceipt($id)
    {
        $paskaay = PaskaayRequest::with('rider.user')->findOrFail($id);
        $rider = $paskaay->rider;

        return view('paskaay.receipt', compact('paskaay', 'rider'));
    }

    public function rateRider(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $paskaay = PaskaayRequest::findOrFail($id);
        $rider = Rider::where('user_id', $paskaay->rider_id)->first();

        if (!$rider) {
            return back()->with('error', 'Rider not found');
        }

        $count = $rider->rating_count ?? 0;
        $oldRating = $rider->rating ?? 5;

        if ($count == 0) {
            $newRating = $request->rating;
            $newCount = 1;
        } else {
            $newRating = (($oldRating * $count) + $request->rating) / ($count + 1);
            $newCount = $count + 1;
        }

        $rider->update([
            'rating' => round($newRating, 2),
            'rating_count' => $newCount,
        ]);

        return redirect()->route('home')->with('success', 'Thanks for rating!');
    }

    public function cancel($id)
    {
        $paskaay = PaskaayRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($paskaay->status === 'searching_rider') {
            $paskaay->update([
                'status' => 'cancelled',
            ]);

            return redirect()
                ->route('home')
                ->with('success', 'Your booking has been successfully cancelled.');
        }

        return redirect()
            ->back()
            ->with('error', 'Booking cannot be cancelled at this stage.');
    }
}
