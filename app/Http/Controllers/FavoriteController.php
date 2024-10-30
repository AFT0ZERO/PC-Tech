<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request, $productId)
    {
        $user = auth()->user();
// Check if the product is already in favorites
        if ($user->favorites()->where('product_id', $productId)->exists()) {
            // If it exists, remove it from favorites
            $user->favorites()->detach($productId);
            return response()->json(['message' => 'Product removed from favorites', 'status' => 'removed'], 200);
        } else {
            // If it does not exist, add it to favorites
            $user->favorites()->attach($productId);
            return response()->json(['message' => 'Product added to favorites', 'status' => 'added'], 200);
        }
    }

    public function removeFavorite(Product $product)
    {
        $user = auth()->user();
        $user->favorites()->detach($product->id); // Detach the product from user's favorites

        return response()->json(['success' => true, 'message' => 'Product removed from favorites']);
    }


    public function listFavorites()
    {
        $favorites = auth()->user()->favorites()->with('images')->get(); // Retrieve favorites with images
        return view('userSide.pages.partials.favorites-list', compact('favorites'));
    }

}
