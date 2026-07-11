<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 
        'date', 
        'max_slots', 
        'available_slots', 
        'dp_amount'
    ];

    /**
     * The seller who owns these slots.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * The products available for pick-up/distribution on this specific date.
     * This uses the pivot table: product_reservation_slot
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_reservation_slot', 'reservation_slot_id', 'product_id')
                    ->withTimestamps();
    }

    /**
     * All reservations booked for this specific date slot.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'slot_id');
    }
}