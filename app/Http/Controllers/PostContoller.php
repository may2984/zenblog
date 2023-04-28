<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\Tag;
use App\Models\Post;
use App\Models\PostBlogCategory;
use App\Models\BlogPostTag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use App\Models\Author;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\PostAuthor;

use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class PostContoller extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
        # mayank
    }

    public function index(Request $request)
    {
        $search = $request->search;
        
        if( Str::of( $search )->trim()->isNotEmpty() )
        {
            $posts = Post::select('id', 'title', 'published')->where( 'title', 'LIKE', '%'.$search.'%' )->orderBy('id', 'DESC')->paginate(10)->withQueryString();            
        }
        else
        {
            $posts = Post::select('id', 'title', 'published')->orderBy('id', 'DESC')->paginate(10);
        }        
        
        return view('admin.post.list', [
            'posts' => $posts,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $blogCategories = BlogCategory::all();
        $blogAuthors = Author::all();
        $tags = Tag::all();

        return view('admin.post.create', [
            'blogCategories' => $blogCategories,
            'blogAuthors' => $blogAuthors,
            'tags' => $tags 
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = [   
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'slug' => $request->slug,
            'summary' => $request->summary,
            'body' => $request->body,
            'meta_title' => $request->meta_title,
            'published' => $request->published == 'on' ? '1' : '0',
            'comments_allowed' => $request->comments_allowed == 'on' ? '1' : '0',
            'published_at' => $request->publish_date.' '.$request->publish_time
        ];

    //    dd( $request->input() );

        $Post = new Post();

        /**
         * insert data using query builder, it will return last insert id         * 
         * $post_id = DB::table('blog_post')->insertGetId( $data );
        */

        # To auto update the timestamps we have to use Eloquent ORM to save data, Query builder will not do this task

        $stored = Post::create( $data );
        $post_id = $stored->id;

        if( !$post_id )
        {
            return back()->with('error','Error! try again');
        }
        else
        {
            $blog_category_ids = $request->blog_category;
            
            $count = 1;

            foreach($blog_category_ids AS $blog_category_id)
            {
                $PostBlogCategory = new PostBlogCategory;

                $PostBlogCategory->post_id = $post_id;
                $PostBlogCategory->category_id = $blog_category_id;
                if( $count ){
                    $PostBlogCategory->is_main_category = 1;
                    $count = 0;
                }
                $PostBlogCategory->save();
            }

            $blog_tag_ids = $request->blog_tag;
            
            foreach($blog_tag_ids AS $blog_tag_id)
            {
                $BlogPostTag = new BlogPostTag;

                $BlogPostTag->post_id = $post_id;
                $BlogPostTag->tag_id = $blog_tag_id;
                $BlogPostTag->save();
            }

            $blog_author_ids = $request->blog_author;
            
            foreach($blog_author_ids AS $blog_author_id)
            {
                $BlogPostAuthor = new PostAuthor;

                $BlogPostAuthor->post_id = $post_id;
                $BlogPostAuthor->author_id = $blog_author_id;
                $BlogPostAuthor->save();
            }

            return back()->with('success','Post saved');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $post = Post::find($id);

        /**
         * Eager Loadin guser name
         * $post = Post::with('user:id,name')->find($id);
         * dd( $post->user->name );
         */

        # Fetch Author, Category and Tag
        $blogAuthors = Author::all();
        $blogCategories = BlogCategory::all();        
        $blogTags = Tag::all();

        # Get all the Author, Category and Tag saved for this post

        $blogPostCategories = $post->categories->map(function( $categories ){
            return $categories->id;
        });

        $blogPostTags = $post->tags->map(function( $tags ){
            return $tags->id;
        });

        $blogPostAuthors = $post->authors->map(function( $authors ){
            return $authors->id;
        });        

        return view('admin.post.edit',[
            'post' => $post,
            'blogAuthors' => $blogAuthors,
            'blogCategories' => $blogCategories,
            'blogPostCategories' => $blogPostCategories,
            'blogTags' => $blogTags,
            'blogPostTags' => $blogPostTags,
            'blogPostAuthors' => $blogPostAuthors                       
        ]);
    }

    public function update(UpdatePostRequest $request, $id)
    {        
        
        $request->validated();

        $post = Post::find( $id );

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->summary = $request->summary;
        $post->body = $request->body;
        $post->meta_title = $request->meta_title;
        $post->published = $request->published == 'on' ? '1' : '0';
        $post->comments_allowed = $request->comments_allowed == 'on' ? '1' : '0';
        $post->published_at = $request->publish_date.' '.$request->publish_time;      
     
        $updated = $post->save();

        if( !$updated )
        {
            return back()->with('error','Error! try again');
        }
        else
        {
            return redirect()->route('post.list')->with('success','Post edited');
        }
    }

    public function destroy($id)
    {
        $deleted = Post::destroy($id);

        if( !$deleted )
        {
            return redirect()->route('post.list')->with('error', 'Error! try again');
        }
        else
        {
            return redirect()->route('post.list')->with('success', 'Post deleted successfullly');
        }
    }

    public function upload(Request $request)
    {

        return response()->json($request->file('profile_photo'));

        if( !is_null( $request->file('profile_photo') ) ) 
        {

            $image_extension = $request->file('profile_photo')->extension();

            # new image name {profile_name}_time()
            $profile_name = 'dd'; //Str::kebab(session('name')).'-'.time();
            $new_image_name = $profile_name.'.'.$image_extension;    
            
            # store the image in local disk, it will return the file path
            $path = $request->file('profile_photo')->storeAs(env('ADMIN_PROFILE_PHOTO_DIR'), $new_image_name, 'admin'); 

            # update the image path in database
            $data['image'] = $path;

            return response()->json(['image' => $data['image']]);

            /*

            $new_image_name_thumb = $profile_name.'-thumb.'.$image_extension;

            $resize_image = Image::make( $request->file('profile_photo')->getRealPath() );
            $resize_image->resize(120, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path().env('ADMIN_PHOTO_PATH').env('ADMIN_PROFILE_PHOTO_DIR').'/'.$new_image_name_thumb);

            # delete old image from disk           
            $old_image_name = DB::table('users')->where('email', session('email'))->select('image')->first()->image;

            Storage::disk('admin')->delete( $old_image_name );         
            Storage::disk('admin')->delete( env('ADMIN_PROFILE_PHOTO_DIR').'/'.$this->get_thumb_image_name( $old_image_name ) ) ; 
            */
            
        } 
    }    

    public function togglePublish( Request $request, $id, $status )
    {        
        $updated = Post::find( $request->id )->update( [ 'published' => $request->status ] );

        if( !$updated ){
            return response()->json(['type' => 'error']);
        }

        return response()->json(['type' => 'success']);        
    }
}
