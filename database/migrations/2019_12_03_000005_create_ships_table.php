<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipsTable extends Migration
{
    public function up ()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->string('long')->nullable();
            $table->string('owner')->nullable();
            $table->timestamps();
            $table->softDeletes();
        }
        );
    }
}
