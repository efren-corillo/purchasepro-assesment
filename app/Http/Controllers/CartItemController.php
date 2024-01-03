<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartItemController extends Controller
{
    public function index()
    {
        try {
            $items = CartItem::all();

        $response = response()->json(['data' => ['cartItems' => $items]]);
    } catch (\Exception $ex) {

        $message = $ex->getMessage();

        $response = response()->json(['error' => $message, 'e' => $ex], 403);
    }

        return $response;
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);


        try {
            DB::beginTransaction();

            $cartItem = CartItem::firstOrNew(
                ['product_id' => $request->id],
                ['quantity' => 0]
            );

            $cartItem->quantity += $request->quantity;
            $cartItem->save();

            DB::commit();

            return response()->json($cartItem, 201);

        } catch (Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // You can log the exception if needed
            // Log::error($e->getMessage());

            // Return an error response
            return response()->json(['error' => 'An error occurred while adding the item to the cart.'], 500);
        }
    }
}
