<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 200);
            $table->string('last_name', 200);
            $table->string('email', 50);
            $table->string('phone', 20);
            $table->string('occupation', 100)->nullable();
            $table->string('street', 200);
            $table->string('city', 80);
            $table->string('country', 80);
            $table->string('postcode', 50);
            $table->string('image', 50);
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
        Schema::dropIfExists('persons');
    }
}
