<?php

use App\Models\User;
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
        Schema::create('blog_author', function( Blueprint $table ){
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('first_name', 100);            
            $table->string('last_name', 100)->default('');            
            $table->string('pen_name', 100);
            $table->string('url', 100);
            $table->string('photo', 100)->default('');
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
        Schema::dropIfExists('blog_author');
    }
};
