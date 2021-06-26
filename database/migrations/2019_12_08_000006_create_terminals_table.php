<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalsTable extends Migration
{
    public function up ()
    {
        Schema::create('terminals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('air_comm_blocked')->default(0)->nullable();
            $table->boolean('power_backup')->default(0)->nullable();
            $table->boolean('power_main')->default(0)->nullable();
            $table->boolean('sleep_schedule')->default(0)->nullable();
            $table->boolean('battery_low')->default(0)->nullable();
            $table->boolean('speeding_start')->default(0)->nullable();
            $table->boolean('speeding_end')->default(0)->nullable();
            $table->boolean('modem_registration')->default(0)->nullable();
            $table->boolean('geofence_in')->default(0)->nullable();
            $table->boolean('geofence_out')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        }
        );
    }

}
