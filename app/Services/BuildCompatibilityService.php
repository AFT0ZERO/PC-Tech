<?php

namespace App\Services;

use App\Models\Product;

class BuildCompatibilityService
{
    /**
     * Check compatibility for a set of selected product IDs.
     * Returns an array of human-readable warning strings.
     * Warnings are non-blocking — the user can still save their build.
     *
     * @param  array<int>  $productIds
     * @return array<string>
     */
    public function check(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        $products = Product::with('category')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy(fn ($p) => strtolower($p->category->name ?? ''));

        $warnings = [];

        $cpu         = $products->get('cpu');
        $motherboard = $products->get('motherboard');
        $cooler      = $products->get('cooler');
        $psu         = $products->get('psu');
        $case        = $products->get('case');

        // ── Rule 1: CPU socket vs Motherboard socket ─────────────────────────
        if ($cpu && $motherboard) {
            $cpuSocket = $cpu->socket ?? $this->extractSocket($cpu->brand ?? '');
            $mbSocket  = $motherboard->socket ?? $this->extractSocket($motherboard->brand ?? '');

            if ($cpuSocket && $mbSocket && strtolower($cpuSocket) !== strtolower($mbSocket)) {
                $warnings[] = "Socket mismatch: CPU uses socket \"{$cpuSocket}\" but Motherboard supports \"{$mbSocket}\".";
            }
        }

        // ── Rule 2: Motherboard form factor vs Case form factor ──────────────
        if ($motherboard && $case) {
            $mbForm   = $motherboard->form_factor ?? null;
            $caseForm = $case->form_factor ?? null;

            if ($mbForm && $caseForm && strtolower($mbForm) !== strtolower($caseForm)) {
                $warnings[] = "Form factor mismatch: Motherboard is \"{$mbForm}\" but Case supports \"{$caseForm}\".";
            }
        }

        // ── Rule 3: Cooler TDP vs CPU TDP ────────────────────────────────────
        if ($cooler && $cpu) {
            $coolerTdp = $cooler->tdp;
            $cpuTdp    = $cpu->tdp;

            if ($coolerTdp !== null && $cpuTdp !== null && $coolerTdp < $cpuTdp) {
                $warnings[] = "Cooling warning: Cooler is rated for {$coolerTdp}W TDP but your CPU has a {$cpuTdp}W TDP.";
            }
        }

        // ── Rule 4: PSU wattage vs total TDP of all parts ────────────────────
        if ($psu) {
            $psuWattage = $psu->tdp; // PSU wattage is stored in the tdp column

            $totalTdp = $products
                ->filter(fn ($p) => strtolower($p->category->name ?? '') !== 'psu' && $p->tdp !== null)
                ->sum('tdp');

            if ($psuWattage !== null && $totalTdp > 0 && $psuWattage < $totalTdp) {
                $warnings[] = "Power warning: PSU is rated at {$psuWattage}W but estimated total system TDP is {$totalTdp}W.";
            }
        }

        return $warnings;
    }

    /**
     * Try to detect a socket name from a brand/name string as a fallback.
     */
    private function extractSocket(string $text): ?string
    {
        if (preg_match('/\b(AM[45]\+?|TR[45]\+?|LGA\s*\d{3,4}|FM[12]\+?)\b/i', $text, $m)) {
            return strtoupper(trim($m[1]));
        }
        return null;
    }
}
