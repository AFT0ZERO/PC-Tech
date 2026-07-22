<?php

namespace App\Services;

use App\Models\Store;
use App\Repositories\ScraperConfigStore;
use Illuminate\Support\Facades\DB;

class ScraperConfigService
{
    public function __construct(private ScraperConfigStore $scraperConfigStore)
    {
    }

    /**
     * Sync store_product URLs from the database into store_scraper_configs.
     * Replaces the old config.json file-based sync.
     */
    public function sync(): void
    {
        $stores = Store::all();

        foreach ($stores as $store) {
            DB::table('store_scraper_configs')->updateOrInsert(
                ['store_id' => $store->id],
                [
                    'mode' => DB::raw('COALESCE(mode, "static")'),
                ]
            );
        }
    }
}
