<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ContactFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->firstname(),
            'user_id' => fake()->randomNumber([1,2,3,4]),
            'email' => fake()->email(),
            'mobile' => fake()->phoneNumber(),
            'message' => fake()->text(),
        ];
    }
}
