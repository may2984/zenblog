<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class HomePageStoryBlockFirst extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        $post = new Post();        
        
        $posts = Post::with('post_main_category:name,url', 'authors:first_name,last_name')
                                ->select('blog_post.id', 'blog_post.title', 'blog_post.slug', 'blog_post.summary', 'blog_post.published_at')
                                ->join('order_post', function( $join ){
                                    $join->on('blog_post.id', '=', 'order_post.post_id')
                                    ->where('order_post.category_id', '=', 0);
                                })
                                ->orderBy('order_post.position')
                                ->get(); 

        $homePosts = $post->setPost( $posts );             

        # get the top 'n' trending post based on view counts
        $trendingPost = $post->setPost( $post->getTrendingPosts() );

        $data = [
            'home_post' => $homePosts->first(), # set the first post
            'top_six_posts' => $homePosts->slice(0, 6), # set the next six post
            'trending_posts' => $trendingPost, # set the trending post
        ];

        return view('components.home-page-story-block-first', $data);
    }
}
