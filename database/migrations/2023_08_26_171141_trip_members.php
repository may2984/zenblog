<?php

use App\Models\Member;
use App\Models\Trip;
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
       Schema::create('trip_members', function( Blueprint $table ){
        $table->id();
        $table->foreignIdFor(Trip::class)->references('id')->on('trips')->cascadeOnDelete();
        $table->foreignIdFor(Member::class)->references('id')->on('members')->cascadeOnDelete();
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
        Schema::dropIfExists('trip_members');
    }
};
