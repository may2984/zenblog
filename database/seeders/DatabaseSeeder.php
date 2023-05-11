<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PostBlogCategory;
use App\Models\Author;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            AuthorSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
          //  PostSeeder::class,
        ]);
    }
}
