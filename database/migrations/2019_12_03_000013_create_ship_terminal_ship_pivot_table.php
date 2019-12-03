<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipTerminalShipPivotTable extends Migration
{
    public function up ()
    {
        Schema::create('ship_terminal_ship', function (Blueprint $table) {
            $table->unsignedInteger('terminal_ship_id');
            $table->foreign('terminal_ship_id', 'terminal_ship_id_fk_687226')->references('id')->on('terminal_ships')->onDelete('cascade');
            $table->unsignedInteger('ship_id');
            $table->foreign('ship_id', 'ship_id_fk_687226')->references('id')->on('ships')->onDelete('cascade');
        }
        );
    }
}
