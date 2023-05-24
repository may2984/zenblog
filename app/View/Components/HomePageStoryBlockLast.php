<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class HomePageStoryBlockLast extends Component
{
    public string $categoryName;

    public function __construct( $categoryName )
    {
        $this->categoryName = $categoryName;
    }

    public function render()
    {
        $post = new Post();

        $post->categoryName = $this->categoryName;        
        $posts = $post->getHomePageStoryByCategoryName();

        $homePosts = $post->setPost( $posts ); 

        $data = [
            'first_post' => $homePosts->first(), # set the first post
            'next_two_posts' => $homePosts->slice(1, 2), # set the next two post
            'next_four_posts' => $homePosts->slice(3, 6), # set the next four post
            'last_six_posts' => $homePosts->slice(7, 6), # set the last six post
            'category' => Str::of($this->categoryName)->lower(),
        ];   

        return view('components.home-page-story-block-last', $data);
    }
}
