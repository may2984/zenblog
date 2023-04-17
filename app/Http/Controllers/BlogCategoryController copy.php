<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogCategoryController extends Controller
{
    public function add() {
        
        $blogCategory = BlogCategory::select('id','name','created_at')->where('status', 1)->get();

        return view('admin.blog.category', compact('blogCategory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
            'name' => 'required|unique:blog_category|max:100',
            'description' => 'required|max:200',
            ],
            [
                'name.required' => 'Please enter a name',
                'name.unique' => 'Duplicate category',
                'description.required' =>  'Please write some description',
            ]
        );

        $BlogCategory = new BlogCategory;

        $BlogCategory->name = $request->name;
        $BlogCategory->description = $request->description;
        $BlogCategory->status = $request->status;

        $id = $request->id;

        if( $id > 0 )
        {
            $created_at = $BlogCategory->created_at = \Carbon\Carbon::parse(now())->format('d-m-Y H:i:s');                    
            $stored = $BlogCategory->save();
            $created_at = \Carbon\Carbon::parse($created_at)->format('d-m-Y H:i');        
        }
        else
        {
            $BlogCategory = $BlogCategory::find($id);            
            
            $BlogCategory->name = $request->name;
            $BlogCategory->description = $request->description;
            $BlogCategory->status = $request->status;
            $BlogCategory->updated_at = \Carbon\Carbon::parse(now())->format('d-m-Y H:i:s');

            $stored = $BlogCategory->save();
        }        

        if( !$stored )
        {
            echo 'Error! Try again';
        }
        else
        {
           echo "<tr>
                <th scope='row'>".$BlogCategory->id."</th>
                <td>".$request->name."</td>                     
                <td>$</td>
                <td>".$created_at."</td>
                <td>
                    <a href='#'><i class='bi bi-pen'></i></a>
                    <a href='#'><i class='bi bi-trash'></i></a>                        
                </td>
                </tr>";
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;   
        
        $BlogCategory = new BlogCategory;

        $data = $BlogCategory::select('id','name','status','description')->find($id)->toJson();

        echo $data;
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        
        $deleted = DB::table('blog_category')->where('id', '=', $id)->delete();
        if( !$deleted )
        {
            echo '0';
        }
        else
        {
            echo $id;
        }
    }
}
