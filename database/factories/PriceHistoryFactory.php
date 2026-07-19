<?php

namespace Database\Factories;

use App\Models\PriceHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceHistoryFactory extends Factory
{
    protected $model = PriceHistory::class;

    public function definition(): array
    {
        return [
            'sp_id' => 1,
            'price' => fake()->randomFloat(2, 10, 2000),
            'currency' => 'JOD',
            'scraped_at' => fake()->dateTimeThisMonth(),
            'status' => fake()->randomElement(['ok', 'failed']),
        ];
    }

    public function ok(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'ok']);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'failed']);
    }
}
