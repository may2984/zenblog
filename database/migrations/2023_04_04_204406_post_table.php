<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post', function( Blueprint $table ){
            $table->id();            
            $table->foreignId('user_id');
            $table->string('title', 200);
            $table->string('slug', 200);
            $table->string('summary', 255);
            $table->text('body');
            $table->string('meta_title', 255);
            $table->enum('published', ['0','1']);
            $table->enum('comments_allowed', ['0','1']);
            $table->dateTime('published_at', $precision=0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_post');
    }
};
