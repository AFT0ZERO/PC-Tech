<?php

namespace App\Services;

use App\Repositories\PriceHistoryRepository;
use App\Scraping\ScraperOrchestrator;
use Illuminate\Support\Facades\Log;

class ScraperRunnerService
{
    public function __construct(
        private PriceHistoryRepository $priceHistoryRepository,
        private ScraperOrchestrator $scraperOrchestrator,
    ) {
    }

    public function getStats(): array
    {
        return [
            'lastRun' => $this->priceHistoryRepository->getLatestScrapedAt(),
            'recentCount' => $this->priceHistoryRepository->countRecent(),
        ];
    }

    public function run(?string $store = null): array
    {
        try {
            $result = $this->scraperOrchestrator->run($store);

            return [
                'success' => $result['success'],
                'output' => $result['output'] ?? 'Scraper completed.',
            ];
        } catch (\Exception $e) {
            Log::error('Scraper run failed: '.$e->getMessage());

            return ['success' => false, 'output' => $e->getMessage()];
        }
    }
}
