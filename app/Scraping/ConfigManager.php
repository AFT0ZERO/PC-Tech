<?php

namespace App\Scraping;

use App\Scraping\DTOs\StoreConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ConfigManager
{
    public function getStoreConfigs(null|array $storeNames = null): Collection
    {
        $query = DB::table('stores')
            ->join('store_scraper_configs', 'stores.id', '=', 'store_scraper_configs.store_id')
            ->whereNull('stores.deleted_at')
            ->select(
                'stores.id as store_id',
                'stores.name as store_name',
                'store_scraper_configs.mode',
                'store_scraper_configs.delay',
                'store_scraper_configs.price_selectors',
                'store_scraper_configs.currency',
            );

        if (!empty($storeNames)) {
            $query->whereIn('stores.name', $storeNames);
        }

        return $query->get()->map(function ($row) {
            $selectors = json_decode($row->price_selectors, true) ?? [];

            return StoreConfig::fromArray([
                'store_id' => (int) $row->store_id,
                'store_name' => $row->store_name,
                'base_url' => '',
                'mode' => $row->mode,
                'delay' => (int) $row->delay,
                'price_selectors' => $selectors,
                'currency' => $row->currency ?? 'JOD',
            ]);
        });
    }

    public function getProductsForStore(int $storeId): Collection
    {
        return DB::table('store_product')
            ->where('store_id', $storeId)
            ->join('products', 'products.id', '=', 'store_product.product_id')
            ->whereNull('products.deleted_at')
            ->select('store_product.product_id', 'store_product.product_url as url')
            ->get();
    }
}
