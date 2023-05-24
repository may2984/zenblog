<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class BusinessSideSixStory extends Component
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

    public function render()
    {
        $posts = DB::table('blog_post AS post')
                                        ->selectRaw('post.id, post.title, DATE_FORMAT(post.published_at, "%b %D \'%y") AS published_at')
                                        ->join('blog_post_category AS category', function ($join) 
                                            {
                                                $join->on('post.id', '=', 'category.post_id')
                                                     ->where('category.category_id', '=', 3);                       
                                            })  
                                        ->where('post.published', '=', '1')    
                                        ->offset(4)
                                        ->limit(6)                  
                                        ->get();        
        
        return view('components.business-side-six-story',[
            'posts' => $posts
        ]);
    }
}
