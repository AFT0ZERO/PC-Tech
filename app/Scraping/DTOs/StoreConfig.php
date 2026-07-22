<?php

namespace App\Scraping\DTOs;

class StoreConfig
{
    public function __construct(
        public readonly int $storeId,
        public readonly string $storeName,
        public readonly string $baseUrl,
        public readonly string $mode,
        public readonly int $delay,
        public readonly array $priceSelectors,
        public readonly string $currency,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            storeId: (int) $data['store_id'],
            storeName: $data['store_name'],
            baseUrl: $data['base_url'],
            mode: $data['mode'],
            delay: (int) $data['delay'],
            priceSelectors: $data['price_selectors'],
            currency: $data['currency'] ?? 'JOD',
        );
    }
}
