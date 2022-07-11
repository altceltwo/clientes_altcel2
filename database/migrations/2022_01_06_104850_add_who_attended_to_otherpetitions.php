<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWhoAttendedToOtherpetitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('otherpetitions', function (Blueprint $table) {
            // $table->unsignedBigInteger('who_attended')->nullable();

            // $table->foreign('who_attended')->references('id')->on('users');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('otherpetitions', function (Blueprint $table) {
            //
        });
    }
}
