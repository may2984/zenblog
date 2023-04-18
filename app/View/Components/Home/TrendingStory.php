<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class TrendingStory extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        # trending stories
        $trending = DB::table('blog_post')
                    ->selectRaw('COUNT(blog_post_views.id) as views, blog_post.id')
                    ->leftJoin('blog_post_views', 'blog_post.id', '=', 'blog_post_views.post_id')                       
                    ->groupBy('blog_post.id')   
                    ->orderByDesc('views')  
                    ->take(5)                              
                    ->get()
                    ->map( function( $trending ){
                        return $trending->id;
                    });       

        $post_ids = $trending->all();        

        $posts = Post::select('id', 'title', 'slug')->whereIn( 'id' , $post_ids )->get();

        $posts->map(function( $posts ){
            $posts->category = Post::postMainCategory( $posts );
            $posts->author = Post::getPostAuthors( $posts );
            return $posts;
        }); 

        // git@github.com:may2984/zenblog.git        
       
        return view('components.home.trending-story',[
            'posts' => $posts
        ]);
    }
}
