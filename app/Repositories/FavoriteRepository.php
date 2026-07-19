<?php

namespace App\Repositories;

use App\Models\User;

class FavoriteRepository
{
    public function has(User $user, int $productId): bool
    {
        return $user->favorites()->where('product_id', $productId)->exists();
    }

    public function attach(User $user, int $productId): void
    {
        $user->favorites()->attach($productId);
    }

    public function detach(User $user, int $productId): void
    {
        $user->favorites()->detach($productId);
    }

    public function listWithImages(User $user)
    {
        return $user->favorites()->with('images')->get();
    }
}
