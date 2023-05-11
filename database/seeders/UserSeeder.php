<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;

class UserSeeder extends Seeder
{
    public function run()
    {
        /* User::factory()
                       ->has(Post::factory()->count(3))
                       ->count(2)->create(); */

        # User::factory()->hasPosts(5)->count(5)->create();

/*         User::factory()->hasPosts(3, [
                                        'published' => '1',
                                     ])
                                        ->count(5)
                                        ->create(); */
    }
}