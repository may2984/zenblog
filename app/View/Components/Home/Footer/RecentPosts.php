<?php

namespace App\View\Components\Home\Footer;

use Illuminate\View\Component;
use App\Models\Post;

class RecentPosts extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        $posts = Post::published()->orderByDesc('published_at')->take(5)->get();

        $posts->map(function( $posts ){
            $posts->category = Post::postMainCategory( $posts );            
            return $posts;
        });

        $data = [
            'posts' => $posts
        ];

        return view('components.home.footer.recent-posts', $data);
    }
}
