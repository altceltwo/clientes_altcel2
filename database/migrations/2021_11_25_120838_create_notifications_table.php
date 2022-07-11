<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('notifications', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('identifier',20)->nullable();
        //     $table->dateTime('effectiveDate',0);
        //     $table->string('eventType',30);
        //     $table->text('detail');
        //     $table->dateTime('date_notification',0);
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
