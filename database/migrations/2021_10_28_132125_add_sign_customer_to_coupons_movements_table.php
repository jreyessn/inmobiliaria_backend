<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignCustomerToCouponsMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons_movements', function (Blueprint $table) {
            $table->text("sign_customer")->after("payment_method_id")->nullable();
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
            $table->dropColumn(["sign_customer"]);
        });
    }
}
