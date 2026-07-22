<?php

namespace App\Scraping\Contracts;

use App\Scraping\DTOs\ScrapeResult;
use App\Scraping\DTOs\StoreConfig;
use Illuminate\Support\Collection;

interface ScraperInterface
{
    public function scrape(StoreConfig $config, Collection $products): Collection;
}
