<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{

    public function definition(): array
    {
        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => fake()->name(),
            'smallDescription' => fake()->sentence(),
            'description' => json_encode(['spec' => 'value']),
            'brand' => fake()->company(),
        ];
    }
}
