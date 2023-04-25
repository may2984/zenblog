<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string=> '', mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title'=> fake()->unique()->sentence(),
            'slug'=> fake()->paragraph(2),
            'summary'=> fake()->paragraph(2),
            'body'=> fake()->text(),
            'meta_title'=> fake()->paragraph(1),
            'published'=> fake()->randomElement(['1', '0']),
            'comments_allowed'=> fake()->randomElement(['1', '0']),
            'published_at' => fake()->dateTimeBetween('-3 week'),
        ];
    }
}
