<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portabilities', function (Blueprint $table) {
            $table->id();
            $table->string('msisdnTransitory',15);
            $table->string('icc',50);
            $table->string('msisdnPorted',15);
            $table->string('imsi',20);
            $table->date('approvedDateABD')->nullable();
            $table->date('date');
            $table->string('dida',5);
            $table->string('rida',5);
            $table->string('dcr',5);
            $table->string('rcr',5);
            $table->string('order_id',30)->nullable();
            $table->string('status',30)->default('pendiente');
            $table->unsignedBigInteger('who_did_it')->nullable();
            $table->unsignedBigInteger('who_attended')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->timestamps();

            $table->foreign('who_did_it')->references('id')->on('users');
            $table->foreign('who_attended')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portabilities');
    }
}
