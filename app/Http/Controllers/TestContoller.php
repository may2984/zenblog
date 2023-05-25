<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class TestContoller extends Controller
{
    public function index()
    {
               
        # $output = config('app.name');
        $output = Carbon::now()->diffInDays('2023-05-24 13:05:22');     

        return view('admin.test', [
            'output' => $output,
        ]);
    }
}
