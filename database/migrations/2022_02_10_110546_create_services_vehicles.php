<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("vehicle_id")->constrained();
            $table->float("km_current")->default(0);
            $table->foreignId("type_service_vehicle_id")->constrained();
            $table->timestamp("event_date")->nullable();
            $table->float("amount")->default(0);
            $table->tinyInteger("status")->default(0)->comment("0 pediente, 1 cumplido, 2 vencida");
            $table->text("note")->nullable();
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
        Schema::dropIfExists('services_vehicles');
    }
}
