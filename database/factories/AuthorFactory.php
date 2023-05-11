<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $first_name = fake()->firstName();
        $last_name = fake()->lastName();

        return [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_id' => User::factory(),
            'pen_name' => $first_name.' '.$last_name,
            'url' => Str::lower( $first_name.'-'.$last_name ),
        ];
    }
}
