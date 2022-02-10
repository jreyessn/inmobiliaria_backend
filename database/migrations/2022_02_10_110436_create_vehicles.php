<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("brand")->nullable();
            $table->string("model")->nullable();
            $table->string("license_plate")->nullable();
            $table->string("no_serie")->nullable();
            $table->foreignId("user_id")->nullable()->constrained()->comment("Chofer");
            $table->string("insurance_policy")->nullable();
            $table->float("km_start")->default(0);
            $table->float("km_limit")->default(0);
            $table->text("comments")->nullable();
            $table->timestamp("maintenance_limit_at")->nullable();
            $table->timestamp("expiration_license_at")->nullable();
            $table->timestamp("expiration_policy_at")->nullable();
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
        Schema::dropIfExists('vehicles');
    }
}
