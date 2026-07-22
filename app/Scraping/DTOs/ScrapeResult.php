<?php

namespace App\Scraping\DTOs;

class ScrapeResult
{
    public function __construct(
        public readonly int $productId,
        public readonly string $url,
        public readonly ?float $price,
        public readonly string $status, // 'ok' | 'failed'
        public readonly ?string $error = null,
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->status === 'ok';
    }
}
