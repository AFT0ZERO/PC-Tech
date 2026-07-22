<?php

namespace App\Jobs;

use App\Scraping\ScraperOrchestrator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunScraperJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;

    public function __construct(private ?string $storeName = null)
    {
    }

    public function handle(ScraperOrchestrator $orchestrator): void
    {
        Log::channel('scraper')->info(
            $this->storeName
                ? "Scraper job started for store: {$this->storeName}"
                : 'Scraper job started for all stores'
        );

        $orchestrator->run($this->storeName);

        Log::channel('scraper')->info('Scraper job completed.');
    }
}
