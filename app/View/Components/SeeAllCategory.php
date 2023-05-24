<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeeAllCategory extends Component
{
    public function __construct( public string $category )
    {
        
    }

    public function render()
    {        
        return view('components.see-all-category');
    }
}
