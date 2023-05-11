<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestContoller extends Controller
{
    public function index()
    {
        /*

        $collection = collect(['taylor', 'abigail', null])->map(function (string $name) {
            return strtoupper($name);
        })->reject(function (string $name) {
            return empty($name);
        });
        

        $collection = collect([1, 2, 3, null, false, '', 0, []]);

        $collection->filter()->all();

        Collection::macro('toUpper', function () {
            return $this->map(function (string $value) {
                return Str::upper($value);
            });
        });
         
        $collection = collect(['first', 'second']);
         
        $upper = $collection->toUpper();

        */

        $data = Post::find(2);
        $categories = Post::getPostCategories($data);

        $x = Post::withCount('tags')->find(2);

        dd($x->tags_count);

        return view('admin.test', [
            'posts' => $data,
            'categories' => $categories
        ]);
    }
}
