<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content', 'image', 'video'];

    /**
     * The user who owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship for Likes.
     * Assumes you have a 'likes' table with post_id and user_id.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relationship for Comments.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    /**
     * Check if a specific user has liked this post.
     * Used in the Blade view to toggle heart color.
     */
    public function isLikedBy($user)
    {
        if (!$user) {
            return false;
        }
        
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}