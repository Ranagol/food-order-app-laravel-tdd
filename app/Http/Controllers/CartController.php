<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $productIdsFromSession = collect(session('cart'))->pluck('id');
        // dd($productIdsFromSession);//4 & 5
        $products = Product::all();
        dd($products);
        $items = Product::whereIn('id', $productIdsFromSession)->get();
        $cart_items = collect(session('cart'))->map(function ($row, $index) use ($items) {
            return [
                'id' => $row['id'],
                'qty' => $row['qty'],
                'name' => $items[$index]->name,
                'image' => $items[$index]->image,
                'cost' => $items[$index]->cost,
            ];
        })->toArray();

        return view('cart', compact('cart_items'));
    }

    /**
     * When there will be a post request to /cart, the CartController@store will be called. This method
     * pushes the given item into the session cart. So, the cart is not saved into the database, but it is
     * saved in the session. And since the app uses blade files for the FE, therefore the blade FE
     * has access to data in the session. We redirect the user to the same page, however now the cart
     * is not empty, but it has the item that was added.
     */
    public function store(Request $request)
    {

        // dd($request);
        // dd($request->id);
        $existing = collect(session('cart'))->first(function ($row, $key) use ($request){
            return $row['id'] == $request->id;
        });

        // dd($existing);
    
        if (!$existing) {
            // Push the item into the session 'cart'
            session()->push('cart', [
                'id' => request('id'),
                'qty' => 1,
            ]);
        }
    
        return redirect('/cart');
    }
}