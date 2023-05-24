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
use Carbon\Carbon;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class PostContoller extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
    }

    public function index(Request $request)
    {
        $search = $request->search;
        
        if( Str::of( $search )->trim()->isNotEmpty() )
        {
            # eager loading the relations using 'with'
            $posts = Post::with('authors:first_name,last_name')
                                                                ->select('id', 'title', 'published')
                                                                ->where( 'title', 'LIKE', '%'.$search.'%' )->orderBy('id', 'DESC')
                                                                ->paginate(10)
                                                                ->withQueryString();            
        }
        else
        {
            # eager loading the relations using 'with'
            $posts = Post::with('authors:first_name,last_name')->select('id', 'title', 'published')->orderBy('id', 'DESC')->paginate(10);
        }        
        
        return view('admin.post.list', [
            'posts' => $posts,
            'search' => $search
        ]);
    }

    public function create()
    {        
        $blogCategories = BlogCategory::all();
        $blogAuthors = Author::all();
        $tags = Tag::all();

        return view('admin.post.create', [
            'blogCategories' => $blogCategories,
            'blogAuthors' => $blogAuthors,
            'tags' => $tags, 
            'current_date' => Carbon::now()->format('Y-m-d'),
            'current_time' => Carbon::now()->format('H:i')
        ]);
    }

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

        # dd($data);

        $post = $request->user()->posts()->create( $data );
      
        if( !$post )
        {
            return back()->with('error','Error! try again');
        }
        else
        {
            /* Many to many relation sync */

            # set the main category          
            $post->categories()->attach( array( head( $request->blog_category ) => ['is_main_category' => 1] ) );
            
            # set the secondary category
            $post->categories()->attach( collect( $request->blog_category )->slice(1) );

            # set the author
            $post->authors()->attach($request->blog_author);

            # set the tag
            $post->tags()->attach($request->blog_tag);            

            return back()->with('success','Post saved');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $post = Post::FindorFail($id);
            
        $blogTags = Tag::all();
        $blogPostTags = $post->tags->pluck('id');

        $blogPostAuthors = $post->authors->pluck('fullName','id');
        $blogAuthors = Author::all()->whereNotIn( 'id', $blogPostAuthors->keys() );

        $blogPostCategories = $post->categories->pluck('name','id');
        $blogCategories = BlogCategory::all()->whereNotIn('id', $blogPostCategories );

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
            # set the author
            $post->authors()->sync($request->blog_author);

            # set the main category
            $post->categories()->sync( array( head($request->blog_category) => array( 'is_main_category' => 1 ) ) );

            # set the rest of category
            $post->categories()->attach( collect($request->blog_category)->slice(1) );

            # set the tags
            $post->tags()->sync($request->blog_tag);

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
