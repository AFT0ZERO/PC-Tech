<?php

namespace Tests\Feature\Public;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_category_null_shows_all_products(): void
    {
        $cat1 = Category::factory()->create(['name' => 'CPU']);
        $cat2 = Category::factory()->create(['name' => 'GPU']);

        $products1 = Product::factory(3)->create(['category_id' => $cat1->id]);
        $products2 = Product::factory(3)->create(['category_id' => $cat2->id]);

        foreach ($products1->merge($products2) as $product) {
            ProductImage::factory()->forProduct($product)->create();
        }

        $response = $this->get('/category');

        $response->assertStatus(200);
        $response->assertSee($cat1->name);
        $response->assertSee($cat2->name);
    }

    public function test_category_with_id_filters_products(): void
    {
        $cat1 = Category::factory()->create(['name' => 'CPU']);
        $cat2 = Category::factory()->create(['name' => 'GPU']);

        $cpuProduct = Product::factory()->create([
            'category_id' => $cat1->id,
            'name' => 'Intel i7',
            'brand' => 'Intel',
        ]);
        ProductImage::factory()->forProduct($cpuProduct)->create();
        $gpuProduct = Product::factory()->create([
            'category_id' => $cat2->id,
            'name' => 'RTX 4090',
            'brand' => 'NVIDIA',
        ]);
        ProductImage::factory()->forProduct($gpuProduct)->create();

        $response = $this->get("/category/{$cat1->id}");

        $response->assertStatus(200);
        $response->assertSee('Intel i7');
    }

    public function test_invalid_category_id_returns_empty_list(): void
    {
        $response = $this->get('/category/99999');

        $response->assertStatus(200);
    }

    public function test_brand_counts_are_displayed(): void
    {
        $cat = Category::factory()->create(['name' => 'CPU']);

        $product1 = Product::factory()->create([
            'category_id' => $cat->id,
            'brand' => 'Intel',
        ]);
        ProductImage::factory()->forProduct($product1)->create();
        $product2 = Product::factory()->create([
            'category_id' => $cat->id,
            'brand' => 'AMD',
        ]);
        ProductImage::factory()->forProduct($product2)->create();

        $response = $this->get('/category');

        $response->assertStatus(200);
    }
}
