<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsPricesToShippings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->unsignedBigInteger('rate_id')->nullable()->after('ine_back');
            $table->float('rate_price',8,2)->nullable()->after('rate_id');
            $table->float('shipping_price',8,2)->nullable()->after('rate_price');
            $table->string('reference_id',255)->nullable()->after('shipping_price');

            $table->foreign('rate_id')->references('id')->on('rates');
            $table->foreign('reference_id')->references('reference_id')->on('references');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shippings', function (Blueprint $table) {
            //
        });
    }
}
