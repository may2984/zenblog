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
    use HasFactory;

    protected $table = 'blog_author';

    protected $fillable = ['name','created_by'];

    public $timestamps = true;   

    protected function fullName(): Attribute
    {
        return Attribute::make(
            fn ($value, $attributes) => $attributes['first_name'].' '.$attributes['last_name']            
        );
    }  
    
    # test mutator
    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    public function authors(): BelongsTo    
    {
        return $this->BelongsTo(PostAuthor::class);
    }
}
