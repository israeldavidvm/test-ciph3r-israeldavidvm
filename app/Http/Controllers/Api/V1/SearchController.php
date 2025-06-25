<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle the search request for products.
     */
    public function products(Request $request)
    {
        
        $request->validate([
            'query' => 'required|string|min:2', 
        ]);

        $query = $request->input('query');
        $perPage = $request->input('per_page', 10); 

        $products = Product::search($query)->paginate($perPage);

        return response()->json($products);
    }
}