<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditCuoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_cuotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("credit_id")->constrained();
            $table->string("number_letter")->nullable();
            $table->string("reference", 100)->nullable();
            $table->timestamp("giro_at")->nullable();
            $table->timestamp("expiration_at")->nullable();
            $table->float("total")->default(0);
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
        Schema::dropIfExists('credit_cuotes');
    }
}
