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
        DB::enableQueryLog();
    }

    public function render()
    {
        /**
         * trending stories
         * find out stories based on view counts
         */
        $trending = DB::table('blog_post')
                                            ->selectRaw('COUNT(blog_post_views.id) as views, blog_post.id')
                                            ->join('blog_post_views', 'blog_post.id', '=', 'blog_post_views.post_id')  
                                            ->where('published', '1')                     
                                            ->groupBy('blog_post.id')   
                                            ->orderByDesc('views')  
                                            ->take(5)                              
                                            ->get();
              
        if( $trending->count() ) 
        {
            $trendingPost = $trending->map( function( $trending ){
                
                $posts = Post::with('post_main_category:name,url', 'authors:id,first_name,last_name')->select('id', 'title', 'slug')->find( $trending->id );
                $posts->category = Post::postMainCategory( $posts );
                $posts->author = Post::getPostAuthors( $posts );

                return $posts;
            });
        
            return view('components.home.trending-story',[
                'posts' => $trendingPost
            ]);
        }
        else
        {
            return view('blog.wip');
        }
    }
}