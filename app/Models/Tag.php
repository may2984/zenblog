<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
  
}
