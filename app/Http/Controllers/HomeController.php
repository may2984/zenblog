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
use App\Models\BlogCategory;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use App\Models\Tag;

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

    public function category(BlogCategory $blog_category)
    {
        $category_posts = Post::with('authors')
                            ->select('blog_post.id', 'blog_post.title', 'blog_post.slug', 'blog_post.summary', 'blog_post.published_at', 'category.url')
                            ->join('blog_post_category AS post_category', 'post_category.post_id', '=', 'blog_post.id')
                            ->join('blog_category AS category', function($join) use($blog_category){
                                $join->on('category.id', '=', 'post_category.category_id')
                                    ->where('category.url', '=', $blog_category->url);
                      })
                      ->latest('blog_post.created_at')
                      ->paginate(5);                 

        return view('blog.category', [
            'categoryPosts' => $category_posts,
            'categoryName' => $blog_category->name,           
        ]);
    }

    public function showTagPost(Tag $blog_tag)
    {
        dd($blog_tag);
    }

    public function post(Post $id)
    {        
        dd($id);
    }

    public function posts(BlogCategory $blogCategory, string $slug, Post $post, Request $request) 
    {                
        $post_id = $post->id;    
        $category = $blogCategory->url; 
        
        $comments = Comment::with('comments')
                            ->select('id','name','message','created_at')
                            ->where([
                                ['post_id', '=', $post_id],
                                ['comment_id', '=', 0]
                             ])
                            ->orderByDesc('created_at')
                            ->get();

        # insert the view count       
        try {
            DB::table('blog_post_views')->insert([
                'post_id' => $post_id,
                'visitor_ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            Post::find($post_id)->increment('view_count');

        } catch (\Throwable $th) {
            Log::error($th);
        } 

        $data = [
            'post' => $post,
            'comments' => $comments,
            'comment_count' => $comments->count(),
            'category' => $category,
        ];

        return view('blog.post', $data);      
       
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
