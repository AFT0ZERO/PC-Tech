<?php

namespace App\Services;

use App\Repositories\ComponentSpecReader;

class SpecMappingService
{
    public function __construct(private ComponentSpecReader $componentSpecReader)
    {
    }

    public function getBestMatch(string $query, string $dbPath): ?array
    {
        $exact = $this->componentSpecReader->findExact($query, $dbPath);
        if ($exact) {
            return $exact;
        }

        $results = $this->componentSpecReader->searchLike($query, $dbPath);

        if (empty($results)) {
            $words = explode(' ', $query);
            if (\count($words) > 1) {
                $results = $this->componentSpecReader->searchWordImploded($words, $dbPath);
            }
        }

        if (empty($results)) {
            return null;
        }

        $bestScore = 0;
        $bestData  = null;
        foreach ($results as $row) {
            similar_text(strtolower($query), strtolower($row['name']), $percent);
            if ($percent > $bestScore) {
                $bestScore = $percent;
                $bestData  = json_decode($row['specs_json'], true);
            }
        }

        return $bestScore >= 30 ? $bestData : json_decode($results[0]['specs_json'], true);
    }

    public function detectComponentType(array $data): string
    {
        if (isset($data['socket'], $data['chipset']))        return 'motherboard';
        if (isset($data['socket']))                          return 'cpu';
        if (isset($data['chipset_manufacturer']))            return 'gpu';
        if (isset($data['ram_type']) || isset($data['memory_type']) && !isset($data['chipset'])) return 'ram';
        if (isset($data['storage_type']) || isset($data['nvme'])) return 'storage';
        if (isset($data['wattage']))                         return 'psu';
        return 'unknown';
    }

    public function mapSpecs(array $data): array
    {
        $meta  = $data['metadata'] ?? [];
        $type  = $this->detectComponentType($data);

        $specs = match ($type) {
            'cpu'         => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? $data['series'] ?? null,
                'Socket'       => $data['socket'] ?? null,
                'Cores'        => $data['cores']['total'] ?? null,
                'Threads'      => $data['cores']['threads'] ?? null,
                'Base Clock'   => isset($data['clocks']['performance']['base'])
                                    ? "{$data['clocks']['performance']['base']} GHz" : null,
                'Boost Clock'  => isset($data['clocks']['performance']['boost'])
                                    ? "{$data['clocks']['performance']['boost']} GHz" : null,
                'TDP'          => isset($data['specifications']['tdp'])
                                    ? "{$data['specifications']['tdp']}W"
                                    : (isset($data['tdp']) ? "{$data['tdp']}W" : null),
            ],
            'ram'         => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
                'Memory Type'  => $data['ram_type'] ?? $data['memory_type'] ?? null,
                'Capacity'     => isset($data['capacity']) ? "{$data['capacity']} GB" : null,
                'Speed'        => isset($data['speed'])    ? "{$data['speed']} MHz"   : null,
            ],
            'storage'     => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
                'Type'         => $data['storage_type'] ?? $data['type'] ?? null,
                'Capacity'     => isset($data['capacity']) ? "{$data['capacity']} GB" : null,
                'Interface'    => $data['interface'] ?? null,
            ],
            'psu'         => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
                'Wattage'      => isset($data['wattage'])          ? "{$data['wattage']}W"          : null,
                'Length'       => isset($data['length'])           ? "{$data['length']} mm"          : null,
                'Efficiency'   => $data['efficiency_rating'] ?? null,
                'Modular'      => $data['modular'] ?? null,
            ],
            'gpu'         => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Chipset'      => $data['chipset'] ?? null,
                'VRAM'         => isset($data['memory']) ? "{$data['memory']} GB" : null,
                'Memory Type'  => $data['memory_type'] ?? null,
                'Core Clock'   => isset($data['core_base_clock'])  ? "{$data['core_base_clock']} MHz"  : null,
                'Boost Clock'  => isset($data['core_boost_clock']) ? "{$data['core_boost_clock']} MHz" : null,
                'Memory Bus'   => !empty($data['memory_bus'])      ? "{$data['memory_bus']} bit"        : null,
                'TDP'          => isset($data['tdp'])              ? "{$data['tdp']}W"                  : null,
                'Interface'    => $data['interface'] ?? null,
            ],
            'motherboard' => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Socket'       => $data['socket'] ?? null,
                'Chipset'      => $data['chipset'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
                'RAM Slots'    => $data['memory']['slots'] ?? null,
                'Max Memory'   => isset($data['memory']['max']) ? "{$data['memory']['max']} GB" : null,
                'Memory Type'  => $data['memory']['ram_type'] ?? null,
                'PCIe Slots'   => isset($data['pcie_slots']) ? \count($data['pcie_slots']) : null,
            ],
            default       => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
            ],
        };

        return array_filter($specs);
    }
}
