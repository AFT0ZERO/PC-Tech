<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class FaqsFactory extends Factory
{

    public function definition(): array
    {
        return [
           'question' => fake()->sentence(),
            'answer' => fake()->paragraph(),
        ];
    }
}
