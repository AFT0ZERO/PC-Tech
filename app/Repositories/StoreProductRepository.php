<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Store;

class StoreProductRepository
{
    public function attach(Product $product, int $storeId, array $data): void
    {
        $product->stores()->attach($storeId, $data);
    }

    public function updateExistingPivot(Product $product, int $storeId, array $data): void
    {
        $product->stores()->updateExistingPivot($storeId, $data);
    }

    public function exists(Product $product, int $storeId): bool
    {
        return $product->stores()->where('store_id', $storeId)->exists();
    }

    public function allStoresList()
    {
        return Store::all();
    }
}
