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
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('long')->nullable();
            $table->string('owner')->nullable();
            $table->string('call_sign')->nullable();
            $table->string('ship_ids');
            $table->string('region_name')->nullable();
            $table->datetime('last_registration_utc')->nullable();
            $table->timestamps();
            $table->softDeletes();
        }
        );
    }

}
