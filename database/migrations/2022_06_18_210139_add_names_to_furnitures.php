<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamesToFurnitures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('furniture', function (Blueprint $table) {
            $table->string("agent_name")->nullable()->after("agent_user_id");
            $table->string("getter_name")->nullable()->after("getter_user_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('furniture', function (Blueprint $table) {
            $table->dropColumn(["agent_name", "getter_name"]);
        });
    }
}
