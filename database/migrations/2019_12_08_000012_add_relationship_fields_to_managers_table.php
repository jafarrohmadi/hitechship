<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToManagersTable extends Migration
{
    public function up ()
    {
        Schema::table('managers', function (Blueprint $table) {
            $table->unsignedInteger('manager_id')->nullable();
            $table->foreign('manager_id', 'manager_fk_706063')->references('id')->on('users');
        }
        );
    }

}
