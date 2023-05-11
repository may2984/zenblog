<?php

namespace Database\Seeders;

use App\Models\PostBlogCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostBlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       PostBlogCategory::factory()->create();
    }
}
