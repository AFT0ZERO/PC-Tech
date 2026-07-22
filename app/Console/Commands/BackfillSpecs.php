<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * One-off / re-runnable repair: older products (seeded or scraper-imported)
 * carry their specs only inside products.description as a JSON map with
 * human-readable labels ("Socket / CPU", "Memory Max") — or have no usable
 * specs at all. The compatibility engine reads the typed spec tables
 * (cpu_specs, motherboard_specs, ...), which stay empty for those products,
 * so rules silently skip them.
 *
 * Phase 1 maps known description labels onto the spec columns.
 * Phase 2 matches still-uncovered products against the scraper's SQLite
 * component database by name and copies the structured specs over.
 *
 * Products that already have a spec row are never touched.
 */
class BackfillSpecs extends Command
{
    protected $signature = 'specs:backfill';

    protected $description = 'Backfill empty spec tables from descriptions and the SQLite component DB';

    /** Minimum fraction of product-name tokens that must appear in a SQLite row name. */
    private const MATCH_THRESHOLD = 0.75;

    /**
     * spec table => [spec column => accepted description JSON keys, in priority order]
     */
    private const KEY_MAP = [
        'cpu_specs' => [
            'socket' => ['Socket', 'Processor Socket'],
        ],
        'motherboard_specs' => [
            'socket' => ['Socket / CPU', 'Socket'],
            'supported_ram_type' => ['Memory Type', 'Supported RAM Type'],
            'ram_slots' => ['Memory Slots', 'RAM Slots'],
            'max_ram_capacity_gb' => ['Memory Max', 'Max Memory', 'Maximum Memory'],
            'form_factor' => ['Form Factor'],
        ],
        'ram_specs' => [
            'type' => ['Memory Type', 'Type'],
            'capacity_gb' => ['Capacity'],
        ],
        'psu_specs' => [
            'wattage' => ['Wattage', 'Power'],
        ],
        'gpu_specs' => [
            'length_mm' => ['Length'],
            'vram_gb' => ['VRAM', 'Memory'],
        ],
        'case_specs' => [
            'supported_form_factors' => ['Form Factor Support', 'Supported Form Factors', 'Motherboard Compatibility'],
            'max_gpu_length_mm' => ['Max GPU Length', 'Maximum GPU Length'],
            'max_cooler_height_mm' => ['Max CPU Cooler Height', 'Maximum CPU Cooler Height'],
        ],
        'cpu_cooler_specs' => [
            'supported_sockets' => ['Supported Sockets'],
            'height_mm' => ['Height'],
        ],
        'storage_specs' => [
            'interface' => ['Interface'],
            'capacity_gb' => ['Capacity'],
        ],
    ];

    /**
     * spec table => [spec column => path inside the SQLite specs_json
     * (dot notation for nested values)]
     */
    private const SQLITE_MAP = [
        'cpu_specs' => [
            'socket' => 'socket',
        ],
        'motherboard_specs' => [
            'socket' => 'socket',
            'supported_ram_type' => 'memory.ram_type',
            'ram_slots' => 'memory.slots',
            'max_ram_capacity_gb' => 'memory.max',
            'form_factor' => 'form_factor',
        ],
        'ram_specs' => [
            'type' => 'ram_type',
            'capacity_gb' => 'capacity',
        ],
        'psu_specs' => [
            'wattage' => 'wattage',
        ],
        'gpu_specs' => [
            'length_mm' => 'length',
            'vram_gb' => 'memory',
        ],
        'case_specs' => [
            'supported_form_factors' => 'supported_motherboard_form_factors',
            'max_gpu_length_mm' => 'max_video_card_length',
            'max_cooler_height_mm' => 'max_cpu_cooler_height',
        ],
        'cpu_cooler_specs' => [
            'supported_sockets' => 'cpu_sockets',
            'height_mm' => 'height',
        ],
        'storage_specs' => [
            'interface' => 'interface',
            'capacity_gb' => 'capacity',
        ],
    ];

    /** Columns that store a JSON array — scalar values are wrapped. */
    private const JSON_COLUMNS = ['supported_form_factors', 'supported_sockets'];

    /** Columns that store a number — digits are extracted from values like "192 GB". */
    private const NUMERIC_COLUMNS = [
        'ram_slots', 'max_ram_capacity_gb', 'capacity_gb',
        'wattage', 'length_mm', 'vram_gb', 'max_gpu_length_mm',
        'max_cooler_height_mm', 'height_mm', 'power_draw_watts',
    ];

    /**
     * SQLite category => path to its TDP inside specs_json. Only the major
     * power drawers are tracked — CPU and GPU account for ~90% of system draw
     * (motherboards, RAM, storage etc. have no TDP data in the component DB).
     */
    private const SQLITE_POWER_MAP = [
        'CPU' => 'specifications.tdp',
        'GPU' => 'tdp',
    ];

    public function handle(): int
    {
        [$created, $skipped] = $this->backfillFromDescriptions();
        $this->info("Phase 1 (descriptions): {$created} created, {$skipped} skipped.");

        [$created, $skipped] = $this->backfillFromSqlite();
        $this->info("Phase 2 (SQLite component DB): {$created} created, {$skipped} skipped.");

        [$created, $skipped] = $this->backfillPowerFromSqlite();
        $this->info("Phase 3 (power draw): {$created} updated, {$skipped} skipped.");

        return self::SUCCESS;
    }

    // ── Phase 1: products.description JSON ──────────────────────────────────

    private function backfillFromDescriptions(): array
    {
        $created = 0;
        $skipped = 0;

        foreach (Category::whereNotNull('specs_table')->get() as $category) {
            $map = self::KEY_MAP[$category->specs_table] ?? null;

            if ($map === null) {
                continue;
            }

            foreach (Product::where('category_id', $category->id)->get() as $product) {
                if ($this->hasSpecRow($category->specs_table, $product->id)) {
                    continue;
                }

                $description = json_decode($product->description ?? '', true);

                if (! is_array($description)) {
                    $skipped++;

                    continue;
                }

                $values = [];

                foreach ($map as $column => $acceptedKeys) {
                    $value = $this->extract($description, $acceptedKeys);

                    if ($value !== null) {
                        $values[$column] = $this->castValue($column, $value);
                    }
                }

                if ($values === []) {
                    $skipped++;

                    continue;
                }

                $this->insertSpecRow($category->specs_table, $product, $values);
                $created++;
            }
        }

        return [$created, $skipped];
    }

    // ── Phase 2: SQLite component database ──────────────────────────────────

    private function backfillFromSqlite(): array
    {
        $created = 0;
        $skipped = 0;

        $dbPath = base_path('database/components.sqlite');

        if (! file_exists($dbPath)) {
            $this->warn("SQLite component DB not found at {$dbPath} — phase 2 skipped.");

            return [0, 0];
        }

        $pdo = new \PDO("sqlite:{$dbPath}");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        foreach (Category::whereNotNull('specs_table')->get() as $category) {
            $map = self::SQLITE_MAP[$category->specs_table] ?? null;

            if ($map === null || empty($category->open_db_name)) {
                continue;
            }

            $rows = $pdo
                ->query('SELECT name, specs_json FROM components WHERE category = '.$pdo->quote($category->open_db_name))
                ->fetchAll(\PDO::FETCH_ASSOC);

            if ($rows === []) {
                continue;
            }

            $candidates = array_map(fn (array $row) => [
                'name' => $row['name'],
                'tokens' => $this->tokenize($row['name']),
                'specs' => json_decode($row['specs_json'], true) ?? [],
            ], $rows);

            foreach (Product::where('category_id', $category->id)->get() as $product) {
                if ($this->hasSpecRow($category->specs_table, $product->id)) {
                    continue;
                }

                $values = $this->matchSqliteRow($product, $candidates, $map);

                if ($values === null) {
                    $skipped++;
                    $this->line("  <comment>skipped</comment> {$category->specs_table}: product #{$product->id} ({$product->name}) — no unambiguous SQLite match");

                    continue;
                }

                $this->insertSpecRow($category->specs_table, $product, $values);
                $created++;
            }
        }

        return [$created, $skipped];
    }

    // ── Phase 3: products.power_draw_watts from SQLite ──────────────────────

    private function backfillPowerFromSqlite(): array
    {
        $created = 0;
        $skipped = 0;

        $dbPath = base_path('database/components.sqlite');

        if (! file_exists($dbPath)) {
            $this->warn("SQLite component DB not found at {$dbPath} — phase 3 skipped.");

            return [0, 0];
        }

        $pdo = new \PDO("sqlite:{$dbPath}");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        foreach (self::SQLITE_POWER_MAP as $sqliteCategory => $tdpPath) {
            $category = Category::where('open_db_name', $sqliteCategory)->first();

            if ($category === null) {
                continue;
            }

            $rows = $pdo
                ->query('SELECT name, specs_json FROM components WHERE category = '.$pdo->quote($sqliteCategory))
                ->fetchAll(\PDO::FETCH_ASSOC);

            $candidates = array_map(fn (array $row) => [
                'name' => $row['name'],
                'tokens' => $this->tokenize($row['name']),
                'specs' => json_decode($row['specs_json'], true) ?? [],
            ], $rows);

            $products = Product::where('category_id', $category->id)
                ->whereNull('power_draw_watts')
                ->get();

            foreach ($products as $product) {
                $values = $this->matchSqliteRow($product, $candidates, ['power_draw_watts' => $tdpPath]);
                $tdp = $values['power_draw_watts'] ?? null;

                if ($tdp === null) {
                    $skipped++;
                    $this->line("  <comment>skipped</comment> power: product #{$product->id} ({$product->name}) — no unambiguous SQLite match");

                    continue;
                }

                $product->update(['power_draw_watts' => $tdp]);
                $created++;
                $this->line("  power_draw_watts={$tdp}: product #{$product->id} ({$product->name})");
            }
        }

        return [$created, $skipped];
    }

    /**
     * Score every SQLite candidate by how many of the product's name tokens it
     * contains, then map the top-scoring rows to spec values. Tied rows must
     * agree on every mapped value — otherwise the match is ambiguous and the
     * product is skipped (wrong specs are worse than missing specs).
     *
     * @return array<string, mixed>|null
     */
    private function matchSqliteRow(Product $product, array $candidates, array $map): ?array
    {
        $brandTokens = $this->tokenize($product->brand ?? '');
        $productTokens = array_values(array_diff($this->tokenize($product->name), $brandTokens));

        if ($productTokens === []) {
            return null;
        }

        $best = 0.0;
        $topRows = [];

        foreach ($candidates as $candidate) {
            // Brand must be identifiable in the row name.
            if (count(array_intersect($brandTokens, $candidate['tokens'])) < count($brandTokens)) {
                continue;
            }

            $score = count(array_intersect($productTokens, $candidate['tokens'])) / count($productTokens);

            if ($score > $best) {
                $best = $score;
                $topRows = [$candidate];
            } elseif ($score === $best) {
                $topRows[] = $candidate;
            }
        }

        if ($best < self::MATCH_THRESHOLD) {
            return null;
        }

        $agreed = null;

        foreach ($topRows as $row) {
            $values = $this->valuesFromSqliteSpecs($row['specs'], $map);

            if ($values === []) {
                return null;
            }

            if ($agreed === null) {
                $agreed = $values;
            } elseif ($agreed != $values) {
                return null; // tied rows disagree — ambiguous
            }
        }

        return $agreed;
    }

    /** @return array<string, mixed> */
    private function valuesFromSqliteSpecs(array $specs, array $map): array
    {
        $values = [];

        foreach ($map as $column => $path) {
            $value = data_get($specs, $path);

            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            if (in_array($column, self::JSON_COLUMNS, true)) {
                $values[$column] = json_encode(is_array($value) ? array_values($value) : [$value]);
            } elseif (in_array($column, self::NUMERIC_COLUMNS, true)) {
                if (preg_match('/\d+/', (string) $value, $m)) {
                    $values[$column] = (int) $m[0];
                }
            } else {
                $values[$column] = is_array($value) ? json_encode($value) : (string) $value;
            }
        }

        return $values;
    }

    /** @return array<int, string> */
    private function tokenize(string $text): array
    {
        return array_values(array_filter(preg_split('/[^a-z0-9]+/i', strtolower($text))));
    }

    // ── Shared helpers ──────────────────────────────────────────────────────

    private function hasSpecRow(string $table, int $productId): bool
    {
        return DB::table($table)->where('product_id', $productId)->exists();
    }

    private function insertSpecRow(string $table, Product $product, array $values): void
    {
        DB::table($table)->insert(['product_id' => $product->id] + $values);
        $this->line("  {$table}: product #{$product->id} ({$product->name})");
    }

    /** First non-empty description value among the accepted keys. */
    private function extract(array $description, array $acceptedKeys): ?string
    {
        foreach ($acceptedKeys as $key) {
            $value = $description[$key] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return null;
    }

    private function castValue(string $column, string $value): mixed
    {
        if (in_array($column, self::JSON_COLUMNS, true)) {
            return json_encode([$value]);
        }

        if (in_array($column, self::NUMERIC_COLUMNS, true)) {
            if (preg_match('/\d+/', $value, $m)) {
                return (int) $m[0];
            }

            return null;
        }

        return $value;
    }
}
