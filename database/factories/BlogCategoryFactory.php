<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

class BlogCategoryFactory extends Factory
{
    public function definition()
    {
        $name = fake()->words(1, true);

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'url' => Str::lower($name),
            'description' => fake()->text(),
            'position' => 1,
            'status' => '1',
        ];
    }
}
