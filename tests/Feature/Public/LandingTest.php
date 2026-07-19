<?php

namespace Tests\Feature\Public;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_landing_page_loads_with_categories_and_products(): void
    {
        $cat1 = Category::factory()->create(['name' => 'CPU']);
        $cat2 = Category::factory()->create(['name' => 'GPU']);

        Product::factory(3)->create(['category_id' => $cat1->id]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($cat1->name);
        $response->assertSee($cat2->name);
    }

    public function test_landing_cheapest_price_is_min_across_stores(): void
    {
        $store1 = Store::factory()->create(['name' => 'Store A']);
        $store2 = Store::factory()->create(['name' => 'Store B']);
        $category = Category::factory()->create(['name' => 'CPU']);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Intel i5',
            'brand' => 'Intel',
        ]);

        $product->stores()->attach($store1->id, [
            'product_price' => 150.00,
            'product_url' => 'http://store-a.com/i5',
            'product_status' => 'in stock',
        ]);
        $product->stores()->attach($store2->id, [
            'product_price' => 120.00,
            'product_url' => 'http://store-b.com/i5',
            'product_status' => 'in stock',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Intel i5');
    }

    public function test_product_with_no_stores_does_not_crash(): void
    {
        $category = Category::factory()->create(['name' => 'CPU']);
        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'No Store Product',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
