<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{

    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'image' => 'uploads/ProductImage/' . fake()->uuid() . '.jpg',
        ];
    }

    public function forProduct(\App\Models\Product $product): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $product->id,
        ]);
    }
}
