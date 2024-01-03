<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\ProcessedOrder;
use Illuminate\Support\Facades\DB;

class OrderProcessingService
{
    public function processOrder($orderData)
    {
        DB::transaction(function () use ($orderData) {
            // Recalculate the subtotal from CartItems and Products
            $subtotal = $this->recalculateSubtotal($orderData['items']);

            // Create ProcessedOrder
            $processedOrder = ProcessedOrder::create([
                // ...fill with other order data...
                'subtotal' => $subtotal,
                // ...other fields...
            ]);


            // Clear the cart
            CartItem::truncate();
        });
    }

    protected function recalculateSubtotal($items)
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            $subtotal += $product->price * $item['quantity'];
        }

        return $subtotal;
    }
}
