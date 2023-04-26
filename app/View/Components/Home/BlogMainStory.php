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
        $post = Post::select('id', 'title', 'slug', 'summary', 'published_at','user_id')->published()->latest()->first();

        # dd( $post );

        if( $post )
        {
            $category = Post::postMainCategory( $post ); 
            
            # dd( DB::getQueryLog() );

            $author = Post::getPostAuthors( $post );
    
            $data = [
                'post' => $post,
                'category' => $category,
                'author' => $author
            ];  

            return view('components.home.blog-main-story', $data);
        }
        else
        {
            return view('blog.wip');
        }
    }
}
