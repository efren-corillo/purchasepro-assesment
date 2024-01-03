<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessedOrder extends Model
{
    use HasFactory;

    // Set the table name if it's not the plural form of the model name
    protected $table = 'processed_orders';

    // Allow mass assignment for all fields in your table
    protected $guarded = [];

    // Cast the 'items' column to an array (or collection, depending on your needs)
    protected $casts = [
        'items' => 'array',
    ];
}
