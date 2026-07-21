<?php

namespace Tests\Feature;

use App\Models\Build;
use App\Models\BuildItem;
use App\Models\BuildSlot;
use App\Models\Category;
use App\Models\CpuSpec;
use App\Models\MotherboardSpec;
use App\Models\Product;
use App\Models\RamSpec;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Feature tests for the build item management API (PcBuildController),
 * covering slot enforcement, ownership authorization, and the
 * compatibility feedback returned after every mutation.
 */
class PcBuildControllerTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private Build $build;
    private Category $cpuCategory;
    private Category $ramCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->user()->create();
        $this->build = Build::factory()->create(['user_id' => $this->user->id]);

        $this->cpuCategory = Category::factory()->create(['name' => 'CPU', 'specs_table' => 'cpu_specs']);
        $this->ramCategory = Category::factory()->create(['name' => 'Memory', 'specs_table' => 'ram_specs']);

        BuildSlot::create(['category_id' => $this->cpuCategory->id, 'min_qty' => 1, 'max_qty' => 1]);
        BuildSlot::create(['category_id' => $this->ramCategory->id, 'min_qty' => 1, 'max_qty' => 4]);
    }

    private function createCpu(string $socket): Product
    {
        $product = Product::factory()->create(['category_id' => $this->cpuCategory->id]);
        CpuSpec::create(['product_id' => $product->id, 'socket' => $socket]);

        return $product;
    }

    private function createRam(string $type = 'DDR5', int $capacityGb = 16): Product
    {
        $product = Product::factory()->create(['category_id' => $this->ramCategory->id]);
        RamSpec::create(['product_id' => $product->id, 'type' => $type, 'capacity_gb' => $capacityGb]);

        return $product;
    }

    private function createMotherboard(string $socket): Product
    {
        $category = Category::factory()->create(['name' => 'Motherboard', 'specs_table' => 'motherboard_specs']);
        BuildSlot::create(['category_id' => $category->id, 'min_qty' => 1, 'max_qty' => 1]);

        $product = Product::factory()->create(['category_id' => $category->id]);
        MotherboardSpec::create([
            'product_id' => $product->id, 'socket' => $socket, 'supported_ram_type' => 'DDR5',
            'ram_slots' => 4, 'max_ram_capacity_gb' => 128, 'form_factor' => 'ATX',
        ]);

        return $product;
    }

    // ── Add item ────────────────────────────────────────────────────────────

    public function test_owner_can_add_item_to_build(): void
    {
        $cpu = $this->createCpu('AM5');

        $response = $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", [
            'product_id' => $cpu->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(['is_compatible' => true, 'violations' => []]);

        $this->assertDatabaseHas('build_items', [
            'build_id' => $this->build->id,
            'product_id' => $cpu->id,
            'quantity' => 1,
        ]);
    }

    public function test_adding_same_product_twice_increments_quantity(): void
    {
        $ram = $this->createRam();

        $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", ['product_id' => $ram->id]);
        $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", ['product_id' => $ram->id]);

        $this->assertDatabaseHas('build_items', [
            'build_id' => $this->build->id,
            'product_id' => $ram->id,
            'quantity' => 2,
        ]);
    }

    public function test_adding_incompatible_part_returns_violations_but_still_adds(): void
    {
        $mb = $this->createMotherboard('LGA1700');
        $cpu = $this->createCpu('AM5');

        $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", ['product_id' => $mb->id]);

        $response = $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", [
            'product_id' => $cpu->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(['is_compatible' => false]);

        $violations = $response->json('violations');
        $this->assertCount(1, $violations);
        $this->assertSame('direct_match', $violations[0]['type']);
        $this->assertStringContainsString('AM5', $violations[0]['message']);

        $this->assertDatabaseHas('build_items', [
            'build_id' => $this->build->id,
            'product_id' => $cpu->id,
        ]);
    }

    public function test_slot_max_quantity_is_enforced_per_category(): void
    {
        $first = $this->createCpu('AM5');
        $second = $this->createCpu('AM5');

        $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", ['product_id' => $first->id]);

        // CPU slot is 1-1: a second CPU (any product of that category) is rejected
        $response = $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", [
            'product_id' => $second->id,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('build_items', ['product_id' => $second->id]);
    }

    public function test_adding_quantity_beyond_slot_max_is_rejected(): void
    {
        $ram = $this->createRam();

        $response = $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", [
            'product_id' => $ram->id,
            'quantity' => 5, // RAM slot allows at most 4
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('build_items', ['product_id' => $ram->id]);
    }

    public function test_adding_product_from_non_buildable_category_is_rejected(): void
    {
        $category = Category::factory()->create(['name' => 'Accessory']);
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'This product category is not part of the PC Builder.']);
    }

    public function test_add_item_validates_product_existence(): void
    {
        $response = $this->actingAs($this->user)->postJson("/builds/{$this->build->id}/items", [
            'product_id' => 99999,
        ]);

        $response->assertStatus(422);
    }

    // ── Update quantity ─────────────────────────────────────────────────────

    public function test_owner_can_update_item_quantity(): void
    {
        $ram = $this->createRam();
        BuildItem::create(['build_id' => $this->build->id, 'product_id' => $ram->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->patchJson("/builds/{$this->build->id}/items/{$ram->id}", [
            'quantity' => 3,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('build_items', [
            'build_id' => $this->build->id,
            'product_id' => $ram->id,
            'quantity' => 3,
        ]);
    }

    public function test_update_quantity_beyond_slot_max_is_rejected(): void
    {
        $ram = $this->createRam();
        BuildItem::create(['build_id' => $this->build->id, 'product_id' => $ram->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->patchJson("/builds/{$this->build->id}/items/{$ram->id}", [
            'quantity' => 5,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseHas('build_items', [
            'build_id' => $this->build->id,
            'product_id' => $ram->id,
            'quantity' => 1,
        ]);
    }

    public function test_update_quantity_for_item_not_in_build_returns_422(): void
    {
        $ram = $this->createRam();

        $response = $this->actingAs($this->user)->patchJson("/builds/{$this->build->id}/items/{$ram->id}", [
            'quantity' => 2,
        ]);

        $response->assertStatus(422);
    }

    // ── Remove item ─────────────────────────────────────────────────────────

    public function test_owner_can_remove_item_from_build(): void
    {
        $ram = $this->createRam();
        BuildItem::create(['build_id' => $this->build->id, 'product_id' => $ram->id, 'quantity' => 2]);

        $response = $this->actingAs($this->user)->deleteJson("/builds/{$this->build->id}/items/{$ram->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('build_items', [
            'build_id' => $this->build->id,
            'product_id' => $ram->id,
        ]);
    }

    // ── Show / compatibility ────────────────────────────────────────────────

    public function test_show_returns_build_items_and_compatibility(): void
    {
        $cpu = $this->createCpu('AM5');
        BuildItem::create(['build_id' => $this->build->id, 'product_id' => $cpu->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->getJson("/builds/{$this->build->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'items', 'compatibility' => ['is_compatible', 'violations']]);
        $response->assertJsonPath('items.0.product_id', $cpu->id);
        $response->assertJsonPath('compatibility.is_compatible', true);
    }

    public function test_compatibility_endpoint_reports_violations(): void
    {
        $mb = $this->createMotherboard('LGA1700');
        $cpu = $this->createCpu('AM5');
        BuildItem::create(['build_id' => $this->build->id, 'product_id' => $mb->id, 'quantity' => 1]);
        BuildItem::create(['build_id' => $this->build->id, 'product_id' => $cpu->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->getJson("/builds/{$this->build->id}/compatibility");

        $response->assertStatus(200);
        $response->assertJson(['is_compatible' => false]);
        $this->assertCount(1, $response->json('violations'));
    }

    // ── Authorization ───────────────────────────────────────────────────────

    public function test_other_user_cannot_manage_someone_elses_build(): void
    {
        $other = User::factory()->user()->create();
        $cpu = $this->createCpu('AM5');

        $this->actingAs($other)->getJson("/builds/{$this->build->id}")->assertStatus(403);
        $this->actingAs($other)->getJson("/builds/{$this->build->id}/compatibility")->assertStatus(403);
        $this->actingAs($other)->postJson("/builds/{$this->build->id}/items", ['product_id' => $cpu->id])->assertStatus(403);
        $this->actingAs($other)->patchJson("/builds/{$this->build->id}/items/{$cpu->id}", ['quantity' => 2])->assertStatus(403);
        $this->actingAs($other)->deleteJson("/builds/{$this->build->id}/items/{$cpu->id}")->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $cpu = $this->createCpu('AM5');

        $this->get("/builds/{$this->build->id}")->assertRedirectToRoute('login');
        $this->post("/builds/{$this->build->id}/items", ['product_id' => $cpu->id])->assertRedirectToRoute('login');
    }
}
