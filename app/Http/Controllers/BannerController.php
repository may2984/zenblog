<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\BannerStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {   
        $postWithBanner = Post::with('post_main_category:url')
                        ->select('blog_post.id', 'blog_post.title', 'blog_post.slug', 'blog_post.summary','banners.id AS banner_id','banners.banner_image','banners.banner_heading','banners.banner_text','banners.status')
                        ->join('banners', 'banners.post_id', '=', 'blog_post.id') 
                        ->orderBy('banner_position')                       
                        ->get();
        
        $post = new Post();
        $post->setAuthors = false;
        $banners = $post->setPost($postWithBanner);

        return response()->json($banners);
    }

    public function create()
    {
        return view('admin.banner.create',[
            'posts' => Post::select('id','title')->published()->latest()->take(10)->get(),
        ]);        
    }

    public function store(BannerStore $request): RedirectResponse
    {
        if ($request->validated()) 
        { 
            $saved = (new Banner)::create([
                        'banner_image' => $request->get('banner_image'),
                        'post_id' => $request->get('post_id'),
                        'banner_heading' => $request->get('banner_heading'),
                        'banner_text' => $request->get('banner_text'),
                        'banner_position' => $this->get_max_position(),
                    ]);
            
            if($saved){
                return redirect()->back()->with('success', 'Banner Saved');
            }
            else{
                return redirect()->back()->with('error', 'Error! try again');                
            }
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $banner = Banner::select('id','post_id','banner_heading','banner_text')->find($id);

        return view('admin.banner.edit',[
            'posts' => Post::select('id','title')->published()->latest()->take(10)->get(),
            'banner' => $banner
        ]);
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::find($id);

        $updated = $banner->update([                                            
                    'post_id' => $request->get('post_id'),
                    'banner_heading' => $request->get('banner_heading'),
                    'banner_text' => $request->get('banner_text'),                    
                   ]);

        if($updated){
            return redirect(route('banner.create'))->with('success', 'Banner edited');
        }
        else{  
            return redirect()->back()->with('error', 'Error! try again'); 
        }
    }

    public function destroy($id)
    {
        try {
            $banner_image = DB::table('banners')->select('banner_image')->where('id', '=', $id)->get()[0]->banner_image;            
            Storage::disk('public')->delete($banner_image);
        } catch (\Throwable $th) {
            Log::debug('Error deleting File');
        }

        $deleted = DB::table('banners')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json([
                'success', 'Banner deleted'
            ]);
        }

        return response()->json([
            'error', 'Error! Try again'
        ]);
    }

    protected function get_max_position()
    {
        return DB::table('banners')->max('banner_position') + 1;
    }

    
    public function sort(Request $request)
    {
        $sortedIds = json_decode($request->sorted_ids); 
        foreach( $sortedIds as $id => $position)
        {         
            DB::table('banners')->where('id', $id)->update(['banner_position' => $position]);
        }  
        
        echo 'Position Saved';
    }

    
    public function upload(Request $request)
    {
        return $request->banner_image->store('banners', 'public');
    }

    public function toggleStatus($status, $id)
    {   
        $updated = Banner::where('id', $id)->update(['status' => $status]);
        
        if( !$updated ){
            return response()->json(['type' => 'error']);
        }

        return response()->json(['type' => 'success']);        
    }
}
