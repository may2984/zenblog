<?php

use App\Models\Post;
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
        Schema::create('comments', function(Blueprint $table){
            $table->id();
            $table->foreignIdFor(Post::class)->references('id')->on('blog_post')->cascadeOnDelete();
            $table->unsignedBigInteger('comment_id')->default(0);
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('message', 500);
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
        Schema::dropIfExists('comments');
    }
};
