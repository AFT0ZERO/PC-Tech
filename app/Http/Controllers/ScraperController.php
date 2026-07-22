<?php

namespace App\Http\Controllers;

use App\Jobs\RunScraperJob;
use App\Models\Store;
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
        $stores = Store::orderBy('name')->get(['id', 'name']);

        return view('admin.scraper.index', [
            'lastRun' => $stats['lastRun'],
            'recentCount' => $stats['recentCount'],
            'stores' => $stores,
        ]);
    }

    public function run(Request $request)
    {
        $stores = $request->input('stores', []);
        $storeNames = array_filter($stores);

        RunScraperJob::dispatch($storeNames ?: null);

        return redirect()->route('scraper.index')
            ->with('success', !empty($storeNames)
                ? 'Scraper queued for selected stores.'
                : 'Full scraper queued for all stores.');
    }
}
