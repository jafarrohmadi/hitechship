<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryShipShipPivotTable extends Migration
{
    public function up ()
    {
        Schema::create('history_ship_ship', function (Blueprint $table) {
            $table->unsignedInteger('history_ship_id');
            $table->foreign('history_ship_id', 'history_ship_id_fk_687195')->references('id')->on('history_ships')->onDelete('cascade');
            $table->unsignedInteger('ship_id');
            $table->foreign('ship_id', 'ship_id_fk_687195')->references('id')->on('ships')->onDelete('cascade');
        }
        );
    }
}
