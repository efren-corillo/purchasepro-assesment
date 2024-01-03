<?php

use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('test', [Controller::class, 'TestRoute']);

Route::get('/send-test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('test@example.com')->subject('Test Email');
    });

    return 'Email sent!';
});



Route::resource('catalog', CatalogController::class);

Route::get('list-products-by-catalog/{id}', [ProductController::class, 'listProductByCatalog']);



Route::resource('cart-items', CartItemController::class);
Route::get('cart-items-with-product', [CartItemController::class, 'getItemsWithProducts']);
Route::post('add-product-to-cart', [CartItemController::class, 'addToCart']);
Route::post('confirm-order', [CartItemController::class, 'confirmOrder']);
Route::post('update-cart-quantity', [CartItemController::class, 'updateCartQuantity']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
