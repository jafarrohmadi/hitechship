<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryShipsTable extends Migration
{
    public function up ()
    {
        Schema::create('history_ships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('history_ids');
            $table->string('sin')->nullable();
            $table->string('region_name')->nullable();
            $table->datetime('receive_utc')->nullable();
            $table->datetime('message_utc')->nullable();
            $table->text('payload')->nullable();
            $table->string('ota_message_size')->nullable();
            $table->unsignedInteger('ship_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        }
        );
    }

}
