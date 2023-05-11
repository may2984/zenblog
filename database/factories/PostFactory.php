<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Post;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string=> '', mixed>
     */
    public function definition()
    {
        $title = fake()->unique()->sentence();

        return [
            'user_id' => User::factory(),
            'title'=> $title,
            'slug'=> fake()->slug(),
            'summary'=> fake()->paragraph(2),
            'body'=> fake()->text(),
            'meta_title'=> fake()->paragraph(1),
            'published'=> fake()->randomElement(['1', '0']),
            'comments_allowed'=> fake()->randomElement(['1', '0']),
            'published_at' => fake()->dateTimeBetween('-3 week'),
        ];
    }
}
