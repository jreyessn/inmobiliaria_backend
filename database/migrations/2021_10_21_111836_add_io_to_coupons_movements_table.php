<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIoToCouponsMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons_movements', function (Blueprint $table) {
            $table->tinyInteger("io")->default(1)->after("type_movement")->comment("1 = entrada, 2 = salida");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons_movements', function (Blueprint $table) {
            $table->dropColumn(["io"]);
        });
    }
}
