<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToCouponsRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons_requests', function (Blueprint $table) {
            $table->foreignId("payment_method_id")->after("quantity_coupons")->nullable();
            $table->string("comment", 200)->after("payment_method_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons_requests', function (Blueprint $table) {
            $table->dropForeign("payment_method_id");
            $table->dropColumn(["payment_method_id"]);
        });
    }
}
