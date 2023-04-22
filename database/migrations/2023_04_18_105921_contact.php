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
        Schema::create('blog_contact', function(Blueprint $table){
            $table->id();
            $table->string('name',100);
            $table->string('email',100);
            $table->string('subject',200);
            $table->string('message', 1000);
            $table->ipAddress('visitor_ip');
            $table->string('user_agent', 200);
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
       Schema::dropIfExists('blog_contact');
    }
};
