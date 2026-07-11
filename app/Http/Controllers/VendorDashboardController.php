<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function index()
    {
        // Get the logged-in user's store
        $store = Store::where('user_id', Auth::id())->first();

        // If they don't have a store yet, send them to register
        if (!$store) {
            return redirect()->route('vendor.register.create')
                ->with('info', 'Please complete your shop registration first.');
        }

        return view('vendor.dashboard', compact('store'));
    }
}