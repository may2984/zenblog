<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TagFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->words(1, true),
            'user_id' => User::factory(),
        ];
    }
}
