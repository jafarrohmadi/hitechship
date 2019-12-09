<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipTerminalPivotTable extends Migration
{
    public function up ()
    {
        Schema::create('ship_terminal', function (Blueprint $table) {
            $table->unsignedInteger('terminal_id');
            $table->foreign('terminal_id', 'terminal_id_fk_706062')->references('id')->on('terminals')->onDelete('cascade');
            $table->unsignedInteger('ship_id');
            $table->foreign('ship_id', 'ship_id_fk_706062')->references('id')->on('ships')->onDelete('cascade');
        }
        );
    }

}
