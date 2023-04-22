<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use App\Models\Contact;
use App\Http\Requests\StoreContactUsForm;

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

    public function contactUs(StoreContactUsForm $request)
    {
        if( $request->validated() )
        {
            $contact = new Contact();

            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
                'visitor_ip' => $request->ip(),
                'user_agent' => $request->header('user-agent')
            ];

            $stored = $contact->create( $data );

            if( !$stored )
            {
                return back()->with(['error' => 'There was an error submitting your request. Please try again later']);
            }
            else
            {
                return back()->with(['success' => 'Thanks for contacting us! We will be in touch with you shortly.']);
            }
        }
        
    }
}
