<?php

namespace App\Scraping;

use App\Scraping\Scrapers\DynamicScraper;
use App\Scraping\Scrapers\StaticScraper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScraperOrchestrator
{
    public function __construct(
        private ConfigManager $configManager,
    ) {
    }

    public function run(null|array $storeNames = null): array
    {
        $startTime = microtime(true);

        $storeConfigs = $this->configManager->getStoreConfigs($storeNames);

        if ($storeConfigs->isEmpty()) {
            Log::channel('scraper')->error("No store scraper configs found".(!empty($storeNames) ? " for selected stores" : ''));

            return [
                'success' => false,
                'output' => 'No store scraper configs found.',
            ];
        }

        echo '[' . date('H:i:s') . '] Scraping ' . $storeConfigs->count() . ' store(s)' . PHP_EOL;

        $results = [];

        foreach ($storeConfigs as $config) {
            $results[] = $this->scrapeStore($config);
        }

        $duration = round(microtime(true) - $startTime, 2);

        $ok = collect($results)->where('status', 'completed')->count();
        $failed = collect($results)->where('status', 'failed')->count();

        echo '[' . date('H:i:s') . '] Done — ' . $ok . ' succeeded, ' . $failed . ' failed (' . $duration . 's)' . PHP_EOL;

        $this->notifyTelegram($results, $duration);

        return [
            'success' => true,
            'output' => 'Scraper completed successfully.',
            'results' => $results,
        ];
    }

    private function scrapeStore($config): array
    {
        $products = $this->configManager->getProductsForStore($config->storeId);

        echo '[' . date('H:i:s') . ']  Store: ' . $config->storeName . ' (' . $products->count() . ' products, ' . $config->mode . ')' . PHP_EOL;

        try {
            if ($config->mode === 'dynamic') {
                $scraper = new DynamicScraper();
            } else {
                $scraper = new StaticScraper();
            }

            $urlResults = $scraper->scrape($config, $products);

            $this->logUrlResults($config->storeName, $urlResults);

            echo '[' . date('H:i:s') . ']    -> ' . $urlResults->where('status', 'ok')->count() . ' ok, '
                . $urlResults->where('status', 'skipped')->count() . ' skipped, '
                . $urlResults->where('status', 'failed')->count() . ' failed' . PHP_EOL;

            return [
                'store' => $config->storeName,
                'mode' => $config->mode,
                'products' => $products->count(),
                'status' => 'completed',
                'url_results' => $urlResults->map(fn ($r) => [
                    'product_id' => $r->productId,
                    'url' => $r->url,
                    'price' => $r->price,
                    'status' => $r->status,
                    'error' => $r->error,
                ])->all(),
            ];
        } catch (\Exception $e) {
            Log::channel('scraper')->error("Fatal error while scraping store {$config->storeName}: ".$e->getMessage());

            echo '[' . date('H:i:s') . ']    -> FAILED: ' . $e->getMessage() . PHP_EOL;

            return [
                'store' => $config->storeName,
                'mode' => $config->mode,
                'products' => 0,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function logUrlResults(string $storeName, \Illuminate\Support\Collection $results): void
    {
        foreach ($results as $result) {
            if (in_array($result->status, ['ok', 'skipped'], true)) {
                continue;
            }

            $message = "[$storeName] Failed to get price: product={$result->productId} url={$result->url} reason=\"{$result->error}\"";

            Log::channel('scraper')->warning($message);
        }
    }

    private function notifyTelegram(array $results, float $duration): void
    {
        $success = collect($results)->where('status', 'completed')->count();
        $failed = collect($results)->where('status', 'failed')->pluck('store')->toArray();

        $failedText = count($failed) > 0
            ? "\n⚠️ فشلت: " . implode(', ', $failed)
            : "\n✅ كل المحلات نجحت";

        $message = "🔄 انتهى Scraping الأسعار\n"
            . "✅ نجح: {$success} متجر\n"
            . "⏱️ المدة: {$duration} ثانية"
            . $failedText;

        Http::post("https://api.telegram.org/bot" . config('services.telegram.token') . "/sendMessage", [
            'chat_id' => config('services.telegram.chat_id'),
            'text' => $message,
        ]);
    }
}