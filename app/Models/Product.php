<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'external_id',
        'title',
        'price',
        'description',
        'category',
        'image',
        'rating_rate',
        'rating_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating_rate' => 'decimal:1',
        'rating_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the customers through favorites.
     */
    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Favorite::class);
    }
}
