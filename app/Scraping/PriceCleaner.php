<?php

namespace App\Scraping;

class PriceCleaner
{
    /**
     * Strips away currency symbols and text, returning just the float value.
     * Mirrors Python BaseScraper.clean_price() exactly.
     *
     * Example: 'JD 25.99' -> 25.99
     */
    public function clean(?string $priceStr): ?float
    {
        if ($priceStr === null || $priceStr === '') {
            return null;
        }

        if (preg_match('/[\d,]+\.?\d*/', $priceStr, $matches)) {
            $cleanStr = str_replace(',', '', $matches[0]);

            if (is_numeric($cleanStr)) {
                return (float) $cleanStr;
            }
        }

        return null;
    }
}
