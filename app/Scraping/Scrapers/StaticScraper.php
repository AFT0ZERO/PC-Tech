<?php

namespace App\Scraping\Scrapers;

use App\Scraping\BaseScraper;
use App\Scraping\DTOs\ScrapeResult;
use App\Scraping\DTOs\StoreConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class StaticScraper extends BaseScraper
{
    private const MAX_RETRIES = 3;
    private const REQUEST_TIMEOUT = 15;
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    private function fetchUrl(string $url): ?string
    {
        for ($attempt = 0; $attempt < self::MAX_RETRIES; $attempt++) {
            try {
                $response = Http::withHeaders(['User-Agent' => self::USER_AGENT])
                    ->timeout(self::REQUEST_TIMEOUT)
                    ->get($url);

                if ($response->successful()) {
                    return $response->body();
                }

                Log::channel('scraper')->warning("HTTP {$response->status()} for $url");
            } catch (\Exception $e) {
                Log::channel('scraper')->warning("Attempt ".($attempt + 1)." failed for $url: ".$e->getMessage());
            }

            if ($attempt < self::MAX_RETRIES - 1) {
                sleep(2 ** $attempt);
            }
        }

        Log::channel('scraper')->error("Failed to fetch $url after ".self::MAX_RETRIES.' attempts.');

        return null;
    }

    public function scrape(StoreConfig $config, Collection $products): Collection
    {
        $storeName = $config->storeName;

        Log::channel('scraper')->info("[$storeName] Starting Static Scrape for {$products->count()} products.");

        $results = collect();

        foreach ($products as $product) {
            $productId = $product->product_id;
            $url = $product->url ?? $product->product_url ?? '';

            if ($this->shouldSkip($url)) {
                Log::channel('scraper')->info("[$storeName] Skipping Product $productId: placeholder URL.");
                $results->push(new ScrapeResult(
                    productId: $productId,
                    url: $url,
                    price: null,
                    status: 'skipped',
                    error: 'placeholder URL',
                ));
                continue;
            }

            Log::channel('scraper')->info("[$storeName] Fetching Product $productId: $url");

            $html = $this->fetchUrl($url);

            if ($html === null) {
                $this->saveToDb($productId, $storeName, $url, null, $config->currency);
                $results->push(new ScrapeResult(
                    productId: $productId,
                    url: $url,
                    price: null,
                    status: 'failed',
                    error: 'Failed to fetch URL after '.self::MAX_RETRIES.' attempts.',
                ));
                continue;
            }

            $crawler = new Crawler($html);

            $priceText = null;
            foreach ($config->priceSelectors as $selector) {
                try {
                    $node = $crawler->filter($selector)->first();
                    if ($node->count() > 0) {
                        $priceText = $node->text();
                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            if ($priceText === null) {
                Log::channel('scraper')->warning("[$storeName] None of the selectors found for Product $productId.");
                $this->saveToDb($productId, $storeName, $url, null, $config->currency);
                $results->push(new ScrapeResult(
                    productId: $productId,
                    url: $url,
                    price: null,
                    status: 'failed',
                    error: 'None of the price selectors matched.',
                ));
                continue;
            }

            $price = $this->cleanPrice($priceText);
            $this->saveToDb($productId, $storeName, $url, $price, $config->currency);
            $results->push(new ScrapeResult(
                productId: $productId,
                url: $url,
                price: $price,
                status: $price !== null ? 'ok' : 'failed',
                error: $price === null ? 'Price text could not be parsed.' : null,
            ));

            Log::channel('scraper')->debug("[$storeName] Sleeping for {$config->delay} seconds.");
            sleep($config->delay);
        }

        return $results;
    }
}
