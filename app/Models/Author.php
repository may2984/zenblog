<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_author';

    protected $fillable = ['first_name','last_name', 'pen_name', 'url', 'photo'];

    public $timestamps = true;   

    protected $appends = [
        'full_name',
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d',        
    ];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['first_name'].' '.$attributes['last_name']            
        );
    }  
    
    # test mutator
    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

 /*    public function authors(): BelongsTo    
    {
        return $this->BelongsTo(PostAuthor::class);
    } */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);        
    }
}
