<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rider;
use App\Models\Order;
use App\Models\PaskaayRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RiderController extends Controller
{
    public function showRiderRegistrationForm()
    {
        return view('auth.register-rider');
    }

    public function registerRider(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_number' => ['required', 'string', 'max:20'],

            'vehicle_type' => [
                'required',
                'string',
                'in:Motorcycle,Car,Van,Bicycle'
            ],

            'vehicle_brand' => ['required', 'string', 'max:100'],
            'vehicle_model' => ['required', 'string', 'max:100'],
            'vehicle_color' => ['required', 'string', 'max:50'],
            'vehicle_plate' => ['required', 'string', 'max:50'],

            'license_number' => [
                'required',
                'string',
                'max:100',
                'unique:riders,license_number'
            ],

            'license_expiration' => ['required', 'date', 'after:today'],

            'license_img' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],

            'orcr_img' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:10240'
            ],

            'vehicle_photo' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],

            'selfie_license' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],

            'terms' => ['accepted'],
        ]);

        $licenseImagePath = null;
        $orcrImagePath = null;
        $vehiclePhotoPath = null;
        $selfieLicensePath = null;

        DB::beginTransaction();

        try {
            $licenseImagePath = $request
                ->file('license_img')
                ->store('rider_documents/licenses', 'public');

            $orcrImagePath = $request
                ->file('orcr_img')
                ->store('rider_documents/orcr', 'public');

            $vehiclePhotoPath = $request
                ->file('vehicle_photo')
                ->store('rider_documents/vehicles', 'public');

            $selfieLicensePath = $request
                ->file('selfie_license')
                ->store('rider_documents/selfies', 'public');

            $user = new User();
            $user->name = $validated['name'];
            $user->full_name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->role = 'rider';
            $user->save();

            $rider = new Rider();
            $rider->user_id = $user->id;
            $rider->phone = $validated['phone'];
            $rider->birth_date = $validated['birth_date'];
            $rider->address = $validated['address'];

            $rider->emergency_contact_name =
                $validated['emergency_contact_name'];

            $rider->emergency_contact_number =
                $validated['emergency_contact_number'];

            $rider->vehicle_type = $validated['vehicle_type'];
            $rider->vehicle_brand = $validated['vehicle_brand'];
            $rider->vehicle_model = $validated['vehicle_model'];
            $rider->vehicle_color = $validated['vehicle_color'];

            $rider->vehicle_plate = strtoupper(
                $validated['vehicle_plate']
            );

            $rider->license_number = $validated['license_number'];

            $rider->license_expiration =
                $validated['license_expiration'];

           $rider->license_img = $licenseImagePath;
            $rider->orcr_img = $orcrImagePath;
            $rider->vehicle_photo = $vehiclePhotoPath;
            $rider->selfie_license = $selfieLicensePath;

            $rider->rating = 0;
            $rider->rating_count = 0;
            $rider->is_online = 0;
            $rider->earnings = 0;

            $rider->status = 'pending';
            $rider->rejection_reason = null;
            $rider->verified_at = null;

            $rider->save();
            DB::commit();

            Auth::login($user);

            $request->session()->regenerate();

            return redirect()
                ->route('rider.dashboard')
                ->with(
                    'success',
                    'Rider registration completed successfully.'
                );
        } catch (\Throwable $exception) {
            DB::rollBack();

            if ($licenseImagePath) {
                Storage::disk('public')->delete($licenseImagePath);
            }

            if ($orcrImagePath) {
                Storage::disk('public')->delete($orcrImagePath);
            }

            if ($vehiclePhotoPath) {
                Storage::disk('public')->delete($vehiclePhotoPath);
            }

            if ($selfieLicensePath) {
                Storage::disk('public')->delete($selfieLicensePath);
            }

            report($exception);

            return back()
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                        'license_img',
                        'orcr_img',
                        'vehicle_photo',
                        'selfie_license',
                    ])
                )
                ->with(
                    'error',
                    'Unable to complete rider registration. Please try again.'
                );
        }
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please log in first.');
        }

        $user = Auth::user();

        $rider = Rider::where('user_id', $user->id)->first();

        if (!$rider) {
            return redirect()
                ->route('register.rider')
                ->with(
                    'error',
                    'Please register as a rider first.'
                );
        }

        $activePaskaay = PaskaayRequest::where(
            'rider_id',
            $user->id
        )
            ->whereIn('status', ['accepted', 'picked_up'])
            ->first();

        $activeOrder = Order::where('rider_id', $user->id)
            ->whereIn('status', ['accepted', 'picked_up'])
            ->first();

        $activeBooking = $activePaskaay ?? $activeOrder;
        $activeBookingType = null;

        if ($activePaskaay) {
            $activeBookingType = 'paskaay';
        } elseif ($activeOrder) {
            $activeBookingType = 'pasabuy';
        }

        $allRequests = collect();

        if (!$activeBooking) {
            $pasabuyOrders = Order::where('status', 'pending')
                ->latest()
                ->get()
                ->map(function ($order) {
                    $order->booking_type = 'pasabuy';

                    return $order;
                });

            $riderVehicle = strtolower($rider->vehicle_type ?? '');

            $requestedVehicle = $riderVehicle === 'car'
                ? 'car'
                : 'motorcycle';

            $paskaayRequests = PaskaayRequest::where('status', 'searching_rider')
                ->where('vehicle_type', $requestedVehicle)
                ->latest()
                ->get()
                ->map(function ($paskaayRequest) {
                    $paskaayRequest->booking_type = 'paskaay';
                    return $paskaayRequest;
                });

            $allRequests = $pasabuyOrders
                ->concat($paskaayRequests)
                ->sortByDesc('created_at')
                ->values();
        }

        $deliveriesToday =
            Order::where('rider_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count()
            +
            PaskaayRequest::where('rider_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count();

        $earningsToday = $rider->earnings ?? 0;

        return view('rider.dashboard', compact(
            'rider',
            'allRequests',
            'activeBooking',
            'activeBookingType',
            'deliveriesToday',
            'earningsToday'
        ));
    }

    public function acceptBooking($id, Request $request)
    {
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please log in first.');
        }

        $type = $request->query('type');
        $userId = Auth::id();

        $rider = Rider::where('user_id', $userId)->first();

        if (!$rider) {
            return redirect()
                ->route('register.rider')
                ->with(
                    'error',
                    'Please register as a rider first.'
                );
        }

        $hasActivePaskaay = PaskaayRequest::where(
            'rider_id',
            $userId
        )
            ->whereIn('status', ['accepted', 'picked_up'])
            ->exists();

        $hasActiveOrder = Order::where('rider_id', $userId)
            ->whereIn('status', ['accepted', 'picked_up'])
            ->exists();

        if ($hasActivePaskaay || $hasActiveOrder) {
            return redirect()
                ->route('rider.dashboard')
                ->with(
                    'error',
                    'You already have an active booking.'
                );
        }

        DB::beginTransaction();

        try {
            if ($type === 'paskaay') {
                $booking = PaskaayRequest::where('id', $id)
                    ->where('status', 'searching_rider')
                    ->lockForUpdate()
                    ->first();

                if (!$booking) {
                    DB::rollBack();

                    return redirect()
                        ->route('rider.dashboard')
                        ->with(
                            'error',
                            'This Paskaay request is no longer available.'
                        );
                }
            } else {
                $booking = Order::where('id', $id)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->first();

                if (!$booking) {
                    DB::rollBack();

                    return redirect()
                        ->route('rider.dashboard')
                        ->with(
                            'error',
                            'This order is no longer available.'
                        );
                }
            }

            $booking->status = 'accepted';
            $booking->rider_id = $userId;
            $booking->save();

            DB::commit();

            return redirect()
                ->route('rider.dashboard')
                ->with(
                    'success',
                    'Booking accepted successfully.'
                );
        } catch (\Throwable $exception) {
            DB::rollBack();

            report($exception);

            return redirect()
                ->route('rider.dashboard')
                ->with(
                    'error',
                    'Unable to accept the booking.'
                );
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please log in first.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:picked_up,completed'],
        ]);

        $type = $request->query('type');
        $newStatus = $validated['status'];
        $userId = Auth::id();

        if ($type === 'paskaay') {
            $booking = PaskaayRequest::where('id', $id)
                ->where('rider_id', $userId)
                ->firstOrFail();
        } else {
            $booking = Order::where('id', $id)
                ->where('rider_id', $userId)
                ->firstOrFail();
        }

        if (
            $newStatus === 'picked_up' &&
            $booking->status !== 'accepted'
        ) {
            return redirect()
                ->route('rider.dashboard')
                ->with(
                    'error',
                    'Only accepted bookings can be marked as picked up.'
                );
        }

        if (
            $newStatus === 'completed' &&
            $booking->status !== 'picked_up'
        ) {
            return redirect()
                ->route('rider.dashboard')
                ->with(
                    'error',
                    'The booking must be picked up before completing it.'
                );
        }

        DB::beginTransaction();

        try {
            $booking->status = $newStatus;
            $booking->save();

            if ($newStatus === 'completed') {
                $rider = Rider::where('user_id', $userId)
                    ->lockForUpdate()
                    ->first();

                if ($rider) {
                    $fare = $type === 'paskaay'
                        ? ($booking->fare ?? 0)
                        : ($booking->rider_price ?? 0);

                    $rider->earnings =
                        ($rider->earnings ?? 0) + $fare;

                    $rider->save();
                }
            }

            DB::commit();

            return redirect()
                ->route('rider.dashboard')
                ->with(
                    'success',
                    'Status updated to ' .
                    str_replace('_', ' ', $newStatus) .
                    '.'
                );
        } catch (\Throwable $exception) {
            DB::rollBack();

            report($exception);

            return redirect()
                ->route('rider.dashboard')
                ->with(
                    'error',
                    'Unable to update the booking status.'
                );
        }
    }
}