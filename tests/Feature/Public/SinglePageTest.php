<?php

namespace Tests\Feature\Public;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SinglePageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_single_page_loads_with_product_data(): void
    {
        $category = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Intel i7-14700K',
            'brand' => 'Intel',
            'description' => json_encode(['cores' => '20', 'threads' => '28']),
        ]);
        ProductImage::factory()->forProduct($product)->create();

        $response = $this->get("/single-page/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('Intel i7-14700K');
    }

    public function test_single_page_with_price_history(): void
    {
        $store = Store::factory()->create(['name' => 'Test Store']);
        $category = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'AMD Ryzen 5',
        ]);
        ProductImage::factory()->forProduct($product)->create();

        $product->stores()->attach($store->id, [
            'product_price' => 199.99,
            'product_url' => 'http://store.test/ryzen',
            'product_status' => 'in stock',
        ]);

        $storeProduct = $product->stores()->first()->pivot;

        \App\Models\PriceHistory::factory()->ok()->create([
            'sp_id' => $storeProduct->id,
            'price' => 199.99,
            'currency' => 'JOD',
        ]);

        \App\Models\PriceHistory::factory()->failed()->create([
            'sp_id' => $storeProduct->id,
            'price' => 0,
            'currency' => 'JOD',
        ]);

        $response = $this->get("/single-page/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('AMD Ryzen 5');
    }

    public function test_product_with_no_price_history_still_renders(): void
    {
        $category = Category::factory()->create(['name' => 'CPU']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Product Without History',
        ]);
        ProductImage::factory()->forProduct($product)->create();

        $response = $this->get("/single-page/{$product->id}");

        $response->assertStatus(200);
    }
}
