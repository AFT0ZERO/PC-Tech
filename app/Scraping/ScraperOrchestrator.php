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

    public function run(?string $storeName = null): array
    {
        Log::channel('scraper')->info('Starting Price Scraper Run');

        $startTime = microtime(true);

        $storeConfigs = $this->configManager->getStoreConfigs($storeName);

        if ($storeConfigs->isEmpty()) {
            Log::channel('scraper')->error("No store scraper configs found".($storeName ? " for '$storeName'" : ''));

            return [
                'success' => false,
                'output' => 'No store scraper configs found.',
            ];
        }

        $results = [];

        foreach ($storeConfigs as $config) {
            $results[] = $this->scrapeStore($config);
        }

        $duration = round(microtime(true) - $startTime, 2);

        $this->notifyTelegram($results, $duration);

        Log::channel('scraper')->info('Scraper Run Completed.');

        return [
            'success' => true,
            'output' => 'Scraper completed successfully.',
            'results' => $results,
        ];
    }

    private function scrapeStore($config): array
    {
        Log::channel('scraper')->info("--- Processing Store: {$config->storeName} ---");

        try {
            $products = $this->configManager->getProductsForStore($config->storeId);

            if ($config->mode === 'dynamic') {
                $scraper = new DynamicScraper();
            } else {
                $scraper = new StaticScraper();
            }

            $scraper->scrape($config, $products);

            return [
                'store' => $config->storeName,
                'mode' => $config->mode,
                'products' => $products->count(),
                'status' => 'completed',
            ];
        } catch (\Exception $e) {
            Log::channel('scraper')->error("Fatal error while scraping store {$config->storeName}: ".$e->getMessage());

            return [
                'store' => $config->storeName,
                'mode' => $config->mode,
                'products' => 0,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ];
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
            . "✅ نجح: {$success} صفحة\n"
            . "⏱️ المدة: {$duration} ثانية"
            . $failedText;

        Http::post("https://api.telegram.org/bot" . config('services.telegram.token') . "/sendMessage", [
            'chat_id' => config('services.telegram.chat_id'),
            'text' => $message,
        ]);
    }
}
