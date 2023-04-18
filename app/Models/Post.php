<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'blog_post';

    protected $fillable = ['user_id','title','slug','summary','body','meta_title','published','comments_allowed','published_at'];

    public $timestamps = true;

    protected $casts = [
        'published_at' => 'datetime',      
    ];

    protected $attributes = [      
       // 'published' => Carbon::today()
    ];    

    # Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): belongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'post_id', 'category_id');
    }

    public function post_main_category(): belongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'post_id', 'category_id'); //->wherePivot('is_main_category', 1);
    }

    public function tags(): belongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag', 'blog_post_id', 'blog_tag_id');
    }

    public function authors(): belongsToMany
    {
        return $this->belongsToMany(Author::class, 'blog_post_author');
    }

    public static function postMainCategory( $post ){
        
        # fetch the main category
        $category = $post->post_main_category->map( function( $post_main_category ) {
            return $post_main_category->url;
        }); 
        
        return $category[0];
    }

    public static function getPostCategories( $post ){

        # fetch all the categories
        $categories = $post->categories->map( function( $categories ) {
            return $categories->name;
        });    

        return Arr::join( $categories->all(), ', ', ' and ');
    }

    public static function getPostAuthors( $post ){

        # fetch all the authors
        $authors = $post->authors->map( function( $authors ){
            return $authors->fullName;
        });

        return Arr::join( $authors->all(), ', ',' and ');
    }

    # scopes
    public function scopePublished(Builder $query): void
    {
        $query->where('published', '1');       
    }
}
