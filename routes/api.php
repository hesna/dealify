<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDealController;

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

Route::apiResource('products', ProductController::class)->except(['index', 'destroy']);
Route::apiResource('products.deals', ProductDealController::class)->only(['index', 'store']);
Route::delete('products/{product}/deals', [ProductDealController::class, 'destroy']);
Route::post('checkout', CheckoutController::class);
