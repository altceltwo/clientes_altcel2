<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('cp',10)->nullable()->defauñt('N/A');
            $table->string('colonia',60)->nullable()->default('N/A');
            $table->string('tipo_asentamiento',40)->nullable()->default('N/A');
            $table->string('municipio',60)->nullable();
            $table->string('estado',40)->nullable();
            $table->string('ciudad',60)->nullable();
            $table->string('pais',30)->nullable()->default('MEXICO');
            $table->string('no_exterior',5)->nullable()->default('N/A');
            $table->string('no_interior',5)->nullable()->default('N/A');
            $table->string('phone_contact',15)->nullable()->default('N/A');
            $table->text('referencias')->nullable();
            $table->string('recibe',70)->nullable()->default('N/A');
            $table->string('phone_alternative',15)->nullable()->default('N/A');
            $table->string('canal',20)->nullable()->default('N/A');
            $table->dateTime('fecha_entregado',0)->nullable();
            $table->string('status',20)->default('pendiente');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('sold_by');
            $table->unsignedBigInteger('attended_by')->nullable();
            $table->unsignedBigInteger('to_id');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('sold_by')->references('id')->on('users');
            $table->foreign('to_id')->references('id')->on('users');
            $table->foreign('attended_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
}
