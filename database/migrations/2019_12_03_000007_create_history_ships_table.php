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
            $table->string('latitute');
            $table->string('logitude');
            $table->string('time_ship');
            $table->timestamps();
            $table->softDeletes();
        }
        );
    }
}
