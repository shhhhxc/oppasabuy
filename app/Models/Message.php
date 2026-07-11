<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * * user_id: The person who sent the message.
     * order_id: The conversation/order room ID.
     */
     protected $fillable = [
    'order_id', // Nullable if Room Chat
    'room_id',  // Nullable if Order Chat
    'user_id',
    'message',
    'image_path',
    'video_path'
];

    /**
     * Get the user who sent the message (The Sender).
     * This links 'user_id' in messages table to 'id' in users table.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


public function room() {
    return $this->belongsTo(Room::class);
}


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}