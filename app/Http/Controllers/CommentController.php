<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStore;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CommentController extends Controller
{
    public function store(CommentStore $request)
    {
        if( $request->validated() )
        {
            $data = $request->validated();

            $data['post_id'] = (int) $request->post_id;
            $data['comment_id'] = (int) $request->comment_id;

            $saved = (new Comment())->create( $data );

            if( $saved )
            {
                return redirect()->back()->with('success', 'Thanks for commenting. Your comment will be live once moderated');
            }
            else
            {
                return redirect()->back()->with('error', 'Error! saving your comment. Try again later');
            }
        }    
    }
}
