<?php

namespace App\Http\Controllers;

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
        $store = $request->input('store');
        $result = $this->scraperRunnerService->run($store);

        if ($result['success']) {
            return redirect()->route('scraper.index')
                ->with('success', 'Scraper ran successfully.' . ($result['output'] ? ' Output: ' . $result['output'] : ''));
        }
        return redirect()->route('scraper.index')
            ->with('error', $result['output']
                ? 'Scraper finished with errors. Output: ' . $result['output']
                : 'Failed to run scraper: ' . $result['output']);
    }
}
