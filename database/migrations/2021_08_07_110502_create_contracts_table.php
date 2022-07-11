<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('contracts', function (Blueprint $table) {
            // $table->id();
            // $table->string('name',100);
            // $table->string('lastnameP',100);
            // $table->string('lastnameM',100);
            // $table->string('email',60)->nullable();
            // $table->string('rfc',100);
            // $table->string('cellphone',15);
            // $table->string('typePhone',5);
            // $table->string('street',100);
            // $table->string('exterior',10)->nullable();
            // $table->string('interior',10)->nullable();
            // $table->string('colonia',50);
            // $table->string('municipal',60);
            // $table->string('state',50);
            // $table->string('postal_code',10);
            // $table->string('marca',30);
            // $table->string('modelo',100);
            // $table->string('serialNumber',30);
            // $table->integer('deviceQuantity');
            // $table->float('devicePrice',8,2);
            // $table->float('ratePrice',8,2);
            // $table->string('product',25)->nullable();
            // $table->string('msisdn',15)->nullable();
            // $table->string('icc',30)->nullable();
            // $table->string('typePayment',5);
            // $table->string('bank',30)->nullable();
            // $table->string('cardNumber',30)->nullable();
            // $table->string('cvv',5)->nullable();
            // $table->string('monthExpiration',2)->nullable();
            // $table->string('yearExpiration',2)->nullable();
            // $table->string('invoiceBool',2);
            // $table->string('rightsMinBool',2);
            // $table->string('contractAdhesionBool',2);
            // $table->string('useInfoFirst',2);
            // $table->string('useInfoSecond',2);
            // $table->string('signature',100);
            // $table->unsignedBigInteger('client_id');
            // $table->unsignedBigInteger('activation_id')->nullable();
            // $table->unsignedBigInteger('who_did_id');
            // $table->timestamps();

            // $table->foreign('client_id')->references('id')->on('users');
            // $table->foreign('activation_id')->references('id')->on('activations');
            // $table->foreign('who_did_id')->references('id')->on('users');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('contracts');
    }
}
