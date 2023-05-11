<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_category' , function( Blueprint $table ) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->char('name', 100);
            $table->char('url', 100)->default('');            
            $table->string('description', 500)->default('');
            $table->enum('status',['1','0'])->default('1');     
            $table->unsignedSmallInteger('position');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('blog_category');
    }
};
