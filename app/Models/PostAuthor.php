<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PostAuthor extends Model
{
    use HasFactory;

    protected $table = 'blog_post_author';

    protected $fillable = ['post_id','author_id'];

    public $timestamps = true;

    public function name(): HasOne    
    {
        return $this->HasOne(Author::class, 'id');
    }
}
