<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;

class NewsTabs extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        $post = new Post();

        # get the top 'n' trending post based on view counts
        $trendingPosts = $post->setPost( $post->getTrendingPosts() );

        # get the lastest post
        $latestPost = Post::with('post_main_category:name,url', 'authors:first_name,last_name')
                            ->latest()
                            ->published()
                            ->take(6)
                            ->get();

        $latestPosts = $post->setPost( $latestPost );

        $popularPosts = $post->setPost( $latestPosts );

        return view('components.news-tabs',[
            'popularPosts' => $popularPosts,
            'trendingPosts' => $trendingPosts,
            'latestPosts' => $latestPosts
        ]);
    }
}
