<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Author;
use App\Models\BlogCategory;
use App\Models\Tag;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Models\PostBlogCategory;

class PostSeeder extends Seeder
{
    public function run()
    {
      //  Post::factory()->count(1)->create();

     /*    Post::factory()
            ->count(1)
            ->for(User::factory()->state([
                'name' => 'Kalyan Kumar',
            ]))
            ->create(); */

        /* Post::factory()
        ->count(3)
        ->forUser([
            'name' => 'Mayank Singh Parmar',
            'email' => 'parmarmayank29@gmail.com'
        ])
        ->create(); */

      /*   User::factory()->count(2)->create()->each(function($user){

            Post::factory()->count(2)->create();

           
        }); */
/* 
        Post::factory()
                ->count(2)
                ->state(new Sequence(
                    ['published' => '1'],
                    ['published' => '0'],
                ))
                ->create(); 
 */

         User::factory()->count(2)->create()->each(function($user){
            $user->posts()->saveMany(
                Post::factory()->count(4)->create()->each(function($post){
                    $post->tags()->saveMany( Tag::factory()->count(3)->create([
                        'user_id' => $post->user->id,
                    ]) );
                    $post->authors()->saveMany( Author::factory()->count(2)->create([
                        'user_id' => $post->user->id                       
                    ]) );
                    $post->categories()->saveMany( BlogCategory::factory()
                  //  ->hasAttached( PostBlogCategory::factory(), ['is_main_category' => 1], 'categories')
                    ->count(3)->create([
                        'user_id' => $post->user->id,
                    ]) );
                })
            );
        }); 

        /* Post::factory()
      //  ->has( Author::factory()->count(Arr::random([1,2])) )
        //->has( BlogCategory::factory()->count(3), 'categories' )
        ->hasAttached( PostBlogCategory::factory(), ['is_main_category' => 1], 'categories')
      //  ->has( Tag::factory()->count(Arr::random([3,5])) )  
        ->count(10)          
        ->create(); */

    }
}
