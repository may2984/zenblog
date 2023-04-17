<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class HomeController extends Controller
{
    public function index() 
    {
        return view('blog.index');
    }

    public function about() 
    {
        return view('blog.about');
    }

    public function contact() 
    {
        return view('blog.contact');
    }

    public function category()
    {
        return view('blog.category');
    }

    public function posts(Request $request, string $category,  string $slug, int $id) 
    {        
        $post_id = $request->id;    

        # get the post
        $post = Post::find( $post_id );                   

        $category = Post::getPostCategories( $post );
       
        if( ! is_null( $post ) )
        {
            # save page view
            if( DB::table('blog_post')->where('id', $post_id)->exists() )
            {
                DB::table('blog_post_views')->insert( ['post_id' => $post_id ] );
            }            

            $data = [
                'post' => $post,
                'category' => $category
            ];
    
            return view('blog.post', $data);

        }
        else
        {            
            return view('errors.404');
        }
    }
}
