<?php

namespace App\Scraping;

use App\Models\PriceHistory;
use App\Models\StoreProduct;
use App\Scraping\Contracts\ScraperInterface;
use App\Scraping\DTOs\ScrapeResult;
use App\Scraping\DTOs\StoreConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseScraper implements ScraperInterface
{
    protected PriceCleaner $priceCleaner;

    public function __construct(?PriceCleaner $priceCleaner = null)
    {
        $this->priceCleaner = $priceCleaner ?? new PriceCleaner();
    }

    public function cleanPrice(?string $priceStr): ?float
    {
        return $this->priceCleaner->clean($priceStr);
    }

    public function saveToDb(int $productId, string $storeName, string $url, ?float $price, string $currency): bool
    {
        if ($price === null) {
            Log::channel('scraper')->warning("[$storeName] Skipping DB insert for Product $productId: No valid price.");

            return $this->writeDbRecord($productId, $storeName, $url, 0, $currency, 'failed');
        }

        Log::channel('scraper')->info("[$storeName] Saving Product $productId: $currency $price");

        return $this->writeDbRecord($productId, $storeName, $url, $price, $currency, 'ok');
    }

    protected function writeDbRecord(int $productId, string $storeName, string $url, float $price, string $currency, string $status): bool
    {
        return DB::transaction(function () use ($productId, $storeName, $url, $price, $currency, $status) {
            $sp = StoreProduct::where('product_id', $productId)
                ->whereHas('store', fn ($q) => $q->where('name', $storeName))
                ->first();

            if (!$sp) {
                Log::channel('scraper')->error("Store product relation not found for product $productId and store $storeName");
                return false;
            }

            PriceHistory::create([
                'sp_id' => $sp->id,
                'price' => $price,
                'currency' => $currency,
                'status' => $status,
            ]);

            if ($status === 'ok') {
                $sp->update(['product_price' => $price]);
            }

            if ($url && $url !== '#') {
                $sp->update(['product_url' => $url]);
            }

            return true;
        });
    }

    public function shouldSkip(string $url): bool
    {
        return $url === '#' || trim($url) === '';
    }
}
