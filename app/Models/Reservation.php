<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id', 
        'seller_id', 
        'slot_id', 
        'slot_number', 
        'product_id',
        'status', 
        'payment_proof'
    ];

    /**
     * The product being reserved.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * The buyer who made the reservation.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * The seller providing the service/product.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * The specific date slot this reservation belongs to.
     */
    public function slot()
    {
        return $this->belongsTo(ReservationSlot::class, 'slot_id');
    }
}