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
        $home_six_stories = Post::orderBy('published_at', 'DESC')->limit(6)->get();

        return view('components.home.top-six-story',[
            'stories' => $home_six_stories,
            'category' => 'sports'
        ]);
    }
}
