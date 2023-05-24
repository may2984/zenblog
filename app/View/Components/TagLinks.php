<?php

namespace App\View\Components;

use App\Models\Tag;
use Illuminate\View\Component;

class TagLinks extends Component
{
    public int $numberOfLinks;

    public function __construct( $numberOfLinks )
    {
        $this->numberOfLinks = $numberOfLinks;
    }

    public function render()
    {
        $tags = Tag::withCount('posts')->orderByDesc('posts_count')->take( $this->numberOfLinks )->get();

        return view('components.tag-links', [
            'tags' => $tags,
        ]);
    }
}
