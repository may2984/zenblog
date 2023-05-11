<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Tag;
use App\Models\Post;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_tag', function( Blueprint $table ) {
            $table->id();            
            $table->foreignId('post_id')->references('id')->on('blog_post')->cascadeOnDelete();
            $table->foreignId('tag_id')->references('id')->on('blog_tag')->cascadeOnDelete();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_post_tag');
    }
};
