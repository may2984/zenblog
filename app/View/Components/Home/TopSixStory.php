<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;
use App\Models\Post;

class TopSixStory extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        $posts = Post::published()->orderByDesc('created_at')->offset(1)->limit(6)->get();
        
        $posts->map(function( $posts ){
            $posts->category = Post::postMainCategory( $posts );            
            return $posts;
        }); 

        return view('components.home.top-six-story',[
            'stories' => $posts,
        ]);
    }
}
