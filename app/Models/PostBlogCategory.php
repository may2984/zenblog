<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PostBlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_post_category';

    protected $fillable = ['post_id','category_id', 'is_main_category'];

    public $timestamps = true;    
}
