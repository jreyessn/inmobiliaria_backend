<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tools_users', function (Blueprint $table) {
            $table->id();
            $table->integer("quantity")->default(0);
            $table->foreignId("tool_id")->constrained();
            $table->foreignId("user_id")->constrained()->comment("Poseedor de las herramientas");
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
        Schema::dropIfExists('tools_users');
    }
}
