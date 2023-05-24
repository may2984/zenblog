<?php

namespace App\View\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class SideStory extends Component
{
    public function __construct( public string $categoryName, )
    {
        //    
    }

    public function render()
    {   
        $posts = DB::table('blog_post AS post')
                                                ->selectRaw('post.id, post.title, post.slug, DATE_FORMAT(post.published_at, "%b %D \'%y") AS published_at, category.url')
                                                ->join('blog_post_category AS post_category', 'post.id', '=', 'post_category.post_id')
                                                ->join('blog_category AS category', function($join){
                                                    $join->on('category.id', '=', 'post_category.category_id')
                                                    ->where('category.name', '=', $this->categoryName);
                                                })
                                                ->where('post.published', '=', '1')
                                                ->offset(4)
                                                ->limit(6)
                                                ->get();
        
        return view('components.side-story',[
            'posts' => $posts,
            'category' => Str::of($this->categoryName)->lower()
        ]);
    }
}
