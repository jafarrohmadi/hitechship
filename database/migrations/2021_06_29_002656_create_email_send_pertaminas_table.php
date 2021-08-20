<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSendPertaminasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_send_pertaminas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ship_id');
            $table->bigInteger('history_ship_id');
            $table->dateTime('last_seen_time');
            $table->json('last_sent_destination');
            $table->string('last_sent_status');
            $table->string('subject');
            $table->string('filename_chr');
            $table->string('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_send_pertaminas');
    }
}
