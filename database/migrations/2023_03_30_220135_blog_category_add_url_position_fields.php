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
        Schema::table('blog_category', function( Blueprint $table ) {
            $table->char('url', 100)->default('');
            $table->tinyInteger('position')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_category', function( Blueprint $table ) {
            $table->dropColumn('url');
            $table->dropColumn('position');
        });
    }
};
