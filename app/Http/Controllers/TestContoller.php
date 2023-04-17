<?php

namespace App\Http\Controllers;
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
        */

        $collection = collect([1, 2, 3, null, false, '', 0, []]);

        $collection->filter()->all();

        Collection::macro('toUpper', function () {
            return $this->map(function (string $value) {
                return Str::upper($value);
            });
        });
         
        $collection = collect(['first', 'second']);
         
        $upper = $collection->toUpper();

        return view('admin.test', ['names' => $upper]);
    }
}
