<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipUserPivotTable extends Migration
{
     public function up()
    {
        Schema::create('ship_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');

            $table->foreign('user_id', 'user_id_fk_706215')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('ship_id');

            $table->foreign('ship_id', 'ship_id_fk_706215')->references('id')->on('ships')->onDelete('cascade');
        });
    }
}
