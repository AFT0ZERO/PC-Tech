<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Services\BuildCompatibilityService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BuildCompatibilityServiceTest extends TestCase
{
    use DatabaseTransactions;

    private BuildCompatibilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BuildCompatibilityService;
    }

    private function createCategory(string $name): Category
    {
        return Category::factory()->create(['name' => $name]);
    }

    private function createProduct(Category $category, array $overrides = []): Product
    {
        return Product::factory()->create(array_merge([
            'category_id' => $category->id,
        ], $overrides));
    }

    public function test_empty_array_returns_no_warnings(): void
    {
        $result = $this->service->check([]);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_same_cpu_and_motherboard_socket_no_warning(): void
    {
        $cpuCat = $this->createCategory('CPU');
        $mbCat = $this->createCategory('Motherboard');

        $cpu = $this->createProduct($cpuCat, ['socket' => 'LGA1700', 'brand' => 'Intel']);
        $mb = $this->createProduct($mbCat, ['socket' => 'LGA1700', 'brand' => 'ASUS']);

        $result = $this->service->check([$cpu->id, $mb->id]);

        $this->assertEmpty($result);
    }

    public function test_different_cpu_and_motherboard_socket_returns_warning(): void
    {
        $cpuCat = $this->createCategory('CPU');
        $mbCat = $this->createCategory('Motherboard');

        $cpu = $this->createProduct($cpuCat, ['socket' => 'AM5', 'brand' => 'AMD']);
        $mb = $this->createProduct($mbCat, ['socket' => 'LGA1700', 'brand' => 'ASUS']);

        $result = $this->service->check([$cpu->id, $mb->id]);

        $this->assertCount(1, $result);
        $this->assertStringContainsString('Socket mismatch', $result[0]);
        $this->assertStringContainsString('AM5', $result[0]);
        $this->assertStringContainsString('LGA1700', $result[0]);
    }

    public function test_socket_fallback_from_brand_regex(): void
    {
        $cpuCat = $this->createCategory('CPU');
        $mbCat = $this->createCategory('Motherboard');

        $cpu = $this->createProduct($cpuCat, ['socket' => null, 'brand' => 'AMD Ryzen 5 7600X AM5']);
        $mb = $this->createProduct($mbCat, ['socket' => 'LGA1700', 'brand' => 'ASUS']);

        $result = $this->service->check([$cpu->id, $mb->id]);

        $this->assertCount(1, $result);
        $this->assertStringContainsString('Socket mismatch', $result[0]);
        $this->assertStringContainsString('AM5', $result[0]);
    }

    public function test_case_insensitive_socket_comparison(): void
    {
        $cpuCat = $this->createCategory('CPU');
        $mbCat = $this->createCategory('Motherboard');

        $cpu = $this->createProduct($cpuCat, ['socket' => 'AM5', 'brand' => 'AMD']);
        $mb = $this->createProduct($mbCat, ['socket' => 'am5', 'brand' => 'ASUS']);

        $result = $this->service->check([$cpu->id, $mb->id]);

        $this->assertEmpty($result);
    }

    public function test_matching_form_factor_no_warning(): void
    {
        $mbCat = $this->createCategory('Motherboard');
        $caseCat = $this->createCategory('Case');

        $mb = $this->createProduct($mbCat, ['form_factor' => 'ATX']);
        $case = $this->createProduct($caseCat, ['form_factor' => 'ATX']);

        $result = $this->service->check([$mb->id, $case->id]);

        $this->assertEmpty($result);
    }

    public function test_different_form_factor_returns_warning(): void
    {
        $mbCat = $this->createCategory('Motherboard');
        $caseCat = $this->createCategory('Case');

        $mb = $this->createProduct($mbCat, ['form_factor' => 'ATX']);
        $case = $this->createProduct($caseCat, ['form_factor' => 'mATX']);

        $result = $this->service->check([$mb->id, $case->id]);

        $this->assertCount(1, $result);
        $this->assertStringContainsString('Form factor mismatch', $result[0]);
    }

    public function test_cooler_tdp_less_than_cpu_tdp_returns_warning(): void
    {
        $cpuCat = $this->createCategory('CPU');
        $coolerCat = $this->createCategory('Cooler');

        $cpu = $this->createProduct($cpuCat, ['tdp' => 125]);
        $cooler = $this->createProduct($coolerCat, ['tdp' => 95]);

        $result = $this->service->check([$cpu->id, $cooler->id]);

        $this->assertCount(1, $result);
        $this->assertStringContainsString('Cooling warning', $result[0]);
        $this->assertStringContainsString('125', $result[0]);
        $this->assertStringContainsString('95', $result[0]);
    }

    public function test_cooler_tdp_equal_or_greater_no_warning(): void
    {
        $cpuCat = $this->createCategory('CPU');
        $coolerCat = $this->createCategory('Cooler');

        $cpu = $this->createProduct($cpuCat, ['tdp' => 125]);
        $cooler = $this->createProduct($coolerCat, ['tdp' => 125]);

        $result = $this->service->check([$cpu->id, $cooler->id]);

        $this->assertEmpty($result);
    }

    public function test_null_tdp_does_not_cause_warning(): void
    {
        $cpuCat = $this->createCategory('CPU');
        $coolerCat = $this->createCategory('Cooler');

        $cpu = $this->createProduct($cpuCat, ['tdp' => null]);
        $cooler = $this->createProduct($coolerCat, ['tdp' => 95]);

        $result = $this->service->check([$cpu->id, $cooler->id]);

        $this->assertEmpty($result);
    }

    public function test_psu_wattage_less_than_total_tdp_returns_warning(): void
    {
        $psuCat = $this->createCategory('PSU');
        $cpuCat = $this->createCategory('CPU');
        $gpuCat = $this->createCategory('GPU');

        $psu = $this->createProduct($psuCat, ['tdp' => 500]);
        $cpu = $this->createProduct($cpuCat, ['tdp' => 300]);
        $gpu = $this->createProduct($gpuCat, ['tdp' => 300]);

        $result = $this->service->check([$psu->id, $cpu->id, $gpu->id]);

        $this->assertCount(1, $result);
        $this->assertStringContainsString('Power warning', $result[0]);
        $this->assertStringContainsString('500', $result[0]);
        $this->assertStringContainsString('600', $result[0]);
    }

    public function test_psu_excluded_from_its_own_tdp_sum(): void
    {
        $psuCat = $this->createCategory('PSU');
        $cpuCat = $this->createCategory('CPU');

        $psu = $this->createProduct($psuCat, ['tdp' => 500]);
        $cpu = $this->createProduct($cpuCat, ['tdp' => 300]);

        $result = $this->service->check([$psu->id, $cpu->id]);

        $this->assertEmpty($result);
    }

    public function test_null_psu_wattage_no_warning(): void
    {
        $psuCat = $this->createCategory('PSU');
        $cpuCat = $this->createCategory('CPU');

        $psu = $this->createProduct($psuCat, ['tdp' => null]);
        $cpu = $this->createProduct($cpuCat, ['tdp' => 300]);

        $result = $this->service->check([$psu->id, $cpu->id]);

        $this->assertEmpty($result);
    }

    public function test_unknown_category_does_not_crash(): void
    {
        $unknownCat = $this->createCategory('Accessory');
        $product = $this->createProduct($unknownCat);

        $result = $this->service->check([$product->id]);

        $this->assertEmpty($result);
    }

    public function test_single_part_returns_no_warnings(): void
    {
        $cpuCat = $this->createCategory('CPU');
        $cpu = $this->createProduct($cpuCat);

        $result = $this->service->check([$cpu->id]);

        $this->assertEmpty($result);
    }

    public function test_multiple_rules_together_accumulate_all_warnings(): void
    {
        $cpuCat = $this->createCategory('CPU');
        $mbCat = $this->createCategory('Motherboard');
        $coolerCat = $this->createCategory('Cooler');
        $psuCat = $this->createCategory('PSU');
        $caseCat = $this->createCategory('Case');

        $cpu = $this->createProduct($cpuCat, ['socket' => 'AM5', 'tdp' => 200]);
        $mb = $this->createProduct($mbCat, ['socket' => 'LGA1700', 'form_factor' => 'ATX']);
        $cooler = $this->createProduct($coolerCat, ['tdp' => 95]);
        $psu = $this->createProduct($psuCat, ['tdp' => 300]);
        $case = $this->createProduct($caseCat, ['form_factor' => 'mATX']);

        $result = $this->service->check([$cpu->id, $mb->id, $cooler->id, $psu->id, $case->id]);

        $this->assertCount(3, $result);
    }
}
