<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use App\Models\BlogCategory;

class PostBlogCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'post_id' => Post::factory(),
            'category_id' => BlogCategory::factory(),
            'is_main_category' => fake()->randomElement(['0','1']),
        ];
    }
}
