<?php

use Doctrine\DBAL\Event\SchemaEventArgs;
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
        Schema::table('blog_post', function(Blueprint $table){
            $table->unsignedInteger('view_count')->after('comments_allowed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_post', function(Blueprint $table){
            $table->dropColumn('view_count');
        });
    }
};
