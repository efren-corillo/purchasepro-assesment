<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'price', 'description', 'image', 'rating'];

    protected $casts = [
        'rating' => 'array',
    ];

    public function catalogs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(related: Catalog::class);
    }

    /**
     * Get the cart items associated with the product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }
}