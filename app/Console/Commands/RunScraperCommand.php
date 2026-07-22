<?php

namespace App\Console\Commands;

use App\Scraping\ScraperOrchestrator;
use Illuminate\Console\Command;

class RunScraperCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'scraper:run {--store= : Scrape only a specific store by name}';

    /**
     * The console command description.
     */
    protected $description = 'Run the PHP price scraper';

    /**
     * Execute the console command.
     */
    public function handle(ScraperOrchestrator $orchestrator): int
    {
        $storeOption = $this->option('store') ?: null;

        $this->info($storeOption
            ? "Running scraper for store: {$storeOption}"
            : 'Running scraper for all stores');

        $result = $orchestrator->run($storeOption);

        if ($result['success']) {
            if (isset($result['results'])) {
                foreach ($result['results'] as $r) {
                    $this->info("  {$r['store']}: {$r['status']} ({$r['products']} products)");
                }
            }
            $this->info($result['output']);

            return 0;
        }

        $this->error($result['output']);

        return 1;
    }
}
