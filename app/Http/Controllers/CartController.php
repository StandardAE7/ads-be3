<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $product = Product::findOrFail($request->product_id);
        $availableStock = $product->stock;
    
        if ($request->quantity > $availableStock) {
            return response()->json(['message' => 'The quantity exceeds the available stock'], 422);
        }
    
        $user_id = Auth::id();
    
        // Check if the product is already in the cart, if yes, update the quantity
        $existingCart = Cart::where('user_id', $user_id)->where('product_id', $request->product_id)->first();
    
        if ($existingCart) {
            $newQuantity = min($request->quantity + $existingCart->quantity, $availableStock);
            $existingCart->update(['quantity' => $newQuantity]);
            return new CartResource($existingCart);
        }
    
        $cart = new Cart();
        $cart->user_id = $user_id;
        $cart->product_id = $request->product_id;
        $cart->quantity = $request->quantity;
        $cart->save();        
    
        return new CartResource($cart);
    }

    public function showCart(Request $request)
    {
        $user_id = Auth::id();
        $carts = Cart::where('user_id', $user_id)->get();
    
        return CartResource::collection($carts);
    }


    public function checkout(Request $request)
    {
        // Get authenticated user
        $user_id = Auth::id();
    
        // Get all cart items for the user
        $carts = Cart::where('user_id', $user_id)->get();
        $totalPrice = 0;
    
        foreach ($carts as $cart) {
            $product = Product::findOrFail($cart->product_id);
            $availableStock = $product->stock;
    
            // Check if the cart quantity exceeds the available stock
            if ($cart->quantity > $availableStock) {
                // Remove the item from the cart if quantity exceeds stock
                $cart->delete();
    
                // Add a message that the item with the given ID cannot be checked out
                return response()->json(['message' => 'Item with ID ' . $cart->product_id . ' cannot be checked out due to insufficient stock'], 422);
            }
    
            // Perform actions related to the checkout process here, e.g., updating product quantity or creating order records.
    
            // Decrease the product stock after successful checkout
            $product->decrement('stock', $cart->quantity);
    
            // Calculate the total price for the cart item and add it to the total price
            $totalPrice += $cart->quantity * $product->price;
    
            // Remove the item from the cart after successful checkout
            $cart->delete();
        }
    
        // Return the total price after successful checkout
        return response()->json(['message' => 'Checkout successful', 'total_price' => $totalPrice]);
    }
    
}
