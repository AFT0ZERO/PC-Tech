<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition(): array
    {
        return [
            'message' => fake()->sentence(),
            'rate' => fake()->numberBetween(1, 5),
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
        ];
    }
}
