<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmed;
use App\Models\CartItem;
use App\Models\ProcessedOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    /**
     * Remove the specified resource from storage.
     *
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $catalog = CartItem::findOrFail($id);
                $catalog->delete();
            });

            $response = response()->json(['message' => 'Item in cart deleted successfully']);
        } catch (\Exception $ex) {
            $response = response()->json(['error' => $ex->getMessage()], 500);
        }

        return $response;
    }

    public function updateCartQuantity(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $cartItem = CartItem::find($request->id);

            $cartItem->quantity = (int) $request->quantity;

            $success = $cartItem->save();
            if ($success) {
                DB::commit();

                return response()->json(['data' => ['item' => $cartItem]]);
            } else {
                return response()->json(['error' => 'An error occurred while adding the item to the cart.'], 500);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'An error occurred while adding the item to the cart.'], 500);
        }
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
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

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'An error occurred while adding the item to the cart.'], 500);
        }
    }

    /**
     * get all products in the cart
     */
    public function getItemsWithProducts(): JsonResponse
    {
        try {
            $cartItems = CartItem::with('product')->get();

            $response = response()->json(['data' => ['cartItems' => $cartItems]]);
        } catch (\Exception $ex) {
            $response = response()->json(['error' => 'An error occurred while retrieving cart items.', 'messages' => $ex], 500);
        }

        return $response;
    }

    public function confirmOrder(Request $request)
    {
        $request->validate([
            'customer.email' => 'required',
            'customer.firstName' => 'required',
            'customer.lastName' => 'required',
            'customer.company' => 'required',
            'customer.address' => 'required',
            'customer.apartetc' => 'required',
            'customer.city' => 'required',
            'customer.country' => 'required',
            'customer.state' => 'required',
            'customer.postal' => 'required',
            'customer.phone' => 'required',
            'customer.card.number' => 'required',
            'customer.card.name' => 'required',
            'customer.card.expiration' => 'required',
            'customer.card.cvc' => 'required',
        ]);

        // let's get the cart items and compute the necessary fields.
        $cartItems = CartItem::with('product')->get();

        $subTotal = 0;

        foreach ($cartItems as $item) {
            $subTotal += (float) $item->product->price * $item->quantity;
        }

        try {
            DB::beginTransaction();

            // store this data to processed_order table
            $order = new ProcessedOrder();

            $order->order_number = date('Ymd').Str::random(8);
            $order->email = $request->input('customer.email');
            $order->first_name = $request->input('customer.firstName');
            $order->last_name = $request->input('customer.lastName');
            $order->company = $request->input('customer.company');
            $order->address = $request->input('customer.address');
            $order->apartetc = $request->input('customer.apartetc');
            $order->city = $request->input('customer.city');
            $order->country = $request->input('customer.country');
            $order->state = $request->input('customer.state');
            $order->postal = $request->input('customer.postal');
            $order->phone = $request->input('customer.phone');
            $order->card_number = $request->input('customer.card.number');
            $order->card_name = $request->input('customer.card.name');
            $order->card_expiration = $request->input('customer.card.expiration');
            $order->card_cvc = $request->input('customer.card.cvc');
            $order->subtotal = $subTotal;
            $order->shipping_fee = $request->input('shipping_fee');
            $order->taxes = $request->input('taxes');
            $order->total = $request->input('taxes') + $subTotal + $request->input('shipping_fee');
            $order->items = json_encode($cartItems);

            if ($order->save()) {
                Mail::to($request->input('customer.email'))->send(new OrderConfirmed($order));

                CartItem::query()->delete();

                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Order processed successfully.']);
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Failed to process the order.'], 500);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            $response = response()->json(['error' => 'An error occurred while processing your order.', 'messages' => $ex], 500);
        }

        return $response;
    }
}
