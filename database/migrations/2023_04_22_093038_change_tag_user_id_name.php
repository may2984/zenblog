<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blog_tag', function(Blueprint $table){
            $table->renameColumn('tag_user_id', 'user_id');
        });
    }

    public function down()
    {
        Schema::table('blog_tag', function(Blueprint $table){
            $table->renameColumn('user_id', 'tag_user_id');
        });
    }
};
