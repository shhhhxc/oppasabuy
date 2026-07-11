<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user profile and/or store logo.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // 1. Handle Personal Profile Picture
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profiles', 'public');
        }

        // 2. Handle Store Logo (For Sellers)
        if ($request->hasFile('logo_path')) {
            $sellerInfo = DB::table('seller_verifications')->where('user_id', $user->id)->first();
            if ($sellerInfo) {
                if ($sellerInfo->logo_path && Storage::disk('public')->exists($sellerInfo->logo_path)) {
                    Storage::disk('public')->delete($sellerInfo->logo_path);
                }
                $newLogoPath = $request->file('logo_path')->store('logos', 'public');
                DB::table('seller_verifications')
                    ->where('user_id', $user->id)
                    ->update(['logo_path' => $newLogoPath]);
            }
        }

        // 3. Handle Password Change
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Account and Store settings updated successfully!');
    }
}