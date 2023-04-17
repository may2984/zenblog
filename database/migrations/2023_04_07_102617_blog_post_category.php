<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Post;
use App\Models\BlogCategory;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_post_category', function( Blueprint $table ) {
            $table->id();
            $table->foreignIdFor(Post::class)->references('id')->on('blog_post')->cascadeOnDelete();
            $table->foreignIdFor(BlogCategory::class)->references('id')->on('blog_category')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_post_category');
    }
};
