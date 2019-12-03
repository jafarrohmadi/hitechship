<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalTerminalShipPivotTable extends Migration
{
    public function up ()
    {
        Schema::create('terminal_terminal_ship', function (Blueprint $table) {
            $table->unsignedInteger('terminal_ship_id');
            $table->foreign('terminal_ship_id', 'terminal_ship_id_fk_687227')->references('id')->on('terminal_ships')->onDelete('cascade');
            $table->unsignedInteger('terminal_id');
            $table->foreign('terminal_id', 'terminal_id_fk_687227')->references('id')->on('terminals')->onDelete('cascade');
        }
        );
    }
}
