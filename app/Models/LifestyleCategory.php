<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LifestyleCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_category',
        'icon',
    ];

    /**
     * Optional: Helper to get all products associated with this category.
     * Assuming your Product model has a 'category' column.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'name');
    }
}