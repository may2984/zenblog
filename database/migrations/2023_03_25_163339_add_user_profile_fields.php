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
       Schema::table('users', function( Blueprint $table ) {
        $table->binary('image')->nullable();
        $table->text('about', 1000)->nullable();
        $table->char('company', 100)->nullable();
        $table->char('role', 200)->nullable();
        $table->text('address', 300)->nullable();
        $table->char('phone', 15)->nullable();
        $table->char('twitter', 200)->nullable();
        $table->char('facebook', 200)->nullable();
        $table->char('instagram', 200)->nullable();
        $table->char('linkedin', 200)->nullable();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('users', function( Blueprint $table ) {
        $table->dropColumn(['about','company','role','address','phone','twitter','facebook','instagram','linkedin']);
       });
    }
};
