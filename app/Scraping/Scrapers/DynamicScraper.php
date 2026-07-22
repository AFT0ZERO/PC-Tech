<?php

namespace App\Scraping\Scrapers;

use App\Scraping\BaseScraper;
use App\Scraping\DTOs\ScrapeResult;
use App\Scraping\DTOs\StoreConfig;
use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DynamicScraper extends BaseScraper
{
    private const MAX_RETRIES = 3;
    private const NAVIGATION_TIMEOUT = 30000;
    private const SELECTOR_TIMEOUT_MS = 10000;
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
    private const BROWSER_RESTART_INTERVAL = 20;

    public function scrape(StoreConfig $config, Collection $products): Collection
    {
        $storeName = $config->storeName;

        $browserFactory = new BrowserFactory();

        $browser = $browserFactory->createBrowser([
            'headless' => true,
            'noSandbox' => true,
            'userAgent' => self::USER_AGENT,
            'enableImages' => false,
        ]);

        $results = collect();

        try {
            $page = $browser->createPage();

            $productIndex = 0;
            foreach ($products as $product) {
                $productId = $product->product_id;
                $url = $product->url ?? $product->product_url ?? '';

                if ($this->shouldSkip($url)) {
                    $results->push(new ScrapeResult(
                        productId: $productId,
                        url: $url,
                        price: null,
                        status: 'skipped',
                        error: 'placeholder URL',
                    ));
                    continue;
                }

                $productIndex++;

                if ($productIndex > 1 && $productIndex % self::BROWSER_RESTART_INTERVAL === 0) {
                    $page->close();
                    $page = $browser->createPage();
                }

                $rawText = $this->fetchPrice($page, $url, $config->priceSelectors, $storeName, $productId);

                if ($rawText === null) {
                    $this->saveToDb($productId, $storeName, $url, null, $config->currency);
                    $results->push(new ScrapeResult(
                        productId: $productId,
                        url: $url,
                        price: null,
                        status: 'failed',
                        error: 'Failed to extract price from URL after '.self::MAX_RETRIES.' attempts.',
                    ));
                } else {
                    $price = $this->cleanPrice($rawText);
                    $this->saveToDb($productId, $storeName, $url, $price, $config->currency);
                    $results->push(new ScrapeResult(
                        productId: $productId,
                        url: $url,
                        price: $price,
                        status: $price !== null ? 'ok' : 'failed',
                        error: $price === null ? 'Price text could not be parsed.' : null,
                    ));
                }

                sleep($config->delay);
            }

            $page->close();
        } finally {
            $browser->close();
        }

        return $results;
    }

    private function fetchPrice(Page $page, string $url, array $priceSelectors, string $storeName, int $productId): ?string
    {
        if (empty($priceSelectors)) {
            Log::channel('scraper')->warning("[$storeName] Failed to get price: product=$productId url=$url reason=\"No price selectors configured.\"");

            return null;
        }

        $lastReason = null;

        for ($attempt = 0; $attempt < self::MAX_RETRIES; $attempt++) {
            try {
                $navigation = $page->navigate($url);

                try {
                    $navigation->waitForNavigation(Page::DOM_CONTENT_LOADED, self::NAVIGATION_TIMEOUT);
                } catch (\Exception $e) {
                    $lastReason = 'Navigation timeout';
                }

                // Wait for selectors with retry loop
                $elapsed = 0;
                $pollMs = 500;
                $found = false;

                while ($elapsed < self::SELECTOR_TIMEOUT_MS) {
                    foreach ($priceSelectors as $selector) {
                        try {
                            $node = $page->dom()->querySelector($selector);
                            if ($node !== null) {
                                $text = $node->getText();
                                if (!empty(trim($text))) {
                                    return trim($text);
                                }
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }

                    usleep($pollMs * 1000);
                    $elapsed += $pollMs;
                }

                $lastReason = $lastReason ?? 'All price selectors timed out';
                return null;

            } catch (\Exception $e) {
                $lastReason = $e->getMessage();

                if ($attempt < self::MAX_RETRIES - 1) {
                    sleep(2 ** $attempt);
                }
            }
        }

        Log::channel('scraper')->warning("[$storeName] Failed to get price: product=$productId url=$url reason=\"$lastReason after ".self::MAX_RETRIES.' attempts."');

        return null;
    }
}
