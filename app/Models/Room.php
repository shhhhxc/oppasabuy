<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'creator_id',
    ];

    /**
     * The users that belong to the room (Group Members).
     * This defines the Many-to-Many relationship via the room_user pivot table.
     */
  public function users()
{
    return $this->belongsToMany(User::class, 'room_user', 'room_id', 'user_id');
}

    /**
     * Get the user who created the room.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * The messages sent within this specific room.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}