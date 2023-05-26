<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Services\TagService;

use App\Models\User;
use App\Models\Tag;

# use Symfony\Component\HttpKernel\Event\RequestEvent;

class TagController extends Controller
{
    public function add(Request $request) 
    {  
        $query = Tag::select('id','name','status')->withCount('posts')->orderBy('posts_count', 'DESC');

        $search = $request->search;
        # if the search strgin is not empty        
        if( !Str::of($search)->trim()->isEmpty() ){
            $query->where( 'name', 'LIKE', $search.'%' );            
        } 

        $tags =  $query->paginate()->withQueryString();

        return view('admin.tag.add', [
            'tags' => $tags,
            'search' => $search
        ]);
    }

    public function store(TagStore $request, TagService $service) 
    {
        $saved = $service->store( $request );

        if( !$saved )
        {
            return redirect()->route('tag.add')->with('error', 'Error try again');
        }
        else
        {
            return redirect()->route('tag.add')->with('success', 'Tag saved');
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        $deleted = Tag::where('id','=', $id)->delete();

        if( !$deleted )
        {
            return back()->with('error', 'Error! Try again');
        }
        else
        {
            return back()->with('success' , 'Tag deleted');
        }
    }

    public function edit(Tag $id)
    {
        return view('admin.tag.edit', ['tag' => $id]);
    }

    public function modify(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'name' => 'required|max:20|unique:blog_tag,id,'.$id.'',
        ],[
            'name.required' => 'Enter name',
            'name.max' => 'Max. 20 character',
            'unique' => 'Duplicate tag',
        ]);        
       
        $tag = Tag::find($id);

        $tag->name = $request->name;
        $tag->status = $request->status;

        $saved = $tag->save();

        if( !$saved )
        {
            return back()->with('error', 'Error! Try again');
        }
        else
        {
            return redirect()->route('tag.add')->with('success' , 'TAg updated');        
        }
    }

    public function getTags(Request $request)
    {
        $search = $request->term;
        
        $tags = Tag::select('id','name AS value')->where('name' , 'LIKE', $search.'%')->get();

        if( $tags->count() )
        {
            return response()->json($tags);
        }
        else
        {
            return response()->json('');
        }
    }    
}
