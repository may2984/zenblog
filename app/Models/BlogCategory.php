<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_category';

    protected $fillable = ['name','url','description','status'];

    public $timestamps = true;

    protected $casts = [        
        'created_at' => 'datetime',
    ];

    /*

    public function name(): BelongsTo
    {
        return $this->belongsTo(PostBlogCategory::class);
    }
    */

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }


}
