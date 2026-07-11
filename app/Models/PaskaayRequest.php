<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaskaayRequest extends Model
{
    protected $fillable = [
        'user_id',
        'rider_id',
        'pickup_address',
        'destination_address',
        'note',
        'status',
        'pickup_lat',
        'pickup_lng',
        'dest_lat',
        'dest_lng',
        'latitude',
        'longitude',
        'fare',
        'vehicle_type',
    ];

    /**
     * Customer who created the booking.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Rider assigned to this booking.
     * rider_id stores the User ID of the rider,
     * so we link it to riders.user_id.
     */
    public function rider()
    {
        return $this->belongsTo(
            Rider::class,
            'rider_id',
            'user_id'
        );
    }
}
