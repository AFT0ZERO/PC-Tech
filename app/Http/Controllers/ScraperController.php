<?php

namespace App\Http\Controllers;

use App\Jobs\RunScraperJob;
use App\Services\ScraperRunnerService;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    public function __construct(private ScraperRunnerService $scraperRunnerService)
    {
    }

    public function index()
    {
        $stats = $this->scraperRunnerService->getStats();

        return view('admin.scraper.index', [
            'lastRun' => $stats['lastRun'],
            'recentCount' => $stats['recentCount'],
        ]);
    }

    public function run(Request $request)
    {
        $store = $request->input('store') ?: null;

        RunScraperJob::dispatch($store);

        return redirect()->route('scraper.index')
            ->with('success', $store
                ? "Scraper queued for store: {$store}"
                : 'Full scraper queued for all stores.');
    }
}
