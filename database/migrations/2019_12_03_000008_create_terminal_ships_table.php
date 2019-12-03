<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalShipsTable extends Migration
{
    public function up ()
    {
        Schema::create('terminal_ships', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('arrive_time')->nullable();
            $table->datetime('departure_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        }
        );
    }
}
