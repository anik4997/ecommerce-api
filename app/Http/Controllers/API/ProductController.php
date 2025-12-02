<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title','like',"%{$search}%")
                  ->orWhere('description','like',"%{$search}%");
            });
        }

        $perPage = (int) $request->query('per_page', 10);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0'
        ]);

        $product = Product::create($data);

        return response()->json($product, 201);
    }
}
