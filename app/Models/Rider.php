<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',

        'phone',
        'birth_date',
        'address',

        'emergency_contact_name',
        'emergency_contact_number',

        'vehicle_type',
        'vehicle_brand',
        'vehicle_model',
        'vehicle_color',
        'vehicle_plate',

        'license_number',
        'license_expiration',

        'license_img',
        'orcr_img',
        'vehicle_photo',
        'selfie_license',

        'rating',
        'rating_count',
        'earnings',
        'is_online',
        'status',
        'rejection_reason',
        'verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date',
        'license_expiration' => 'date',

        'is_online' => 'boolean',

        'rating' => 'decimal:2',
        'earnings' => 'decimal:2',

        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the rider profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}