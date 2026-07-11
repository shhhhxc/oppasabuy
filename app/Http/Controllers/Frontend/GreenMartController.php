<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;

class GreenMartController extends Controller
{
    public function index()
    {
        // 1. Wet Markets
        $wetMarkets = Store::where('green_market_type', 'LIKE', '%"wet_market"%')->get();

        // 2. Sari-Sari Stores
        $sariSariStores = Store::where('green_market_type', 'LIKE', '%"sari_sari"%')->get();

        // 3. Featured Products
        $featuredProducts = Product::where('channel', 'green_market')
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('frontend.greenmart.index', compact('wetMarkets', 'sariSariStores', 'featuredProducts'));
    }
}