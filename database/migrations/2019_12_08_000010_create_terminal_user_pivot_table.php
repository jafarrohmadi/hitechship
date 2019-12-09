<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('terminal_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');

            $table->foreign('user_id', 'user_id_fk_706214')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('terminal_id');

            $table->foreign('terminal_id', 'terminal_id_fk_706214')->references('id')->on('terminals')->onDelete('cascade');
        });
    }
}
