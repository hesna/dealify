<?php

use App\Http\Controllers\Product\ProductShowController;
use App\Http\Controllers\Product\ProductStoreController;
use App\Http\Controllers\ProductDeal\ProductDealIndexController;
use App\Http\Controllers\ProductDeal\ProductDealStoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Checkout\CheckoutController;
use App\Http\Controllers\Product\ProductUpdateController;
use App\Http\Controllers\ProductDeal\ProductDealDestroyController;

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

Route::post('products', ProductStoreController::class);
Route::get('products/{product}', ProductShowController::class);
Route::put('products/{product}', ProductUpdateController::class);
Route::post('checkout', CheckoutController::class);
Route::get('products/{product}/deals', ProductDealIndexController::class);
Route::post('products/{product}/deals', ProductDealStoreController::class);
Route::delete('products/{product}/deals', ProductDealDestroyController::class);
