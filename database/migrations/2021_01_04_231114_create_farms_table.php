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
            $table->string("name", 200);
            $table->unsignedBigInteger('farm_manager_id')->comment("Jefe de Granja");
            $table->foreign('farm_manager_id')->references('id')->on('persons');
            $table->unsignedBigInteger('sharecropper_id')->comment("Aparcero");
            $table->foreign('sharecropper_id')->references('id')->on('persons');
            $table->text("direction")->nullable();
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
