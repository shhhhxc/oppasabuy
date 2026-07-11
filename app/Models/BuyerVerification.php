<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerVerification extends Model
{
    use HasFactory;

    // This allows Laravel to save the data sent from your form
    protected $fillable = [
        'user_id',
        'id_type',
        'id_path',
        'status',
         'status',
    'rejection_reason',
    'verified_at',
    ];

    // Relationship: A verification belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    protected $casts = [
    'verified_at' => 'datetime',
];
}