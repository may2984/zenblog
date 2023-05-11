<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_post_views', function( Blueprint $table ) {
            $table->id();
            $table->foreignId('post_id')->references('id')->on('blog_post')->cascadeOnDelete();            
            $table->timestamp('viewed_at', $precision = 0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_post_views');
    }
};
