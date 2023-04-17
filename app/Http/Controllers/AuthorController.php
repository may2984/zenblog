<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # using query builder to combine first name and last name 
        return response()->json(
            DB::table('blog_author')->select('id', DB::raw('CONCAT_WS(" ", first_name, last_name) AS full_name'), 'url')
            ->where('deleted_at', NULL)
            ->orderBy('id', 'DESC')->get()
        );

        # return response()->json(Author::select('id','first_name','last_name', 'url')->orderBy('id', 'DESC')->take(10)->get());
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
        $request->validate([
            'first_name' => 'required|max:100',            
            'url' => 'required|max:100|unique:blog_author',
            'pen_name' => 'required|max:100|unique:blog_author'
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

        $author = new Author;
        $author->first_name = $request->first_name;
        $author->last_name = $request->last_name != '' ? $request->last_name : '';
        $author->pen_name = $request->pen_name;
        $author->url = $request->url;
        $author->created_by = session('user_id');

        $saved = $author->save();

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
        $request->validate([
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
