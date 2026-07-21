<?php

namespace Tests\Feature;

use App\Models\Build;
use App\Models\BuildItem;
use App\Models\BuildSlot;
use App\Models\CaseSpec;
use App\Models\Category;
use App\Models\CpuSpec;
use App\Models\MotherboardSpec;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BuilderTest extends TestCase
{
    use DatabaseTransactions;

    /** Create a product together with its CTI spec row. */
    private function createPart(string $specsTable, array $specData, array $productData = [], ?string $categoryName = null): Product
    {
        $category = Category::factory()->create([
            'name' => $categoryName ?? ucfirst(str_replace('_specs', '', $specsTable)),
            'specs_table' => $specsTable,
        ]);

        $product = Product::factory()->create(array_merge(['category_id' => $category->id], $productData));

        $specModel = match ($specsTable) {
            'cpu_specs' => CpuSpec::class,
            'motherboard_specs' => MotherboardSpec::class,
            'case_specs' => CaseSpec::class,
            default => throw new \InvalidArgumentException("No spec mapping for $specsTable"),
        };
        $specModel::create(['product_id' => $product->id] + $specData);

        return $product;
    }

    private function createSlot(Category $category, int $min, int $max): BuildSlot
    {
        return BuildSlot::create(['category_id' => $category->id, 'min_qty' => $min, 'max_qty' => $max]);
    }

    // ── BLD-F-01: GET /builder 200, slot-driven categories ─────────────────

    public function test_builder_index_lists_only_categories_with_build_slots(): void
    {
        $cpu = Category::factory()->create(['name' => 'CPU', 'specs_table' => 'cpu_specs']);
        $gpu = Category::factory()->create(['name' => 'GPU', 'specs_table' => 'gpu_specs']);
        $accessory = Category::factory()->create(['name' => 'Mouse Pad XYZ']);

        $this->createSlot($cpu, 1, 1);
        $this->createSlot($gpu, 0, 2);

        $response = $this->get('/builder');

        $response->assertStatus(200);
        $response->assertSee($cpu->name);
        $response->assertSee($gpu->name);

        // Only slotted categories are offered as builder slots (the site layout
        // still renders every category in its navbar, hence the view-data check)
        $response->assertViewHas('builderCategories', fn ($categories) => $categories->pluck('id')->all() === [
            $cpu->id, $gpu->id,
        ]);
    }

    // ── BLD-F-02: GET /builder/parts/{category} ─────────────────────────────

    public function test_get_parts_returns_json_for_category(): void
    {
        $cat = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create([
            'category_id' => $cat->id,
            'name' => 'Intel i5',
            'brand' => 'Intel',
        ]);

        $response = $this->getJson("/builder/parts/{$cat->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'id' => $product->id,
            'name' => 'Intel i5',
            'brand' => 'Intel',
        ]);
    }

    public function test_get_parts_empty_category_returns_empty_array(): void
    {
        $cat = Category::factory()->create(['name' => 'CPU']);

        $response = $this->getJson("/builder/parts/{$cat->id}");

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_parts_page_renders_select_button_with_type_button(): void
    {
        $cat = Category::factory()->create([
            'name' => 'Motherboard',
            'specs_table' => 'motherboard_specs',
        ]);
        $this->createSlot($cat, 1, 1);

        $product = Product::factory()->create([
            'category_id' => $cat->id,
            'name' => 'B450M',
            'brand' => 'MSI',
        ]);
        MotherboardSpec::create([
            'product_id' => $product->id,
            'socket' => 'AM4',
            'supported_ram_type' => 'DDR4',
            'ram_slots' => 4,
            'max_ram_capacity_gb' => 64,
            'form_factor' => 'Micro-ATX',
        ]);

        $response = $this->get("/builder/parts/{$cat->id}");

        $response->assertStatus(200);
        $response->assertSee('Select');
        $response->assertSee('data-product-id="' . $product->id . '"', false);
        $response->assertSee('<button type="button" class="select-btn"', false);
    }

    // ── BLD-F-03: POST /builder/check-compatibility ─────────────────────────

    public function test_check_compatibility_returns_warnings_json(): void
    {
        $cpu = $this->createPart('cpu_specs', ['socket' => 'AM5']);

        $response = $this->postJson('/builder/check-compatibility', [
            'part_ids' => [$cpu->id],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['warnings']);
        $response->assertJson(['warnings' => []]);
    }

    public function test_check_compatibility_detects_socket_mismatch(): void
    {
        $cpu = $this->createPart('cpu_specs', ['socket' => 'AM5']);
        $mb = $this->createPart('motherboard_specs', [
            'socket' => 'LGA1700', 'supported_ram_type' => 'DDR5',
            'ram_slots' => 4, 'max_ram_capacity_gb' => 128, 'form_factor' => 'ATX',
        ]);

        $response = $this->postJson('/builder/check-compatibility', [
            'part_ids' => [$cpu->id, $mb->id],
        ]);

        $response->assertStatus(200);
        $warnings = $response->json('warnings');
        $this->assertCount(1, $warnings);
        $this->assertStringContainsString('AM5', $warnings[0]);
        $this->assertStringContainsString('LGA1700', $warnings[0]);
    }

    public function test_check_compatibility_compatible_pair_returns_no_warnings(): void
    {
        $cpu = $this->createPart('cpu_specs', ['socket' => 'AM5']);
        $mb = $this->createPart('motherboard_specs', [
            'socket' => 'AM5', 'supported_ram_type' => 'DDR5',
            'ram_slots' => 4, 'max_ram_capacity_gb' => 128, 'form_factor' => 'ATX',
        ]);

        $response = $this->postJson('/builder/check-compatibility', [
            'part_ids' => [$cpu->id, $mb->id],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['warnings' => []]);
    }

    public function test_check_compatibility_invalid_product_id_returns_422(): void
    {
        $response = $this->postJson('/builder/check-compatibility', [
            'part_ids' => [99999],
        ]);

        $response->assertStatus(422);
    }

    public function test_check_compatibility_non_array_returns_422(): void
    {
        $response = $this->postJson('/builder/check-compatibility', [
            'part_ids' => 'not-an-array',
        ]);

        $response->assertStatus(422);
    }

    // ── BLD-F-04: POST /builder/save ────────────────────────────────────────

    public function test_store_valid_build_creates_rows(): void
    {
        $user = User::factory()->user()->create();
        $cpu = $this->createPart('cpu_specs', ['socket' => 'AM5']);

        $response = $this->actingAs($user)->postJson('/builder/save', [
            'name' => 'My Build',
            'part_ids' => [$cpu->id],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['build_id', 'warnings']);

        $this->assertDatabaseHas('pc_builds', [
            'user_id' => $user->id,
            'name' => 'My Build',
        ]);

        $this->assertDatabaseHas('build_items', [
            'product_id' => $cpu->id,
            'quantity' => 1,
        ]);
    }

    public function test_store_duplicate_part_ids_become_item_quantity(): void
    {
        $user = User::factory()->user()->create();
        $cpu = $this->createPart('cpu_specs', ['socket' => 'AM5']);

        $response = $this->actingAs($user)->postJson('/builder/save', [
            'name' => 'Double RAM Build',
            'part_ids' => [$cpu->id, $cpu->id],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('build_items', [
            'product_id' => $cpu->id,
            'quantity' => 2,
        ]);
    }

    public function test_store_response_reports_compatibility_warnings(): void
    {
        $user = User::factory()->user()->create();
        $cpu = $this->createPart('cpu_specs', ['socket' => 'AM5']);
        $mb = $this->createPart('motherboard_specs', [
            'socket' => 'LGA1700', 'supported_ram_type' => 'DDR5',
            'ram_slots' => 4, 'max_ram_capacity_gb' => 128, 'form_factor' => 'ATX',
        ]);

        $response = $this->actingAs($user)->postJson('/builder/save', [
            'name' => 'Warning Build',
            'part_ids' => [$cpu->id, $mb->id],
        ]);

        // Warnings are non-blocking: the build still saves successfully.
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertNotEmpty($response->json('warnings'));

        $this->assertDatabaseHas('pc_builds', ['name' => 'Warning Build']);
    }

    // ── BLD-F-05: Save validation ───────────────────────────────────────────

    public function test_store_no_name_returns_422(): void
    {
        $user = User::factory()->user()->create();
        $cpu = $this->createPart('cpu_specs', ['socket' => 'AM5']);

        $response = $this->actingAs($user)->postJson('/builder/save', [
            'name' => '',
            'part_ids' => [$cpu->id],
        ]);

        $response->assertStatus(422);
    }

    public function test_store_empty_part_ids_returns_422(): void
    {
        $user = User::factory()->user()->create();

        $response = $this->actingAs($user)->postJson('/builder/save', [
            'name' => 'My Build',
            'part_ids' => [],
        ]);

        $response->assertStatus(422);
    }

    public function test_store_nonexistent_part_id_returns_422(): void
    {
        $user = User::factory()->user()->create();

        $response = $this->actingAs($user)->postJson('/builder/save', [
            'name' => 'My Build',
            'part_ids' => [99999],
        ]);

        $response->assertStatus(422);
    }

    public function test_store_guest_redirected(): void
    {
        $response = $this->post('/builder/save', [
            'name' => 'My Build',
            'part_ids' => [1],
        ]);

        $response->assertRedirectToRoute('login');
    }

    // ── BLD-F-07: GET /builder/my-builds ────────────────────────────────────

    public function test_my_builds_shows_only_own_builds(): void
    {
        $user = User::factory()->user()->create();
        $other = User::factory()->user()->create();

        Build::factory()->create(['user_id' => $user->id, 'name' => 'My Build']);
        Build::factory()->create(['user_id' => $other->id, 'name' => 'Their Build']);

        $response = $this->actingAs($user)->get('/builder/my-builds');

        $response->assertStatus(200);
        $response->assertSee('My Build');
        $response->assertDontSee('Their Build');
    }

    // ── BLD-F-08: DELETE /builder/{build} ────────────────────────────────────

    public function test_destroy_owner_soft_deletes(): void
    {
        $user = User::factory()->user()->create();
        $build = Build::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/builder/{$build->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertSoftDeleted('pc_builds', ['id' => $build->id]);
    }

    public function test_destroy_other_user_returns_403(): void
    {
        $owner = User::factory()->user()->create();
        $other = User::factory()->user()->create();
        $build = Build::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->deleteJson("/builder/{$build->id}");

        $response->assertStatus(403);
    }

    public function test_destroy_guest_redirected(): void
    {
        $build = Build::factory()->create();
        $response = $this->delete("/builder/{$build->id}");
        $response->assertRedirectToRoute('login');
    }

    // ── BLD-F-09: Deleting product cascades build_items ─────────────────────

    public function test_deleting_product_cascades_build_items(): void
    {
        $product = Product::factory()->create();
        $build = Build::factory()->create();
        BuildItem::factory()->create([
            'build_id' => $build->id,
            'product_id' => $product->id,
        ]);

        $product->forceDelete();

        $this->assertDatabaseMissing('build_items', ['product_id' => $product->id]);
    }
}
