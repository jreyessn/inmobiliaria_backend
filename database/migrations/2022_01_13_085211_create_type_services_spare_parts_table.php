<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeServicesSparePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_services_spare_parts', function (Blueprint $table) {
            $table->foreignId("type_service_id")->constrained()->onDelete("cascade");
            $table->foreignId("spare_part_id")->constrained();
            $table->primary(['type_service_id', 'spare_part_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_services_spare_parts');
    }
}
