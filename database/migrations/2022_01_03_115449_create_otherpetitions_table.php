<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherpetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('otherpetitions', function (Blueprint $table) {
            // $table->id();
            // $table->unsignedBigInteger('who_did_id');
            // $table->string('type',50);
            // $table->unsignedBigInteger('number_id');
            // $table->string('description',250);
            // $table->text('comment')->nullable();
            // $table->string('status');
            // $table->timestamps();

            // $table->foreign('who_did_id')->references('id')->on('users');
            // $table->foreign('number_id')->references('id')->on('numbers');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otherpetitions');
    }
}
