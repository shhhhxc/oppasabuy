<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'full_name', 'email', 'password', 'phone', 
        'address', 'description', 'intro_video', 'role', 
        'balance', 'is_verified',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Admin & Analytics Relationships (Oppasabuy Standardized)
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Get messages sent by the user (Changed to Message::class)
     */
    public function sentMessages()
    {
        // Points to the Message model using 'user_id' as the sender
        return $this->hasMany(Message::class, 'user_id');
    }

    /**
     * Get messages received by the user (Changed to Message::class)
     */
    public function receivedMessages()
    {
        // Points to the Message model using 'receiver_id'
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Orders where this user is the seller
     */
    public function sellerOrders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Existing Relationships
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Get the store configuration associated with the user where store_types JSON array is managed.
     */
    public function store()
    {
        return $this->hasOne(Store::class, 'user_id');
    }

    public function sellerVerification()
    {
        return $this->hasOne(SellerVerification::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function orders()
    {
        // Orders as a BUYER
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function buyerReservations()
    {
        return $this->hasMany(Reservation::class, 'buyer_id');
    }

    public function managedSlots()
    {
        return $this->hasMany(ReservationSlot::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_user', 'user_id', 'room_id');
    }

    public function targets()
    {
        return $this->hasMany(SellerTarget::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers & Attributes
    |--------------------------------------------------------------------------
    |
    */

    public function isVerifiedSeller()
    {
        return $this->sellerVerification && $this->sellerVerification->status === 'approved';
    }

    public function getBadgeLevelAttribute()
    {
        return $this->sellerVerification->badge_level ?? 'Basic Seller';
    }

    public function getWishlistOrCart()
    {
        return $this->wishlistItems->count() > 0 
            ? $this->wishlistItems 
            : session('cart', []);
    }

  public function riderProfile()
{
    return $this->hasOne(\App\Models\Rider::class, 'user_id');
}

public function rider()
{
    return $this->hasOne(Rider::class);
}
}