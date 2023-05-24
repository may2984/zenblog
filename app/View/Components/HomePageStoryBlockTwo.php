<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class HomePageStoryBlockTwo extends Component
{
    public function __construct( public string $categoryName )
    {
        //
    }

    public function render()
    {
        $post = new Post;

        $post->categoryName = $this->categoryName;        
        $posts = $post->getHomePageStoryByCategoryName();   
        $homePosts = $post->setPost( $posts ); 
        
        $data = [ 
            'first_six_posts' => $homePosts->slice(0, 6), # set the first six post
            'seventh_post' => $homePosts->slice(7, 1)->first(),
            'last_three_posts' => $homePosts->slice(7, 9), # set the next 3 post            
            'category' => Str::of($this->categoryName)->lower(),
        ];    

        return view('components.home-page-story-block-two', $data);
    }
}
