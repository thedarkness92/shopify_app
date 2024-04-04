<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasRolesTable extends Migration
{
    public function up()
    {
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned();
            $table->morphs('model'); // Cela va créer model_id et model_type
            $table->timestamps();

            // Définir les clés étrangères
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_has_roles');
    }
}
