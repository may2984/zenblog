<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class TestContoller extends Controller
{
    public function index()
    {        
        $original_image = 'banners/rocket-3-gt-se-media-slide-2-1920x1080.jpg';

        return view('admin.test', ['original' => $original_image,]);
        #return view('admin.banner.crop', ['original' => $original_image,]);
    }
    public function crop(Request $request)
    {        
        // create instance
        $image = Image::make($request->input('image')); 

        $height = $image->height();
        $width = $image->width();

        $scale = $request->input("scale");        
        
        $scaled_height = $scale * $height;
        $scaled_width = $scale * $width;
        
        $rotation = $request->input("angle");                
        $image->rotate($rotation);

        // resize the image to a height of 200 and constrain aspect ratio (auto width)
        $scaled_image = $image->resize(null, $scaled_height, function ($constraint) {
            $constraint->aspectRatio();
        });

     //   dd( $scaled_image);

        $W = $request->input("w");
        $h = $request->input("h");        
        $x1 = $request->input("x");        
        $y1 = $request->input("y");        

      #  dd($W, $h, $x1, $y1, $scale);  

        $output = $scaled_image->crop($W, $h, $x1, $y1)->save('banners/mayank_crop.jpg', 60);  

        return view('admin.crop', [
            'croped' => 'banners/mayank_crop.jpg',
        ]);
        
    }

    public function test2()
    {        
        $original_image = 'banners/nTgWBgs0ZKZg9dcq6wwpPh8GOgV77LeDR1v4OFvt.jpg';
        $original_image = 'banners/IUxJN5EDLDEEoYdGkzvmrMzMpH3tJ56lPPEczb6X.jpg';
        $original_image = 'banners/rocket-3-gt-se-media-slide-2-1920x1080.jpg';

    //    $image = Image::make($original_image);


        return view('admin.test2', [
            'original' => $original_image,
        ]);
    }
    

    public function crop2(Request $request)
    {        
        // create instance
        $img = Image::make($request->input('image')); 

        $w = $request->input("w");
        $h = $request->input("h");        
        $x = $request->input("x");        
        $y = $request->input("y");  

     //   dd($request->input());

        $output = $img->crop($w, $h, $x, $y)->save('banners/mayank2-crop.jpg', 60);  

        return response()->json($output);

        /* return view('admin.crop', [
            'croped' => 'banners/mayank2-crop.jpg',
        ]); */
        
    }

    public function uploadDropzone1(Request $request)
    {
        $request->banner_image->store('banners', 'public');
    }

    public function test()
    {
        if(App::environment('local'))
        {

        }        
    }
}
