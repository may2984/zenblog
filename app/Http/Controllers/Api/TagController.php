<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tag;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->term;
        $tags = Tag::select('id', 'name')->where('name', 'LIKE', $search . '%')->get();
        if ($tags->count()) {
            return response()->json([
                'status' => 200,
                'tags' => $tags
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'tags' => ''
            ], 404);
        }
    }
}
