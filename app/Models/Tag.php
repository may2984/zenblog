<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'blog_tag';

    protected $fillable = ['name','status'];

    public $timestamps = true;
  /*
    public function tags(): HasMany
    {
        return $this->hasMany(BlogPostTag::class);
    }
    */
}
