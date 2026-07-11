<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User; // Ensure this is imported
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function showStore($id)
    {
        // Use the Model instead of DB::table to support the relationships in your view
        $seller = User::with(['sellerVerification', 'products'])
            ->where('id', $id)
            ->firstOrFail();

        // Get products separately if you want to handle search/filtering later
        $products = $seller->products; 

        return view('store.show', compact('seller', 'products'));
    }
}