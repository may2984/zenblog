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
        $post = new Post; 
        $posts = Post::with('post_main_category:name,url')
                        ->select('id','title','slug','published_at')
                        ->published()
                        ->orderByDesc('published_at')
                        ->take(5)
                        ->get();

        $post->setAuthors = false;

        $data = [            
            'posts' => $post->setPost( $posts ),
        ];

        return view('components.home.footer.recent-posts', $data);
    }
}
