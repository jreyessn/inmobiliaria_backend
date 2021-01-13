<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->string("centro", 200)->nullable();
            $table->string("supervisor", 200)->nullable();
            $table->string("gerente", 200)->nullable();
            $table->string("nombre_centro", 200)->nullable();
            $table->string("nombre_supervisor", 200)->nullable();
            $table->string("nombre_gerente", 200)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farms');
    }
}
