<?php

use Illuminate\Support\Facades\Route;


/**
 * These are the aliases that we can use instead of typing the full command
 * alias p='vendor/bin/phpunit'
 * alias pf='vendor/bin/phpunit --filter'
 */


use App\Http\Controllers\SearchProductsController;
use App\Http\Controllers\CartController;

Route::get('/', [SearchProductsController::class, 'index']);

Route::get('/cart', [CartController::class, 'index']);

/**
 * When there will be a post request to /cart, the CartController@store will be called. This method
 * pushes the given item into the session cart. So, the cart is not saved into the database, but it is
 * saved in the session. And since the app uses blade files for the FE, therefore the blade FE
 * has access to data in the session. We redirect the user to the same page, however now the cart
 * is not empty, but it has the item that was added.
 */
Route::post('/cart', [CartController::class, 'store']);


Route::get('/checkout', function () {
    return view('checkout');
});

Route::get('/summary', function () {
    return view('summary');
});
