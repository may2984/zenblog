<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class BannerUpload extends Component
{
    use WithFileUploads;

    public $banner_image;

    protected $listeners = ['fileUpload'];

    public function fileUpload($imageData)
    {
        $this->banner_image = $imageData;  
    }

    public function render()
    {
        return view('livewire.banner-upload');
    }

    public function upload()
    {
        $this->banner_image->store('banners', 'public');
    }
}
