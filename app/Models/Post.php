<?php

namespace App\Models;

use App\Enums\PostState;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

# use PhpParser\Node\AttributeGroup;

class Post extends Model
{
    use HasFactory;

    protected $table = 'blog_post';

    protected $fillable = ['user_id','title','slug','summary','body','meta_title','published','comments_allowed','published_at'];

    public $timestamps = true;

    public $setAuthors = true;

    public string $categoryName;

    public int $categoryId = 0;

    public int $numberOfTrendingPost = 5;

    protected $casts = [
        'published_at' => 'datetime',   
        'state' => PostState::class,
    ];

    protected $attributes = [      
       'published_at' => 'now',
    ];    

    # Setter and Getter
    public function publishedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Carbon::create($attributes['published_at'])->format('M jS \'y')            
        );        
    }

    public function publishDate(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Carbon::create($attributes['published_at'])->format('Y-m-d'),
        );        
    }

    public function publishTime(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Carbon::create($attributes['published_at'])->format('H:i')
        );
    }

    # Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): belongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'post_id', 'category_id');
    }   

    public function tags(): belongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag', 'post_id', 'tag_id');
    }

    public function authors(): belongsToMany
    {
        return $this->belongsToMany(Author::class, 'blog_post_author');
    }

    public function post_main_category(): belongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'post_id', 'category_id')->wherePivot('is_main_category', 1);
    }

    public function post_other_categories(): belongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'post_id', 'category_id')->wherePivot('is_main_category', 0);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public static function postMainCategory( $post )
    {        
        # fetch the main category
        $category = $post->post_main_category->map( function( $post_main_category ) {
            return $post_main_category->name;
        }); 
        
        return $category[0];
    }

    public static function postCategoryURL( $post )
    {        
        # fetch the main category
        $category = $post->post_main_category->map( function( $post_main_category ) {
            return $post_main_category->url;
        }); 
        
        return $category[0];
    }

    public static function getPostCategories( $post )
    {
        # fetch all the categories
        $categories = $post->categories->map( function( $categories ) {
            return $categories->name;
        });    

        return Arr::join( $categories->all(), ', ', ' and ');
    }

    public static function getPostAuthors( $post )
    {
        # fetch all the authors
        $authors = $post->authors->map( function( $authors ){
            return $authors->fullName;
        });

        return Arr::join( $authors->all(), ', ',' and ');
    }

    public function name()
    {
        return 'Mayank';
    }

    public function setPost( $posts )
    {
         $setPost = $posts->map( function( $posts ){

            $posts->category = $this->postMainCategory( $posts );
            $posts->categoryURL = $this->postCategoryURL( $posts );
            if( $this->setAuthors ){
                $posts->author = $this->getPostAuthors( $posts );                
            }
            $posts->url = route('post.url', ['blog_category' => $posts->categoryURL, 'slug' => $posts->slug, 'post' => $posts->id ]);          

            return $posts;
        });

        return $setPost;
    }

    public function getHomePostIds( $category_name )
    {
        $this->categoryName = $category_name;

        return DB::table('order_post AS home_posts')
                    ->selectRaw('home_posts.post_id')
                    ->join('blog_post AS post', 'post.id', '=', 'home_posts.post_id')
                    ->join('blog_category AS category', function($join){
                        $join->on('category.id', '=', 'home_posts.category_id')
                        ->where('category.name', '=', $this->categoryName);
                    })
                    ->where('post.published', '=', '1')
                    ->orderBy('home_posts.position')
                    ->get();
    }

    public function getHomePageStoryByCategoryId()
    {
        return DB::table('order_post AS home_post')->selectRaw('home_post.post_id')
                ->where('home_post.category_id', '=', $this->categoryId)
                ->orderBy('home_post.position')
                ->get();      
    }

    public function getTrendingPosts()
    {
        return  Post::with('post_main_category:name,url', 'authors:first_name,last_name')
                    ->select('id','title','slug','published_at')
                    ->published()
                    ->orderByDesc('view_count')
                    ->take( $this->numberOfTrendingPost )
                    ->get();     
    }

    public function getHomePageStoryByCategoryName()
    {
        return Post::with('post_main_category:name,url', 'authors:first_name,last_name')
                    ->select('blog_post.id', 'blog_post.title', 'blog_post.slug', 'blog_post.summary', 'blog_post.published_at')
                    ->join('order_post', 'blog_post.id', '=', 'order_post.post_id')
                    ->join('blog_category AS category', function($join){
                        $join->on('category.id', '=', 'order_post.category_id')
                        ->where('category.name', '=', $this->categoryName);
                    })
                    ->where('blog_post.published', '=', '1')
                    ->orderBy('order_post.position')
                    ->get();        
    }

    # scopes
    public function scopePublished(Builder $query): void
    {
        $query->where('published', '1');       
    }
}
