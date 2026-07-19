<?php

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Support\Facades\DB;

class ScraperConfigStore
{
    public function read(string $configPath): ?array
    {
        if (!file_exists($configPath)) return null;
        $configJson = file_get_contents($configPath);
        return json_decode($configJson, true);
    }

    public function write(string $configPath, array $config): void
    {
        file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function getActiveStoreProducts()
    {
        return DB::table('store_product')
            ->join('products', 'products.id', '=', 'store_product.product_id')
            ->whereNull('products.deleted_at')
            ->select('store_product.store_id', 'store_product.product_id', 'store_product.product_url')
            ->get();
    }

    public function getAllStoresKeyedByName()
    {
        return Store::all()->keyBy('name');
    }
}
