<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use PHPUnit\Framework\TestCase;

class ProductSpecMappingTest extends TestCase
{
    private function invokeDetectComponentType(ProductController $controller, array $data): string
    {
        $ref = new \ReflectionMethod($controller, 'detectComponentType');
        return $ref->invoke($controller, $data);
    }

    private function invokeMapSpecs(ProductController $controller, array $data): array
    {
        $ref = new \ReflectionMethod($controller, 'mapSpecs');
        return $ref->invoke($controller, $data);
    }

    public function test_detect_motherboard_socket_and_chipset(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'socket' => 'LGA1700',
            'chipset' => 'Z790',
        ]);
        $this->assertEquals('motherboard', $type);
    }

    public function test_detect_cpu_socket_only(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'socket' => 'AM5',
        ]);
        $this->assertEquals('cpu', $type);
    }

    public function test_detect_gpu_chipset_manufacturer(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'chipset_manufacturer' => 'NVIDIA',
        ]);
        $this->assertEquals('gpu', $type);
    }

    public function test_detect_ram_ram_type(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'ram_type' => 'DDR5',
        ]);
        $this->assertEquals('ram', $type);
    }

    public function test_detect_ram_memory_type_no_chipset(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'memory_type' => 'DDR4',
        ]);
        $this->assertEquals('ram', $type);
    }

    public function test_ram_with_chipset_is_not_ram(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'memory_type' => 'DDR5',
            'chipset' => 'Z790',
        ]);
        $this->assertNotEquals('ram', $type);
    }

    public function test_detect_storage_storage_type(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'storage_type' => 'SSD',
        ]);
        $this->assertEquals('storage', $type);
    }

    public function test_detect_storage_nvme(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'nvme' => true,
        ]);
        $this->assertEquals('storage', $type);
    }

    public function test_detect_psu_wattage(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'wattage' => 750,
        ]);
        $this->assertEquals('psu', $type);
    }

    public function test_detect_unknown(): void
    {
        $controller = new ProductController;
        $type = $this->invokeDetectComponentType($controller, [
            'color' => 'black',
        ]);
        $this->assertEquals('unknown', $type);
    }

    public function test_map_cpu_specs(): void
    {
        $controller = new ProductController;
        $data = [
            'metadata' => ['name' => 'Intel i7-14700K', 'manufacturer' => 'Intel'],
            'socket' => 'LGA1700',
            'cores' => ['total' => 20, 'threads' => 28],
            'clocks' => ['performance' => ['base' => 3.4, 'boost' => 5.6]],
            'specifications' => ['tdp' => 125],
        ];

        $specs = $this->invokeMapSpecs($controller, $data);

        $this->assertEquals('Intel', $specs['Manufacturer']);
        $this->assertEquals('LGA1700', $specs['Socket']);
        $this->assertEquals(20, $specs['Cores']);
        $this->assertEquals(28, $specs['Threads']);
        $this->assertStringContainsString('3.4', $specs['Base Clock']);
        $this->assertStringContainsString('5.6', $specs['Boost Clock']);
        $this->assertStringContainsString('125', $specs['TDP']);
    }

    public function test_map_cpu_specs_tdp_from_top_level(): void
    {
        $controller = new ProductController;
        $data = [
            'metadata' => ['name' => 'AMD Ryzen 5'],
            'socket' => 'AM5',
            'cores' => ['total' => 6, 'threads' => 12],
            'tdp' => 105,
        ];

        $specs = $this->invokeMapSpecs($controller, $data);

        $this->assertStringContainsString('105', $specs['TDP']);
    }

    public function test_map_ram_specs(): void
    {
        $controller = new ProductController;
        $data = [
            'metadata' => ['manufacturer' => 'Corsair', 'series' => 'Vengeance'],
            'form_factor' => 'DIMM',
            'ram_type' => 'DDR5',
            'capacity' => 32,
            'speed' => 6000,
        ];

        $specs = $this->invokeMapSpecs($controller, $data);

        $this->assertEquals('Corsair', $specs['Manufacturer']);
        $this->assertEquals('Vengeance', $specs['Series']);
        $this->assertEquals('DIMM', $specs['Form Factor']);
        $this->assertEquals('DDR5', $specs['Memory Type']);
        $this->assertStringContainsString('32', $specs['Capacity']);
        $this->assertStringContainsString('6000', $specs['Speed']);
    }

    public function test_map_gpu_specs(): void
    {
        $controller = new ProductController;
        $data = [
            'metadata' => ['manufacturer' => 'ASUS', 'series' => 'ROG Strix'],
            'chipset_manufacturer' => 'NVIDIA',
            'chipset' => 'RTX 4080',
            'memory' => 16,
            'memory_type' => 'GDDR6X',
            'core_base_clock' => 2205,
            'core_boost_clock' => 2550,
            'memory_bus' => 256,
            'tdp' => 320,
            'interface' => 'PCIe 4.0',
        ];

        $specs = $this->invokeMapSpecs($controller, $data);

        $this->assertEquals('ASUS', $specs['Manufacturer']);
        $this->assertEquals('RTX 4080', $specs['Chipset']);
        $this->assertStringContainsString('16', $specs['VRAM']);
        $this->assertStringContainsString('2205', $specs['Core Clock']);
        $this->assertStringContainsString('2550', $specs['Boost Clock']);
        $this->assertStringContainsString('256', $specs['Memory Bus']);
        $this->assertStringContainsString('320', $specs['TDP']);
        $this->assertEquals('PCIe 4.0', $specs['Interface']);
    }

    public function test_map_storage_specs(): void
    {
        $controller = new ProductController;
        $data = [
            'metadata' => ['manufacturer' => 'Samsung', 'series' => '990 Pro'],
            'form_factor' => 'M.2 2280',
            'storage_type' => 'SSD',
            'capacity' => 2000,
            'interface' => 'NVMe PCIe 4.0',
        ];

        $specs = $this->invokeMapSpecs($controller, $data);

        $this->assertEquals('Samsung', $specs['Manufacturer']);
        $this->assertEquals('M.2 2280', $specs['Form Factor']);
        $this->assertEquals('SSD', $specs['Type']);
        $this->assertStringContainsString('2000', $specs['Capacity']);
    }

    public function test_map_psu_specs(): void
    {
        $controller = new ProductController;
        $data = [
            'metadata' => ['manufacturer' => 'Corsair', 'series' => 'RMx'],
            'form_factor' => 'ATX',
            'wattage' => 850,
            'length' => 160,
            'efficiency_rating' => '80+ Gold',
            'modular' => 'Full',
        ];

        $specs = $this->invokeMapSpecs($controller, $data);

        $this->assertEquals('Corsair', $specs['Manufacturer']);
        $this->assertStringContainsString('850', $specs['Wattage']);
        $this->assertStringContainsString('160', $specs['Length']);
        $this->assertEquals('80+ Gold', $specs['Efficiency']);
        $this->assertEquals('Full', $specs['Modular']);
    }

    public function test_map_motherboard_specs(): void
    {
        $controller = new ProductController;
        $data = [
            'metadata' => ['manufacturer' => 'ASUS', 'series' => 'ROG Strix'],
            'socket' => 'LGA1700',
            'chipset' => 'Z790',
            'form_factor' => 'ATX',
            'memory' => ['slots' => 4, 'max' => 128, 'ram_type' => 'DDR5'],
            'pcie_slots' => [1, 2, 3],
        ];

        $specs = $this->invokeMapSpecs($controller, $data);

        $this->assertEquals('ASUS', $specs['Manufacturer']);
        $this->assertEquals('LGA1700', $specs['Socket']);
        $this->assertEquals('Z790', $specs['Chipset']);
        $this->assertEquals('ATX', $specs['Form Factor']);
        $this->assertEquals(4, $specs['RAM Slots']);
        $this->assertStringContainsString('128', $specs['Max Memory']);
        $this->assertEquals('DDR5', $specs['Memory Type']);
        $this->assertEquals(3, $specs['PCIe Slots']);
    }

    public function test_map_unknown_specs(): void
    {
        $controller = new ProductController;
        $data = [
            'metadata' => ['manufacturer' => 'Noctua', 'series' => 'NH-D15'],
            'form_factor' => 'Tower',
        ];

        $specs = $this->invokeMapSpecs($controller, $data);

        $this->assertEquals('Noctua', $specs['Manufacturer']);
        $this->assertEquals('NH-D15', $specs['Series']);
        $this->assertEquals('Tower', $specs['Form Factor']);
        $this->assertCount(3, $specs);
    }

    public function test_map_specs_filters_null_values(): void
    {
        $controller = new ProductController;
        $data = [
            'metadata' => [],
        ];

        $specs = $this->invokeMapSpecs($controller, $data);

        $this->assertEmpty($specs['Manufacturer'] ?? null);
        $this->assertEquals('unknown', $this->invokeDetectComponentType($controller, $data));
    }
}
