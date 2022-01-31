<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEquipmentIdToTableServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->bigInteger("equipment_id")->unsigned()->after("type_service_id");
            $table->foreign("equipment_id")->on("equipments")->references("id")->constrained(); 

            $table->foreignId("priorities_service_id")->after("status")->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign("equipment_id");
            $table->dropForeign("priorities_service");
            $table->dropColumn(["equipment_id", "priorities_service"]);
        });
    }
}
