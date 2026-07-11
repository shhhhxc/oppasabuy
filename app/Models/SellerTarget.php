<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'target_value',
        'current_value',
        'period'
    ];

    // Relationship to the user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}