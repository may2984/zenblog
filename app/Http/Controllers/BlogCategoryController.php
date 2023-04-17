<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function add() 
    {        
        $blogCategory = BlogCategory::select('id','url','name','status','position')->orderBy('position')->get();
        return view('admin.blog.category.add', compact('blogCategory'));
    }

    public function sort(Request $request)
    {
        $sortedIds = json_decode($request->sorted_ids); 
        foreach( $sortedIds as $category_id => $category_position)
        {         
            DB::table('blog_category')->where('id', $category_id)->update(['position' => $category_position]);
        }  
        
        echo 'Position Saved';
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
            'name' => 'required|unique:blog_category|max:100',
            'url' => 'required|unique:blog_category|max:100',
            'description' => 'required|max:200',
            ],
            [
                'name.required' => 'Please enter a name',
                'name.unique' => 'Duplicate category',
                'url.required' => 'Please enter a url',
                'url.unique' => 'Duplicate URL',
                'description.required' =>  'Please write some description',
                'description.max' => 'Description can be max 200 characters',
            ]
        );

        $BlogCategory = new BlogCategory;

        $BlogCategory->name = $request->name;
        $BlogCategory->url = $request->url != '' ? Str::kebab( $request->url ) : Str::kebab($request->name);
        $BlogCategory->position = $this->get_max_position();
        $BlogCategory->description = $request->description;
        $BlogCategory->status = $request->status;

        $stored = $BlogCategory->save();        

        if( !$stored )
        {
            return back()->with('error' , 'Error! Try again');
        }
        else
        {
            return back()->with('success' , 'Category saved');
        }
    }

    protected function get_max_position()
    {
        return DB::table('blog_category')->max('position') + 1;
    }

    public function edit(Request $request) 
    {     
        $id = $request->id;

        $blogCategory = BlogCategory::find($id);

        if( !is_null( $blogCategory ) )
        {
            return view('admin.blog.category.edit', compact('blogCategory'));    
        }
        else
        {
            return back()->with('error' , 'Error! Try again');
        }        
    }

    public function update(Request $request)
    {
        $id = $request->id;
       
        $request->validate(
            [
                'name' => 'required|unique:blog_category,id,'.$id.'|max:100',
                'description' => 'required|max:200',
            ],
            [
                'name.required' => 'Please enter a name',
                'name.unique' => 'Duplicate category',
                'description.required' =>  'Please write some description',
            ]
        );

        $BlogCategory = BlogCategory::find($id);
       
        $BlogCategory->name = $request->name;
        $BlogCategory->url = Str::kebab($request->url);
        $BlogCategory->description = $request->description;
        $BlogCategory->status = $request->status;

        $updated = $BlogCategory->save();

        if( !$updated )
        {
            return back()->with('error', 'Error! Try again');
        }
        else
        {
            return redirect()->route('admin.blog.category.add')->with('success' , 'Category updated');
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        
        $deleted = BlogCategory::where('id', '=', $id)->delete();
        
        if( !$deleted )
        {
            return back()->with('error', 'Error! Try again');
        }
        else
        {
            return back()->with('success' , 'Category deleted');
        }
    }
}
