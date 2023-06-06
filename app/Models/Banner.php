<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['banner_image','post_id','banner_heading','banner_text','banner_position'];

    public function post():BelongsTo
    {
        return $this->BelongsTo(Post::class, 'post_id', 'id');
    }
}
