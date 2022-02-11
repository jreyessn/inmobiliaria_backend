<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesKmTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_km_tracker', function (Blueprint $table) {
            $table->id();
            $table->foreignId("vehicle_id")->constrained();
            $table->float("km_previous")->comment("Km registrado por el sistema");
            $table->float("km_current")->comment("Km registrado por el usuario");
            $table->morphs("model");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles_km_tracker');
    }
}
