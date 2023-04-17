<?php

namespace App\View\Components\Home\Footer;

use Illuminate\View\Component;
use App\Models\Post;

class RecentPosts extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $posts = Post::all()->take(5);

        $data = [
            'posts' => $posts,
            'category' => 'sports'
        ];

        return view('components.home.footer.recent-posts', $data);
    }
}
