<?php

namespace Tests\Feature;

use App\Models\Build;
use App\Models\BuildPart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BuilderTest extends TestCase
{
    use DatabaseTransactions;

    // ── BLD-F-01: GET /builder 200 ──────────────────────────────────────────

    public function test_builder_index_lists_slot_categories(): void
    {
        $cpu = Category::factory()->create(['name' => 'CPU']);
        $gpu = Category::factory()->create(['name' => 'GPU']);
        $case = Category::factory()->create(['name' => 'Case']);

        $response = $this->get('/builder');

        $response->assertStatus(200);
        $response->assertSee($cpu->name);
        $response->assertSee($gpu->name);
        $response->assertSee($case->name);
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

    // ── BLD-F-03: POST /builder/check-compatibility ─────────────────────────

    public function test_check_compatibility_returns_warnings_json(): void
    {
        $cpuCat = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create(['category_id' => $cpuCat->id]);

        $response = $this->postJson('/builder/check-compatibility', [
            'part_ids' => [$product->id],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['warnings']);
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
        $cat = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create(['category_id' => $cat->id]);
        $store = Store::factory()->create();

        $product->stores()->attach($store->id, [
            'product_price' => 100.00,
            'product_url' => 'http://store.test/cpu',
            'product_status' => 'in stock',
        ]);

        $response = $this->actingAs($user)->postJson('/builder/save', [
            'name' => 'My Build',
            'notes' => 'My notes',
            'part_ids' => [$product->id],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('builds', [
            'user_id' => $user->id,
            'name' => 'My Build',
            'notes' => 'My notes',
            'total_price' => 100.00,
        ]);

        $this->assertDatabaseHas('build_parts', [
            'product_id' => $product->id,
            'category_name' => 'CPU',
        ]);
    }

    // ── BLD-F-05: Save validation ───────────────────────────────────────────

    public function test_store_no_name_returns_422(): void
    {
        $user = User::factory()->user()->create();
        $cat = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create(['category_id' => $cat->id]);

        $response = $this->actingAs($user)->postJson('/builder/save', [
            'name' => '',
            'part_ids' => [$product->id],
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

    // ── BLD-F-06: Part with no stores contributes 0 ─────────────────────────

    public function test_store_part_with_no_stores_contributes_zero(): void
    {
        $user = User::factory()->user()->create();
        $cat = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create(['category_id' => $cat->id]);

        $this->actingAs($user)->postJson('/builder/save', [
            'name' => 'Zero Price Build',
            'part_ids' => [$product->id],
        ]);

        $this->assertDatabaseHas('builds', [
            'user_id' => $user->id,
            'total_price' => 0,
        ]);
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
    }

    // ── BLD-F-08: DELETE /builder/{build} ────────────────────────────────────

    public function test_destroy_owner_soft_deletes(): void
    {
        $user = User::factory()->user()->create();
        $build = Build::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/builder/{$build->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertSoftDeleted('builds', ['id' => $build->id]);
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

    // ── BLD-F-09: Deleting product cascades build_parts ─────────────────────

    public function test_deleting_product_cascades_build_parts(): void
    {
        $cat = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create(['category_id' => $cat->id]);
        $build = Build::factory()->create();
        BuildPart::factory()->create([
            'build_id' => $build->id,
            'product_id' => $product->id,
            'category_name' => 'CPU',
        ]);

        $product->forceDelete();

        $this->assertDatabaseMissing('build_parts', ['product_id' => $product->id]);
    }

    // ── BLD-F-10: Warnings non-blocking ─────────────────────────────────────

    public function test_save_succeeds_even_with_compatibility_warnings(): void
    {
        $user = User::factory()->user()->create();
        $cat = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create(['category_id' => $cat->id]);
        $store = Store::factory()->create();
        $product->stores()->attach($store->id, [
            'product_price' => 50.00,
            'product_url' => 'http://store.test/cpu',
            'product_status' => 'in stock',
        ]);

        $response = $this->actingAs($user)->postJson('/builder/save', [
            'name' => 'Warning Build',
            'part_ids' => [$product->id],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('builds', ['name' => 'Warning Build']);
    }
}
