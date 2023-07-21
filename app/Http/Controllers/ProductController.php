<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    public function myindex(Request $request)
    {
        // Get authenticated user's ID
        $seller_id = $request->user()->id;

        // Fetch all products added by the authenticated user
        $products = Product::where('seller_id', $seller_id)->get();

        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {
        // Get authenticated user's ID
        $seller_id = $request->user()->id;

        $request->validate([
            'product_name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required',
        ]);

        // Add seller_id to the request data
        $productData = $request->all();
        $productData['seller_id'] = $seller_id;

        $product = Product::create($productData);

        return response()->json(['message' => 'Item has been added']);
    }

    // ...

    public function update(Request $request, $id)
    {
        // Get authenticated user's ID
        $seller_id = $request->user()->id;

        $product = Product::findOrFail($id);

        // Check if the authenticated user is the owner of the product
        if ($product->seller_id !== $seller_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $product->update($request->all());

        return new ProductResource($product);
    }

    // ...

    public function destroy(Request $request, $id)
    {
        // Get authenticated user's ID
        $seller_id = $request->user()->id;

        $product = Product::findOrFail($id);

        // Check if the authenticated user is the owner of the product
        if ($product->seller_id !== $seller_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
