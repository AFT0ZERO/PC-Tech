<?php

namespace App\Services;

use App\Repositories\ScraperConfigStore;

class ScraperConfigService
{
    public function __construct(private ScraperConfigStore $scraperConfigStore)
    {
    }

    public function sync(): void
    {
        $configPath = base_path('scraper/config.json');
        $config = $this->scraperConfigStore->read($configPath);

        if (!$config || !isset($config['stores'])) return;

        $storeProducts = $this->scraperConfigStore->getActiveStoreProducts();

        $storeUrls = [];
        foreach ($storeProducts as $sp) {
            if (!empty($sp->product_url)) {
                $storeUrls[$sp->store_id][] = [
                    'part_id' => $sp->product_id,
                    'url' => $sp->product_url,
                ];
            }
        }

        $storesFromDb = $this->scraperConfigStore->getAllStoresKeyedByName();

        foreach ($config['stores'] as &$configStore) {
            $storeName = $configStore['store_name'];
            if ($storesFromDb->has($storeName)) {
                $dbStore = $storesFromDb->get($storeName);
                $configStore['products'] = $storeUrls[$dbStore->id] ?? [];
            }
        }

        $this->scraperConfigStore->write($configPath, $config);
    }
}
