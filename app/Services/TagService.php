<?php

namespace App\Services;

use App\Http\Requests\TagStore;
use Illuminate\Support\Str;

class TagService{

    public function store(TagStore $request)
    {
        $name = $request->name;

        if( Str::of($name)->contains([',', '#', ' ']) )
        { 
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
            if($count){
                return $request->user()->tags()->createMany($tags);               
            }
            return 0;
        }
        else
        {
            return $request->user()->tags()->create($request->validated());
        }         
    }    
}