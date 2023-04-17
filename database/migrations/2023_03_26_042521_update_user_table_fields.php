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
        Schema::table('users', function ( Blueprint $table ) {
            $table->char('country', 20)->nullable();
            $table->longText('about')->nullable()->change();            
            $table->char('role', 100)->nullable()->change();            
            $table->char('phone', 15)->nullable()->change();    
            $table->char('twitter', 100)->nullable()->change();
            $table->char('facebook', 100)->nullable()->change();
            $table->char('instagram', 100)->nullable()->change();
            $table->char('linkedin', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
