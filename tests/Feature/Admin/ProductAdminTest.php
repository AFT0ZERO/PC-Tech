<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductAdminTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_autocomplete_returns_json_for_query_parameter(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->getJson('/dashboard/products/autocomplete?query=intel&category_id=' . $category->id);

        $response->assertStatus(200);
        $response->assertJsonStructure(['enabled', 'results']);
    }

    public function test_store_creates_product_without_stores(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/dashboard/products', [
            'category' => $category->id,
            'name'     => 'Test Product',
            'brand'    => 'Test Brand',
            'key'      => ['Feature'],
            'value'    => ['Value'],
        ]);

        $response->assertSessionHas('success', 'Product stored successfully!');
        $response->assertRedirectToRoute('product.index');

        $this->assertDatabaseHas('products', [
            'category_id' => $category->id,
            'name'        => 'Test Product',
            'brand'       => 'Test Brand',
        ]);
    }

    public function test_store_creates_product_with_stores(): void
    {
        $category = Category::factory()->create();
        $store = \App\Models\Store::factory()->create();

        $response = $this->actingAs($this->admin)->post('/dashboard/products', [
            'category' => $category->id,
            'name'     => 'Product With Store',
            'brand'    => 'Brand',
            'key'      => ['Feature'],
            'value'    => ['Value'],
            'store_id' => [$store->id],
            'price'    => [199.99],
            'url'      => ['https://example.com'],
            'status'   => ['in stock'],
        ]);

        $response->assertSessionHas('success', 'Product stored successfully!');
        $response->assertRedirectToRoute('product.index');

        $product = \App\Models\Product::where('name', 'Product With Store')->first();
        $this->assertNotNull($product);
        $this->assertTrue($product->stores()->where('store_id', $store->id)->exists());
    }

    public function test_store_creates_motherboard_with_specs(): void
    {
        $category = Category::factory()->create([
            'name'        => 'Motherboard',
            'specs_table' => 'motherboard_specs',
        ]);

        $response = $this->actingAs($this->admin)->post('/dashboard/products', [
            'category'            => $category->id,
            'name'                => 'MSI PRO B760M-B DDR5',
            'brand'               => 'MSI',
            'socket'              => 'LGA 1700',
            'supported_ram_type'  => 'DDR5',
            'ram_slots'           => '2',
            'max_ram_capacity_gb' => '96',
            'form_factor'         => 'Micro ATX',
            'key'                 => ['Chipset'],
            'value'               => ['Intel B760'],
        ]);

        $response->assertSessionHas('success', 'Product stored successfully!');
        $response->assertRedirectToRoute('product.index');

        $product = \App\Models\Product::where('name', 'MSI PRO B760M-B DDR5')->first();
        $this->assertNotNull($product);
        $this->assertDatabaseHas('motherboard_specs', [
            'product_id'         => $product->id,
            'socket'             => 'LGA 1700',
            'supported_ram_type' => 'DDR5',
        ]);
    }

    public function test_store_with_empty_description_row_shows_validation_errors(): void
    {
        $category = Category::factory()->create([
            'name'        => 'Motherboard',
            'specs_table' => 'motherboard_specs',
        ]);

        $response = $this->actingAs($this->admin)
            ->from('/dashboard/products/create')
            ->followingRedirects()
            ->post('/dashboard/products', [
                'category' => $category->id,
                'name'     => 'MSI PRO B760M-B DDR5',
                'brand'    => 'MSI',
                'key'      => [''],
                'value'    => [''],
            ]);

        $response->assertSee('The key.0 field is required.');
        $response->assertSee('The value.0 field is required.');

        $this->assertDatabaseMissing('products', ['name' => 'MSI PRO B760M-B DDR5']);
    }
}
