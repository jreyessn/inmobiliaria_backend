<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFurnitureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('furniture', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description")->nullable();
            $table->integer("bathrooms")->default(0);
            $table->integer("bedrooms")->default(0);
            $table->integer("covered_garages")->default(0);
            $table->integer("uncovered_garages")->default(0);
            $table->foreignId("measure_unit_id")->nullable()->constrained();
            $table->string("area")->nullable();
            $table->float("unit_price")->default(0)->comment("Valor Unitario");
            $table->float("initial_price")->default(0)->comment("Inicial");
            $table->foreignId("type_furniture_id")->constrained();
            $table->unsignedBigInteger("city_id")->nullable();
            $table->string("postal_code")->nullable();
            $table->string("region")->nullable();
            $table->string("address")->nullable();
            $table->string("street_number")->nullable();
            $table->string("aditional_info_address")->nullable();
            $table->integer("flat")->default(0);
            $table->string("reference_address")->nullable();
            
            $table->foreignId("customer_id")->nullable()->constrained();

            $table->bigInteger('getter_user_id')->unsigned()->nullable();
            $table->foreign("getter_user_id")
                  ->nullable()
                  ->references('id')
                  ->on('users')
                  ->comment("Persona que trajo el apartamento a la empresa");

            $table->bigInteger('agent_user_id')->unsigned()->nullable();
            $table->foreign("agent_user_id")
                  ->nullable()
                  ->references('id')
                  ->on('users')
                  ->comment("Corredor");

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
        Schema::dropIfExists('furniture');
    }
}
