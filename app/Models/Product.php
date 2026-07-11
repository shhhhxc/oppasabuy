<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
   protected $fillable = [
    'user_id',
    'seller_id',
    'name',
    'description',
    'price',
    'stock', 
    'category',
    'subcategory', // Add this
    'channel',
    'meta_data',
    'image_path',
    'is_featured',
    'on_sale',           // Add this
    'discount_value',    // Add this
    'discount_type'      // Add this
];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'meta_data'   => 'array',   // Automatically switches JSON to an array and back
        'is_featured' => 'boolean',
    ];

    /**
     * Relationship: A product belongs to a User (the Seller).
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function store()
{
    // Adjust 'store_id' if your foreign key is named differently
    return $this->belongsTo(\App\Models\Store::class, 'store_id');
}

public function getIsAdminAttribute()
{
    // If your database column is named 'is_admin' and uses 1/0
    return $this->is_admin == 1; 
}

// Add this to your Product model
public function getDiscountedPriceAttribute()
{
    if (!$this->on_sale) {
        return $this->price;
    }

    if ($this->discount_type === 'percent') {
        // Example: 5% off = price * 0.95
        return $this->price * (1 - ($this->discount_value / 100));
    }

    // Fixed price reduction
    return $this->price - $this->discount_value;
}

}