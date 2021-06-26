<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagerUserPivotTable extends Migration
{
    public function up ()
    {
        Schema::create('manager_user', function (Blueprint $table) {
            $table->unsignedInteger('manager_id');
            $table->foreign('manager_id', 'manager_id_fk_686825')->references('id')->on('managers')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_686825')->references('id')->on('users')->onDelete('cascade');
        }
        );
    }

}
