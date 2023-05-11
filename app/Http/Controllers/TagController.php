<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Tag;
use App\Models\User;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TagController extends Controller
{
    public function add(Request $request) 
    {  
        $search = $request->search;

        if( ! Str::of( $search )->trim()->isEmpty() )
        {
            $tags = Tag::select('id','name','status')->where( 'name', 'LIKE', $search.'%' )->orderBy('id', 'DESC')->paginate(4)->withQueryString();            
        }
        else
        {
            $tags = Tag::select('id','name','status')->orderBy('id', 'DESC')->paginate(20);
        }

        return view('admin.tag.add', ['tags' => $tags]);
    }

    public function store(Request $request) 
    {
        $name = $request->name;

        # check if the tags contain comma, space or hash

        if( Str::of($name)->contains([',', '#', ' ']) )
        {   
            $request->validate([
                'name' => 'required'
            ],[
                'name.required' => 'Enter tags',
            ]); 

            # split the tags using comma, space and hash
            $tagArray = Str::of($name)->split("/[\s,#]+/");

            $count = 0;
            
            foreach($tagArray as $word)
            {                 
                if( Str::of($word)->trim()->isNotEmpty() )          
                {
                    $count = $count + 1;    
                    $tags[$count]['name'] = Str::of($word)->trim();
                    $tags[$count]['status'] = $request->status;
                }                
            }

            $saved = $request->user()->tags()->createMany( $tags );               
        }
        else
        {
            $validated = $request->validate([
                'name' => 'required|max:20|unique:blog_tag',
            ],[
                'name.required' => 'Enter name',
                'name.max' => 'Max. 20 character',
                'unique' => 'Duplicate tag',
            ]); 

            $saved = $request->user()->tags()->create($validated);
        }          

        if( !$saved )
        {
            return redirect()->route('admin.tag.add')->with('error', 'Error try again');
        }
        else
        {
            return redirect()->route('admin.tag.add')->with('success', 'Tag saved');
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

    public function edit(Request $request)
    {
        $id = $request->id;

        $tag = Tag::find($id);

        return view('admin.tag.edit', ['tag' => $tag]);
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
            return redirect()->route('admin.tag.add')->with('success' , 'TAg updated');        
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
