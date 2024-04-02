<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * alias p='vendor/bin/phpunit'
 * alias pf='vendor/bin/phpunit --filter'
 */

Route::get('/', function () {
    return view('search');
});

Route::get('/cart', function () {
    return view('cart');
});


Route::get('/checkout', function () {
    return view('checkout');
});

Route::get('/summary', function () {
    return view('summary');
});
