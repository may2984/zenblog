<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'blog_tag';

    protected $fillable = ['name','status'];

    # set default values
    protected $attributes = [
      'status' => true,
    ];

    public $timestamps = true;

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class);
    }

    public function posts(): BelongsToMany
    {
      return $this->belongsToMany(Post::class, 'blog_post_tag', 'tag_id', 'post_id');
    }  
}
