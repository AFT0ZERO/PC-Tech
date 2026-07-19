<?php

namespace Database\Factories;

use App\Models\Build;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildFactory extends Factory
{
    protected $model = Build::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'total_price' => fake()->randomFloat(2, 100, 5000),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
