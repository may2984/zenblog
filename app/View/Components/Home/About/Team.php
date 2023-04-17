<?php

namespace App\View\Components\Home\About;

use Illuminate\View\Component;

class Team extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $data = [
            'person 1' => [
                'img' => 'person-1.jpg',
            ],
            'person 2' => [
                'img' => 'person-2.jpg',
            ],
            'person 3' => [
                'img' => 'person-3.jpg',
            ],
            'person 4' => [
                'img' => 'person-4.jpg',
            ],
            'person 5' => [
                'img' => 'person-5.jpg',
            ],
            'person 6' => [
                'img' => 'person-6.jpg'
            ],
        ];

        return view('components.home.about.team', ['data' => $data]);
    }
}
