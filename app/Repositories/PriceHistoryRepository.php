<?php

namespace App\Repositories;

use App\Models\PriceHistory;

class PriceHistoryRepository
{
    public function getAllHistoryForProduct(int $productId)
    {
        return PriceHistory::join('store_product', 'price_history.sp_id', '=', 'store_product.id')
            ->join('stores', 'store_product.store_id', '=', 'stores.id')
            ->where('store_product.product_id', $productId)
            ->where('price_history.status', 'ok')
            ->orderBy('price_history.scraped_at', 'asc')
            ->select('price_history.*', 'stores.name as store_name', 'store_product.store_id', 'store_product.product_url as store_url')
            ->get();
    }

    public function getLatestScrapedAt()
    {
        return PriceHistory::latest('scraped_at')->value('scraped_at');
    }

    public function countRecent(int $hours = 24)
    {
        return PriceHistory::where('scraped_at', '>=', now()->subHours($hours))->count();
    }
}
