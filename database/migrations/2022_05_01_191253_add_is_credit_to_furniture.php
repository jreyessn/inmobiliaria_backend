<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCreditToFurniture extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('furniture', function (Blueprint $table) {
            $table->float("fee_getter",16,2)->default(0)->after("getter_user_id")->comment("Comisión del captador");
            $table->boolean("is_credit")->default(1)->after("initial_price")->comment("Si es crédito o al contado");
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
            $table->dropColumn(["fee_getter", "is_credit"]);
        });
    }
}
