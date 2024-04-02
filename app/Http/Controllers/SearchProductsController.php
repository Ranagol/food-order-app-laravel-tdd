<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchProductsController extends Controller
{
    public function index()
    {
        $query_str = request('query');
        // dd($query_str);
        $items = Product::when($query_str, function ($query, $query_str) {
                    return $query->where('name', 'LIKE', "%{$query_str}%");
                })->get();

        // dd($items);

        return view('search', compact('items', 'query_str'));
    }
}
