<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CategoryAdminTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_index_lists_categories(): void
    {
        Category::factory()->create(['name' => 'CPU']);
        Category::factory()->create(['name' => 'GPU']);

        $response = $this->actingAs($this->admin)->get('/dashboard/categories');

        $response->assertStatus(200);
        $response->assertSee('CPU');
        $response->assertSee('GPU');
    }

    public function test_store_creates_category_with_image(): void
    {
        $file = UploadedFile::fake()->image('category.jpg');

        $response = $this->actingAs($this->admin)->post('/dashboard/categories', [
            'name' => 'CPU',
            'image' => $file,
        ]);

        $response->assertSessionHas('success', 'Category Created Successfully!');
        $response->assertRedirect();

        $this->assertDatabaseHas('categories', [
            'name' => 'CPU',
        ]);

        $category = Category::where('name', 'CPU')->first();
        $this->assertNotNull($category->image);
        $this->assertStringStartsWith('uploads/category/', $category->image);
    }

    public function test_store_requires_name_min_three_chars(): void
    {
        $file = UploadedFile::fake()->image('cat.jpg');

        $response = $this->actingAs($this->admin)->post('/dashboard/categories', [
            'name' => 'AB',
            'image' => $file,
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_store_requires_image(): void
    {
        $response = $this->actingAs($this->admin)->post('/dashboard/categories', [
            'name' => 'CPU',
        ]);

        $response->assertSessionHasErrors(['image']);
    }

    public function test_update_changes_name(): void
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin)->put("/dashboard/categories/{$category->id}", [
            'name' => 'New Name',
        ]);

        $response->assertSessionHas('success', 'Category updated successfully!');
        $response->assertRedirect();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
        ]);
    }

    public function test_update_with_image_replaces_image(): void
    {
        $category = Category::factory()->create([
            'name' => 'CPU',
            'image' => 'uploads/category/old.jpg',
        ]);
        $file = UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($this->admin)->put("/dashboard/categories/{$category->id}", [
            'name' => 'CPU',
            'image' => $file,
        ]);

        $response->assertSessionHas('success', 'Category updated successfully!');

        $category->refresh();
        $this->assertStringStartsWith('uploads/category/', $category->image);
        $this->assertStringEndsWith('.jpg', $category->image);
    }

    public function test_update_without_image_keeps_old_image(): void
    {
        $category = Category::factory()->create([
            'name' => 'CPU',
            'image' => 'uploads/category/original.jpg',
        ]);

        $response = $this->actingAs($this->admin)->put("/dashboard/categories/{$category->id}", [
            'name' => 'Updated CPU',
        ]);

        $response->assertSessionHas('success', 'Category updated successfully!');

        $category->refresh();
        $this->assertEquals('uploads/category/original.jpg', $category->image);
    }

    public function test_destroy_soft_deletes_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/dashboard/categories/{$category->id}");

        $response->assertSessionHas('success', 'Category Deleted Successfully!');
        $response->assertRedirect();

        $this->assertSoftDeleted($category);
    }

    public function test_restore_brings_category_back(): void
    {
        $category = Category::factory()->create();
        $category->delete();

        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->get("/dashboard/restore-c/{$category->id}");

        $response->assertSessionHas('success', 'Category Restore Successfully!');
        $response->assertRedirect();

        $this->assertNotSoftDeleted($category);
    }

    public function test_show_restore_lists_trashed_categories(): void
    {
        $category = Category::factory()->create();
        $category->delete();

        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->get('/dashboard/restore-c');

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }
}
