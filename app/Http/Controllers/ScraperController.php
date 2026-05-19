<?php

namespace App\Http\Controllers;

use App\Models\PriceHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ScraperController extends Controller
{
    
    public function index()
    {
        // Latest scrape time across all records
        $lastRun = PriceHistory::latest('scraped_at')->value('scraped_at');

        // Count of scrape records in last 24 hours
        $recentCount = PriceHistory::where('scraped_at', '>=', now()->subDay())->count();

        return view('admin.scraper.index', compact('lastRun', 'recentCount'));
    }

       public function run(Request $request)
    {
        $store = $request->input('store');

        try {
            $exitCode = Artisan::call('scraper:run', $store ? ['--store' => $store] : []);
            $output   = Artisan::output();

            if ($exitCode === 0) {
                return redirect()->route('scraper.index')
                    ->with('success', 'Scraper ran successfully.' . ($output ? ' Output: ' . $output : ''));
            } else {
                return redirect()->route('scraper.index')
                    ->with('error', 'Scraper finished with errors. Output: ' . $output);
            }
        } catch (\Exception $e) {
            return redirect()->route('scraper.index')
                ->with('error', 'Failed to run scraper: ' . $e->getMessage());
        }
    }
}
