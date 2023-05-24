<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class HomePageStoryBlock extends Component
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

        return view('components.home-page-story-block',[
            'first_posts' => $homePosts->first(),
            'posts' => $homePosts->slice(1, 4), # set the next 3 post
            'last_six_posts' => $homePosts->slice(4, 9), # set the last six post
            'category' => Str::of($this->categoryName)->lower(),
        ]);
    }
}
