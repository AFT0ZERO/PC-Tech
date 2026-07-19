<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\FavoriteService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct(private FavoriteService $favoriteService)
    {
    }

    public function toggleFavorite(Request $request, $productId)
    {
        $user = auth()->user();
        $result = $this->favoriteService->toggle($user, $productId);

        return response()->json($result, 200);
    }

    public function removeFavorite(Product $product)
    {
        $user = auth()->user();
        $this->favoriteService->remove($user, $product->id);

        return response()->json(['success' => true, 'message' => 'Product removed from favorites']);
    }


    public function listFavorites()
    {
        $favorites = $this->favoriteService->list(auth()->user());

        return view('userSide.pages.partials.favorites-list', compact('favorites'));
    }

}
