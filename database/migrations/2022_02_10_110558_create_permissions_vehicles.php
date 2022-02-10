<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("vehicle_id")->constrained();
            $table->string("concept")->nullable();
            $table->timestamp("date")->nullable();
            $table->timestamp("expiration_at")->nullable();
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
        Schema::dropIfExists('permissions_vehicles');
    }
}
