<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string("name", 200);
            $table->foreignId("categories_equipment_id")->nullable()->constrained();
            $table->foreignId("area_id")->nullable()->constrained();
            $table->string("brand")->nullable();
            $table->string("no_serie", 200)->nullable();
            $table->integer("between_days_service")->default(0);
            $table->float("cost")->default(0);
            $table->boolean("maintenance_required")->default(0);
            $table->boolean("no_serie_visible")->default(0);
            $table->boolean("create_services_automatic")->default(0)->comment("Si genera servicios automaticamente");
            $table->timestamp("obtained_at")->nullable();
            $table->timestamp("last_service_at")->nullable();
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
        Schema::dropIfExists('equipments');
    }
}
