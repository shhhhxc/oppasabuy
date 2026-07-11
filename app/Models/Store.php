<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'address',
        'business_email',
        'phone_number',
        'description',
        'store_types',        
        'green_market_type',  
        'followers',          // Added this to fillable
    ];

    protected $casts = [
        'store_types' => 'array',        
        'green_market_type' => 'array',  
        'followers' => 'array',        // Added this to cast as array
    ];

    public function verification()
    {
        return $this->hasOne(SellerVerification::class, 'user_id', 'user_id');
    }
}