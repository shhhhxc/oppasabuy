<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerVerification;
use App\Models\BuyerVerification;
use App\Models\Rider;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
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

    public function update(Request $request, $id, $type)
    {
        $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'rejection_reason' => [
                'required_if:status,rejected',
                'nullable',
                'string',
                'max:500'
            ],
        ]);

        if (!in_array($type, ['seller', 'buyer', 'rider'])) {
            abort(404);
        }

        if ($type === 'buyer') {
            $verification = BuyerVerification::with('user')
                ->findOrFail($id);
        } elseif ($type === 'rider') {
            $verification = Rider::with('user')
                ->findOrFail($id);
        } else {
            $verification = SellerVerification::with('user')
                ->findOrFail($id);
        }

        $user = $verification->user;

        if (!$user) {
            return back()->with(
                'error',
                'The user connected to this application was not found.'
            );
        }

        $verification->status = $request->status;
        $verification->rejection_reason = $request->status === 'rejected'
            ? $request->rejection_reason
            : null;

        $verification->verified_at = $request->status === 'approved'
            ? now()
            : null;

        $verification->save();

        if ($request->status === 'approved') {
            $user->is_verified = true;
            $user->verification_status = 'approved';

            if ($type === 'seller') {
                $user->role = 'seller';
            }

            if ($type === 'rider') {
                $user->role = 'rider';
            }

            $user->save();
        } else {
            $user->is_verified = false;
            $user->verification_status = 'rejected';
            $user->save();
        }

        return back()->with(
            'success',
            ucfirst($type) . ' registration has been ' . $request->status . '.'
        );
    }
}