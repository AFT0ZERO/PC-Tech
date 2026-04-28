<?php

namespace App\Console\Commands;

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
    protected $description = 'Run the Python price scraper microservice';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $scraperPath = base_path('scraper/scraper.py');

        if (!file_exists($scraperPath)) {
            $this->error("Scraper not found at: {$scraperPath}");
            return 1;
        }

        $storeOption = $this->option('store');
        $storeFlag   = $storeOption ? " --store " . escapeshellarg($storeOption) : '';
        $command     = "python " . escapeshellarg($scraperPath) . $storeFlag . " 2>&1";

        $this->info("Running: {$command}");

        exec($command, $output, $exitCode);

        foreach ($output as $line) {
            $this->line($line);
        }

        if ($exitCode === 0) {
            $this->info('Scraper completed successfully.');
        } else {
            $this->error("Scraper exited with code {$exitCode}.");
        }

        return $exitCode;
    }
}
