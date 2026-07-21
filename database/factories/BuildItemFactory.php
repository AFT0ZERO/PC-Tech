<?php

namespace Database\Factories;

use App\Models\Build;
use App\Models\BuildItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildItemFactory extends Factory
{
    protected $model = BuildItem::class;

    public function definition(): array
    {
        return [
            'build_id' => Build::factory(),
            'product_id' => Product::factory(),
            'quantity' => 1,
        ];
    }
}
