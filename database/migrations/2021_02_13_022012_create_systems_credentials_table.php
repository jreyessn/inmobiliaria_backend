<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemsCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('systems_credentials', function (Blueprint $table) {
            $table->id();
            $table->string("description")->nullable();
            $table->string("server", 100)->nullable();
            $table->string("username", 100);
            $table->string("password");
            $table->foreignId("system_id")->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('systems_credentials');
    }
}
