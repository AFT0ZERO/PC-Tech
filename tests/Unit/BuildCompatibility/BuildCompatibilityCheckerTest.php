<?php

namespace Tests\Unit\BuildCompatibility;

use App\Models\Build;
use App\Models\BuildItem;
use App\Models\CaseSpec;
use App\Models\Category;
use App\Models\CpuCoolerSpec;
use App\Models\CpuSpec;
use App\Models\GpuSpec;
use App\Models\MotherboardSpec;
use App\Models\Product;
use App\Models\PsuSpec;
use App\Models\RamSpec;
use App\Support\BuildCompatibility\BuildCompatibilityChecker;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

/**
 * Unit tests for the checker + BuildContext over fully in-memory builds:
 * every relation the engine needs is preset on the models, so no query is
 * ever executed (the checker performs zero DB access by design). The Laravel
 * app is booted only because the Product/Category models themselves require
 * it (Scout's Searchable trait boots via the container).
 */
class BuildCompatibilityCheckerTest extends TestCase
{
    private BuildCompatibilityChecker $checker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checker = new BuildCompatibilityChecker;
    }

    private function makeProduct(string $specsTable, ?string $relation, ?Model $spec, int $powerDraw = 0): Product
    {
        $product = new Product(['name' => 'Part', 'power_draw_watts' => $powerDraw]);
        $product->setRelation('category', new Category(['name' => 'X', 'specs_table' => $specsTable]));

        foreach (Product::SPEC_RELATIONS as $rel) {
            $product->setRelation($rel, null);
        }

        if ($relation !== null) {
            $product->setRelation($relation, $spec);
        }

        return $product;
    }

    /** @param array<int, array{0: Product, 1: int}> $parts [product, quantity] pairs */
    private function makeBuild(array $parts): Build
    {
        $build = new Build();
        $build->setRelation('items', collect(array_map(
            fn (array $part) => (new BuildItem(['quantity' => $part[1]]))->setRelation('product', $part[0]),
            $parts,
        )));

        return $build;
    }

    private function compatibleBuild(): Build
    {
        return $this->makeBuild([
            [$this->makeProduct('cpu_specs', 'cpuSpec', new CpuSpec(['socket' => 'AM5']), 100), 1],
            [$this->makeProduct('motherboard_specs', 'motherboardSpec', new MotherboardSpec([
                'socket' => 'AM5', 'supported_ram_type' => 'DDR5',
                'ram_slots' => 4, 'max_ram_capacity_gb' => 128, 'form_factor' => 'ATX',
            ]), 5), 1],
            [$this->makeProduct('ram_specs', 'ramSpec', new RamSpec(['type' => 'DDR5', 'capacity_gb' => 16]), 35), 2],
            [$this->makeProduct('gpu_specs', 'gpuSpec', new GpuSpec(['length_mm' => 300, 'vram_gb' => 12]), 200), 1],
            [$this->makeProduct('cpu_cooler_specs', 'cpuCoolerSpec', new CpuCoolerSpec([
                'supported_sockets' => ['AM5', 'LGA1700'], 'height_mm' => 160,
            ]), 10), 1],
            [$this->makeProduct('case_specs', 'caseSpec', new CaseSpec([
                'supported_form_factors' => ['ATX', 'mATX'],
                'max_gpu_length_mm' => 320, 'max_cooler_height_mm' => 165,
            ]), 0), 1],
            [$this->makeProduct('psu_specs', 'psuSpec', new PsuSpec(['wattage' => 600]), 0), 1],
        ]);
    }

    public function test_fully_compatible_build_returns_no_violations(): void
    {
        $violations = $this->checker->check($this->compatibleBuild());

        $this->assertSame([], $violations);
        $this->assertTrue($this->checker->isCompatible($this->compatibleBuild()));
    }

    public function test_socket_mismatch_is_detected(): void
    {
        $build = $this->makeBuild([
            [$this->makeProduct('cpu_specs', 'cpuSpec', new CpuSpec(['socket' => 'AM5'])), 1],
            [$this->makeProduct('motherboard_specs', 'motherboardSpec', new MotherboardSpec(['socket' => 'LGA1700'])), 1],
        ]);

        $violations = $this->checker->check($build);

        $this->assertCount(1, $violations);
        $this->assertSame('direct_match', $violations[0]->ruleType);
        $this->assertFalse($this->checker->isCompatible($build));
    }

    public function test_multiple_violations_accumulate(): void
    {
        $build = $this->makeBuild([
            [$this->makeProduct('cpu_specs', 'cpuSpec', new CpuSpec(['socket' => 'AM5']), 105), 1],
            [$this->makeProduct('motherboard_specs', 'motherboardSpec', new MotherboardSpec([
                'socket' => 'LGA1700', 'form_factor' => 'ATX',
            ])), 1],
            [$this->makeProduct('gpu_specs', 'gpuSpec', new GpuSpec(['length_mm' => 350]), 320), 1],
            [$this->makeProduct('case_specs', 'caseSpec', new CaseSpec([
                'supported_form_factors' => ['mATX'], 'max_gpu_length_mm' => 300, 'max_cooler_height_mm' => 160,
            ])), 1],
            [$this->makeProduct('psu_specs', 'psuSpec', new PsuSpec(['wattage' => 300])), 1],
        ]);

        $violations = $this->checker->check($build);

        // socket mismatch + motherboard form factor not in case set + gpu too long + PSU too weak
        $this->assertCount(4, $violations);
        $this->assertEqualsCanonicalizing(
            ['direct_match', 'set_membership', 'dimensional', 'aggregate'],
            array_map(fn ($v) => $v->ruleType, $violations),
        );
    }

    public function test_partial_build_yields_no_violations(): void
    {
        $build = $this->makeBuild([
            [$this->makeProduct('cpu_specs', 'cpuSpec', new CpuSpec(['socket' => 'AM5']), 100), 1],
        ]);

        $this->assertSame([], $this->checker->check($build));
    }

    public function test_item_quantity_counts_toward_power_draw(): void
    {
        // 100W CPU + 2 × 200W GPU = 500W; 500 × 1.2 = 600W > 550W PSU
        $build = $this->makeBuild([
            [$this->makeProduct('cpu_specs', 'cpuSpec', new CpuSpec(['socket' => 'AM5']), 100), 1],
            [$this->makeProduct('gpu_specs', 'gpuSpec', new GpuSpec(['length_mm' => 250]), 200), 2],
            [$this->makeProduct('psu_specs', 'psuSpec', new PsuSpec(['wattage' => 550])), 1],
        ]);

        $violations = $this->checker->check($build);

        $this->assertCount(1, $violations);
        $this->assertSame('aggregate', $violations[0]->ruleType);
        $this->assertSame(600.0, $violations[0]->context['computed_source_value']);
    }

    public function test_category_without_specs_table_is_ignored_by_rules_but_counts_power(): void
    {
        // Accessory (no specs table) drawing 500W against a 500W PSU → 600W needed → violation
        $accessory = new Product(['name' => 'LED strip', 'power_draw_watts' => 500]);
        $accessory->setRelation('category', new Category(['name' => 'Accessory', 'specs_table' => null]));
        foreach (Product::SPEC_RELATIONS as $rel) {
            $accessory->setRelation($rel, null);
        }

        $build = $this->makeBuild([
            [$accessory, 1],
            [$this->makeProduct('psu_specs', 'psuSpec', new PsuSpec(['wattage' => 500])), 1],
        ]);

        $violations = $this->checker->check($build);

        $this->assertCount(1, $violations);
        $this->assertSame('aggregate', $violations[0]->ruleType);
    }
}
