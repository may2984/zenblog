<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestContoller extends Controller
{
    public function index()
    {
               
        $output = config('app.name');

        return view('admin.test', [
            'output' => $output,
        ]);
    }
}
