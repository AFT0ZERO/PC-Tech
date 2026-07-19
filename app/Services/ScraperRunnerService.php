<?php

namespace App\Services;

use App\Repositories\PriceHistoryRepository;
use Illuminate\Support\Facades\Artisan;

class ScraperRunnerService
{
    public function __construct(private PriceHistoryRepository $priceHistoryRepository)
    {
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
            $exitCode = Artisan::call('scraper:run', $store ? ['--store' => $store] : []);
            $output   = Artisan::output();

            if ($exitCode === 0) {
                return ['success' => true, 'output' => $output];
            }
            return ['success' => false, 'output' => $output];
        } catch (\Exception $e) {
            return ['success' => false, 'output' => $e->getMessage()];
        }
    }
}
