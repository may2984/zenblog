<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use function Termwind\render;

class BlogMainStory extends Component
{
    public function __construct()
    {
        DB::enableQueryLog();
    }

    public function render()
    {
        $post = Post::with('post_main_category:name,url', 'authors:id,first_name,last_name')
                                                                                            ->select('id', 'title', 'slug', 'summary', 'published_at')
                                                                                            ->published()
                                                                                            ->latest()
                                                                                            ->first();

        if( $post )
        {
            $post_category = Post::postMainCategory( $post );             
            $post_author = Post::getPostAuthors( $post );
            $post_url = route('post.url', ['category' => $post_category, 'slug' => $post->slug, 'id' => $post->id ]);
    
            $data = [
                'post' => $post,
                'post_category' => $post_category,
                'post_author' => $post_author,
                'post_url' => $post_url
            ];  

            return view('components.home.blog-main-story', $data);
        }
        else
        {
            return view('blog.wip');
        }
    }
}
