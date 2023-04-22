<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'blog_contact';

    protected $fillable = ['name', 'email', 'subject', 'message', 'visitor_ip', 'user_agent'];

    // 
}
