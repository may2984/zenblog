<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # return response()->json(Author::select('id', 'first_name', 'last_name', 'url')->orderBy('id', 'DESC')->take(10)->get());
        return response()->json(Author::all());
    }

    public function create(Request $request)
    {
        /**
         * Using the routeIs method, you may determine if the incoming request has matched a named route:
         */
        if ( $request->routeIs('author.*') ) {
            return view('admin.author.create');
        }        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:50',   
            'last_name'=> 'max:50',
            'url' => 'required|max:100|unique:blog_author',
            'pen_name' => 'required|max:100|unique:blog_author'
        ],[
            'first_name.required' => 'Please enter first name',
            'first_name.max' => 'Maximum :max words',          
            'last_name.max' => 'Maximum :max words',          
            'url.required' => 'Please enter author URL',
            'url.max' => 'Maximum :max words',    
            'url.unique' => 'URL already exists',
            'pen_name.required' => 'Please enter a short name',
            'pen_name.max' => 'Maximum :max words',
            'pen_name.unique' => 'Short name already exists',                                        
        ]);

        $saved = $request->user()->authors()->create($validated);

        if( !$saved )
        {
            return response()->json([
                'type' => 'error', 
                'message' => 'Error! try again'
            ]);
        }
        else
        {
            return response()->json([
                'type' => 'success', 
                'message' => 'Author saved successfully',
                'author_name' => $request->first_name.' '.$request->last_name
            ]);          
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
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json(Author::select('id', 'first_name', 'last_name', 'pen_name', 'url')->find( $id ));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:100',            
            'url' => 'required|max:100|unique:blog_author,id,'.$id,
            'pen_name' => 'required|max:100|unique:blog_author,id,'.$id
        ],[
            'first_name.required' => 'Please enter first name',
            'first_name.max' => 'Maximum :max words',          
            'url.required' => 'Please enter author URL',
            'url.max' => 'Maximum :max words',    
            'url.unique' => 'URL already exists',
            'pen_name.required' => 'Please enter a short name',
            'pen_name.max' => 'Maximum :max words',
            'pen_name.unique' => 'Short name already exists',                                        
        ]);

        

        $author = Author::find( $id );
        $author->first_name = $request->first_name;
        $author->last_name = $request->last_name;
        $author->pen_name = $request->pen_name;
        $author->url = $request->url;

        $updated = $author->save();

        if( !$updated )
        {
            return response()->json([
                'type' => 'error', 
                'message' => 'Error! try again'
            ]);
        }
        else
        {
            return response()->json([
                'type' => 'success', 
                'message' => 'Author edited successfully', 
                'id' => $id, 
                'author_name' => $request->first_name.' '.$request->last_name
            ]);            
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = new Author;
        
        $author = Author::find( $id );

        $deleted = $author->delete();

        if( !$deleted )
        {
            return response()->json(['type' => 'error', 'message' => 'Error! try again']);
        }
        else
        {
            return response()->json(['type' => 'success', 'message' => 'Author deleted successfully']);
        }
    }
}
