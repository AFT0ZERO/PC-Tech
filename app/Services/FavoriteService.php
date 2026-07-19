<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\FavoriteRepository;

class FavoriteService
{
    public function __construct(private FavoriteRepository $favoriteRepository)
    {
    }

    public function toggle(User $user, int $productId): array
    {
        if ($this->favoriteRepository->has($user, $productId)) {
            $this->favoriteRepository->detach($user, $productId);
            return ['message' => 'Product removed from favorites', 'status' => 'removed'];
        }
        $this->favoriteRepository->attach($user, $productId);
        return ['message' => 'Product added to favorites', 'status' => 'added'];
    }

    public function remove(User $user, int $productId): void
    {
        $this->favoriteRepository->detach($user, $productId);
    }

    public function list(User $user)
    {
        return $this->favoriteRepository->listWithImages($user);
    }
}
