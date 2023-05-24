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
        Schema::table('blog_post_views', function(Blueprint $table){
            $table->ipAddress('visitor_ip')->after('post_id');
            $table->string('user_agent', 200)->after('visitor_ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_post_views', function(Blueprint $table){
            $table->dropColumn('visitor_ip');
            $table->dropColumn('user_agent');
        });
    }
};
