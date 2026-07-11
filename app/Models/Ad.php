<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

   protected $fillable = [
    'image_path', 
    'video_path', // Add this
    'title', 
    'target_url', 
    'is_active', 
    'type'        // Add this to distinguish between banners and videos
];
}