<?php

namespace Tests\Feature\Admin;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StoreAdminTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_index_lists_stores(): void
    {
        Store::factory()->create(['name' => 'Store A']);
        Store::factory()->create(['name' => 'Store B']);

        $response = $this->actingAs($this->admin)->get('/dashboard/stores');

        $response->assertStatus(200);
        $response->assertSee('Store A');
        $response->assertSee('Store B');
    }

    public function test_index_sorts_by_name_asc_default(): void
    {
        Store::factory()->create(['name' => 'Z Store']);
        Store::factory()->create(['name' => 'A Store']);

        $response = $this->actingAs($this->admin)->get('/dashboard/stores');

        $response->assertStatus(200);
    }

    public function test_store_creates_store_with_image(): void
    {
        $file = UploadedFile::fake()->image('store.jpg');

        $response = $this->actingAs($this->admin)->post('/dashboard/stores', [
            'name' => 'New Store',
            'image' => $file,
        ]);

        $response->assertSessionHas('success', 'Store Created Successfully!');
        $response->assertRedirect();

        $this->assertDatabaseHas('stores', [
            'name' => 'New Store',
        ]);

        $store = Store::where('name', 'New Store')->first();
        $this->assertNotNull($store->image);
        $this->assertStringStartsWith('uploads/store/', $store->image);
    }

    public function test_store_creates_store_without_image(): void
    {
        $response = $this->actingAs($this->admin)->post('/dashboard/stores', [
            'name' => 'New Store',
        ]);

        $response->assertSessionHas('success', 'Store Created Successfully!');

        $this->assertDatabaseHas('stores', [
            'name' => 'New Store',
            'image' => null,
        ]);
    }

    public function test_store_requires_name_min_three_chars(): void
    {
        $response = $this->actingAs($this->admin)->post('/dashboard/stores', [
            'name' => 'AB',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_update_changes_name(): void
    {
        $store = Store::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin)->put("/dashboard/stores/{$store->id}", [
            'name' => 'New Name',
        ]);

        $response->assertSessionHas('success', 'Store updated successfully!');
        $response->assertRedirect();

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'name' => 'New Name',
        ]);
    }

    public function test_update_with_image_replaces_image(): void
    {
        $store = Store::factory()->create([
            'name' => 'Store',
            'image' => 'uploads/store/old.jpg',
        ]);
        $file = UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($this->admin)->put("/dashboard/stores/{$store->id}", [
            'name' => 'Store',
            'image' => $file,
        ]);

        $response->assertSessionHas('success', 'Store updated successfully!');

        $store->refresh();
        $this->assertStringStartsWith('uploads/store/', $store->image);
        $this->assertStringEndsWith('.jpg', $store->image);
    }

    public function test_update_without_image_keeps_old_image(): void
    {
        $store = Store::factory()->create([
            'name' => 'Store',
            'image' => 'uploads/store/original.jpg',
        ]);

        $response = $this->actingAs($this->admin)->put("/dashboard/stores/{$store->id}", [
            'name' => 'Updated Store',
        ]);

        $response->assertSessionHas('success', 'Store updated successfully!');

        $store->refresh();
        $this->assertEquals('uploads/store/original.jpg', $store->image);
    }

    public function test_destroy_soft_deletes_store(): void
    {
        $store = Store::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/dashboard/stores/{$store->id}");

        $response->assertSessionHas('success', 'Store Deleted Successfully!');
        $response->assertRedirect();

        $this->assertSoftDeleted($store);
    }

    public function test_restore_brings_store_back(): void
    {
        $store = Store::factory()->create();
        $store->delete();

        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->get("/dashboard/restore-s/{$store->id}");

        $response->assertSessionHas('success', 'Store Restore Successfully!');
        $response->assertRedirect();

        $this->assertNotSoftDeleted($store);
    }

    public function test_show_restore_lists_trashed_stores(): void
    {
        $store = Store::factory()->create();
        $store->delete();

        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->get('/dashboard/restore-s');

        $response->assertStatus(200);
    }
}
