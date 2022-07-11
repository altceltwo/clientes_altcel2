<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('petitions', function (Blueprint $table) {
            // $table->id();
            // $table->unsignedBigInteger('sender');
            // $table->string('status');
            // $table->dateTime('date_sent',0);
            // $table->dateTime('date_activated',0)->nullable();
            // $table->dateTime('date_received',0)->nullable();
            // $table->unsignedBigInteger('who_attended')->nullable();
            // $table->unsignedBigInteger('who_received')->nullable();
            // $table->float('collected',8,2)->nullable();
            // $table->unsignedBigInteger('client_id');
            // $table->string('product');
            // $table->text('comment')->nullable();
            // $table->timestamps();

            // $table->foreign('sender')->references('id')->on('users');
            // $table->foreign('who_attended')->references('id')->on('users');
            // $table->foreign('who_received')->references('id')->on('users');
            // $table->foreign('client_id')->references('id')->on('users');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('petitions');
    }
}
