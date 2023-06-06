<?php

namespace App\View\Components;

use App\Models\Banner;
use App\Models\Post;
use Illuminate\View\Component;

class HomeBanner extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        $postWithBanner = Post::with('post_main_category:url')
                        ->select('blog_post.id', 'blog_post.title', 'blog_post.slug', 'blog_post.summary','banners.banner_image','banners.banner_heading','banners.banner_text')
                        ->join('banners', 'banners.post_id', '=', 'blog_post.id')    
                        ->orderBy('banners.banner_position') 
                        ->where('banners.status','=', 1)                   
                        ->get();
        
        $post = new Post();
        $post->setAuthors = false;
        $banners = $post->setPost($postWithBanner);
        
        return view('components.home-banner',[
            'banners' => $banners,
        ]);
    }
}
