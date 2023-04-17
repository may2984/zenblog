<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostViews extends Model
{
    use HasFactory;

    protected $table = ['blog_post_views'];

    public $fillable = ['post_id'];

    public $timestamps = true;

}
