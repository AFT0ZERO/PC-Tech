<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreScraperConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configPath = base_path('scraper/config.json');

        if (!file_exists($configPath)) {
            $this->command?->warn('scraper/config.json not found. Skipping StoreScraperConfig seeding.');
            return;
        }

        $config = json_decode(file_get_contents($configPath), true);

        if (!$config || !isset($config['stores'])) {
            $this->command?->warn('No stores found in config.json. Skipping.');
            return;
        }

        foreach ($config['stores'] as $storeData) {
            $store = Store::where('name', $storeData['store_name'])->first();

            if (!$store) {
                $this->command?->warn("Store '{$storeData['store_name']}' not found in DB. Skipping.");
                continue;
            }

            DB::table('store_scraper_configs')->updateOrInsert(
                ['store_id' => $store->id],
                [
                    'mode' => $storeData['mode'] ?? 'static',
                    'delay' => $storeData['delay'] ?? 3,
                    'price_selectors' => json_encode($storeData['price_selectors'] ?? []),
                    'currency' => $storeData['currency'] ?? 'JOD',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $this->command?->info("Seeded scraper config for: {$storeData['store_name']}");
        }
    }
}
