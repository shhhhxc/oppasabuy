<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BuyerVerification;
use App\Models\SellerVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'account_type' => 'required|in:buyer,seller',

            // Buyer
            'buyer_id_type' => 'nullable|required_if:account_type,buyer',
            'buyer_valid_id' => 'nullable|required_if:account_type,buyer|image|mimes:jpeg,png,jpg|max:10240',

            // Seller
            'store_name' => 'nullable|required_if:account_type,seller|max:255',
            'seller_id_type' => 'nullable|required_if:account_type,seller',
            'seller_valid_id' => 'nullable|required_if:account_type,seller|image|mimes:jpeg,png,jpg|max:10240',
            'seller_video_with_id' => 'nullable|required_if:account_type,seller|mimes:mp4,mov,avi|max:20480',
        ]);

        if ($validator->fails()) {
            Log::error('Oppasabuy Validation Failed', [
                'errors' => $validator->errors()->all()
            ]);

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = DB::transaction(function () use ($request) {

                // 2. CREATE USER
                $user = User::create([
                    'name'     => $request->full_name, 
                    'full_name' => $request->full_name, 
                    'email'    => $request->email,
                    'phone'    => $request->phone,
                    'address'  => $request->address,
                    'password' => Hash::make($request->password),
                    'role'     => $request->account_type,
                    'balance'  => 0,
                ]);

                // 3. BUYER LOGIC
                if ($request->account_type === 'buyer') {

                    if (!$request->hasFile('buyer_valid_id')) {
                        throw new \Exception('Buyer ID is required.');
                    }

                    $path = $request->file('buyer_valid_id')
                        ->store('uploads/buyer_ids', 'public');

                    BuyerVerification::create([
                        'user_id' => $user->id,
                        'id_type' => $request->buyer_id_type,
                        'id_path' => $path,
                        'status'  => 'pending',
                    ]);
                }

                // 4. SELLER LOGIC
                if ($request->account_type === 'seller') {

                    $idPath = $request->hasFile('seller_valid_id')
                        ? $request->file('seller_valid_id')->store('uploads/ids', 'public')
                        : null;

                    $videoPath = $request->hasFile('seller_video_with_id')
                        ? $request->file('seller_video_with_id')->store('uploads/videos', 'public')
                        : null;

                    $logoPath = $request->hasFile('seller_store_media')
                        ? $request->file('seller_store_media')->store('uploads/logos', 'public')
                        : null;

                    SellerVerification::create([
                        'user_id'           => $user->id,
                        'store_name'        => $request->store_name,
                        'store_description' => $request->store_description,
                        'id_type'           => $request->seller_id_type,
                        'document_path'     => $idPath,
                        'video_path'        => $videoPath,
                        'logo_path'         => $logoPath,
                        'plan'              => $request->seller_plan ?? 'free',
                        'status'            => 'pending',
                    ]);
                }

                return $user;
            });

            // 5. LOGIN
            Auth::login($user);

            // 6. REDIRECT TO login_success page
            return redirect()->route('login.success')
                ->with('success', 'Welcome to the family!');

        } catch (\Exception $e) {
            Log::error('Oppasabuy Transaction Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Database error: ' . $e->getMessage()
                ]);
        }
    }

    public function loginSuccess()
    {
        return view('auth.login_success');
    }

    public function registerRider(Request $request)
{
    // Basic validation
    $request->validate([
        'full_name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed',
    ]);

    // Pag-save sa User
    $user = User::create([
        'full_name' => $request->full_name,
        'name' => $request->full_name, // Minsan kailangan ang 'name' field ng Laravel Auth
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'rider', // Dito natin sila nilalagay sa 'rider' role
    ]);

    // Automatic login at redirect
    auth()->login($user);
    return redirect()->route('rider.dashboard');
}
}