<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BlogPostTag extends Model
{
    use HasFactory;

    protected $table = 'blog_post_tag';

    public $fillable = [
        'blog_post_id',
        'blog_tag_id'
    ];

    public $timestamps = true;

    /*
    public function tag(): HasOne
    {
        return $this->hasOne(Tag::class, 'id', 'blog_tag_id');
    }
    */
}
