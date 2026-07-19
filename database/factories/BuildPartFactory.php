<?php

namespace Database\Factories;

use App\Models\Build;
use App\Models\BuildPart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildPartFactory extends Factory
{
    protected $model = BuildPart::class;

    public function definition(): array
    {
        return [
            'build_id' => Build::factory(),
            'product_id' => Product::factory(),
            'category_name' => fake()->randomElement(['CPU', 'Motherboard', 'RAM', 'GPU', 'Storage', 'PSU', 'Cooler', 'Case']),
        ];
    }
}
