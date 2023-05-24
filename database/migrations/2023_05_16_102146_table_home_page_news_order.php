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
        Schema::create('order_post', function(Blueprint $table){
            $table->integer('category_id');            
            $table->foreignIdFor(Post::class)->references('id')->on('blog_post')->cascadeOnDelete();
            $table->integer('position');
        });
    }

    public function down()
    {
        Schema::drop('order_post');
    }
};