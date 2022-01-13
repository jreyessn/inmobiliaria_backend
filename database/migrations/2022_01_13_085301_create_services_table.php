<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId("categories_service_id")->constrained();
            $table->foreignId("type_service_id")->constrained();
            $table->foreignId("equipments_part_id")->constrained();
            
            $table->bigInteger("user_assigned_id")->unsigned();
            $table->foreign("user_assigned_id")->on("users")->references("id")->constrained();
            
            $table->foreignId("farm_id")->constrained()->nullable();
            $table->timestamp("event_date")->nullable();
            $table->text("note")->nullable();
            $table->string("received_by", 200)->nullable();
            $table->text("observation")->nullable();
            $table->timestamp("completed_at")->nullable();
            $table->boolean("status")->default(0)->comment("0 pendiente, 1 completado, 2 cancelado");
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
        Schema::dropIfExists('services');
    }
}
