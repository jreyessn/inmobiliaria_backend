<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentsPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments_parts', function (Blueprint $table) {
            $table->id();
            $table->string("name", 200);
            $table->integer("between_days_service")->default(3);
            $table->unsignedBigInteger('equipment_id');
            $table->foreign('equipment_id', 'equipment_id_foreign_key')
                  ->references('id')
                  ->on('equipments')
                  ->constrained()
                  ->onDelete('cascade');
                  
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
        Schema::dropIfExists('equipments_parts');
    }
}
