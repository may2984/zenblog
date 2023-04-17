<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class BlogMainStory extends Component
{
    public function __construct()
    {

    }

    public function render()
    {
        $post = Post::select('id', 'title', 'slug', 'summary', 'published_at')->published()->orderBy('id', 'DESC')->first();
 
        $category = Post::getPostCategories( $post );
        $author = Post::getPostAuthors( $post );

        $data = [
            'post' => $post,
            'category' => Str::lower( $category ),
            'author' => $author
        ];  

        return view('components.home.blog-main-story', $data);
    }
}
