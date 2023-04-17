<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Post;
use App\Models\Author;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_post_author', function(Blueprint $table){
            $table->id();
            $table->foreignIdFor(Post::class)->references('id')->on('blog_post')->cascadeOnDelete();
            $table->foreignIdFor(Author::class)->references('id')->on('blog_author')->cascadeOnDelete();            
            $table->timestamps();   
            $table->softDeletes();
        });         
    }

    public function down()
    {
        Schema::dropIfExists('blog_post_author');
    }
};
