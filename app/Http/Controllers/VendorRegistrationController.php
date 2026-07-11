<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use App\Models\SellerVerification; // Tiyaking naka-import ito
use Illuminate\Support\Facades\Auth;

class VendorRegistrationController extends Controller
{
    /**
     * Show the vendor registration form.
     */
    public function create()
    {
        $user = Auth::user();
        $existingStore = Store::where('user_id', $user->id)->first();
        $verification = $user->sellerVerification;

        // Kung may store application na siya at approved na talaga ang role niya bilang seller ng admin
        if ($existingStore && $user->role === 'seller') {
            return redirect()->route('vendor.dashboard');
        }

        // Kung may record na sa verification pero 'pending' pa rin ang status nito
        if ($verification && $verification->status === 'pending') {
            return redirect()->route('buyer.dashboard')
                             ->with('show_pending_modal', true)
                             ->with('warning', 'Your seller application is currently pending verification.');
        }

        return view('vendor.register');
    }

    /**
     * Store a newly created vendor resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'shop_name'         => 'required|string|max:255',
            'store_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'no_physical_store' => 'nullable|boolean',
            'store_address'     => 'required_unless:no_physical_store,1|nullable|string|max:500',
            'business_email'    => 'required|email|max:255',
            'phone_number'      => 'required|string|max:50',
            'shop_description'  => 'required|string|max:1000',
            'store_types'       => 'required|array|min:1', 
            'store_types.*'     => 'string|in:oppa_mall,own_webstore,green_market,personal_care',
            'green_market_type' => 'nullable|array', 
            'agree_terms'       => 'required|accepted',
        ]);

        $userId = Auth::id();
        $user = User::find($userId);

        // Extra guard upang maiwasan ang double submission habang pending pa
        $existingStore = Store::where('user_id', $userId)->first();
        if ($existingStore) {
            return redirect()->route('buyer.dashboard')
                             ->with('show_pending_modal', true)
                             ->with('warning', 'Your seller application is currently pending verification.');
        }

        // 2. Handle Logo Upload
        $logoPath = null;
        if ($request->hasFile('store_logo')) {
            $logoPath = $request->file('store_logo')->store('logos', 'public');
        }

        // 3. Create Store Record
        $store = new Store();
        $store->user_id = $userId;
        $store->name = $validated['shop_name'];
        $store->logo = $logoPath;
        $store->address = $request->has('no_physical_store') ? 'Online Only' : $validated['store_address'];
        $store->business_email = $validated['business_email'];
        $store->phone_number = $validated['phone_number'];
        $store->description = $validated['shop_description'];
        $store->store_types = $validated['store_types']; 
        $store->green_market_type = $request->green_market_type; 
        $store->save();

        // 4. DIREKTA AT TIYAK NA INSERT SA SELLER VERIFICATION (Bypass mass assignment protection)
        // Sapilitan nating gagawan ng entry at titiyaking 'pending' ang status nito para makita ni admin!
        $verification = SellerVerification::where('user_id', $userId)->first();
        if (!$verification) {
            $verification = new SellerVerification();
            $verification->user_id = $userId;
        }
        
        $verification->status = 'pending'; // PENDING! Para si Admin ang mag-a-approve sa panel niya.
        $verification->id_type = 'Not Provided Yet'; // Nilagyan ng dummy values para hindi mag-throw ng QueryException
        $verification->id_number = 'PENDING_SETUP';
        $verification->document_path = 'storefront/placeholders/default.png';
        $verification->save();

        // TANDAAN: Mananatiling 'buyer' ang role ng user dito. 
        // Huwag muna nating babaguhin ang role niya para saluhin siya ng middleware mo pabalik sa buyer dashboard.

        return redirect()->route('buyer.dashboard')
                         ->with('show_pending_modal', true)
                         ->with('success', 'Your seller application has been submitted successfully and is pending review!');
    }
}