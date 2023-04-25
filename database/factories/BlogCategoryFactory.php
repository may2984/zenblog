<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogCategory>
 */
class BlogCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'url' => fake()->url(),
            'description' => fake()->text(),
            'position' => fake()->randomElement(['1', '0']),
            'status' => fake()->randomElement(['1', '0']),
        ];
    }
}
