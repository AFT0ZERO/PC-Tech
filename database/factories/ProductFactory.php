<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{

    public function definition(): array
    {
        return [
            'category_id' => rand(1, 2),
            'name' => fake()->name(),
        ];
    }
}
