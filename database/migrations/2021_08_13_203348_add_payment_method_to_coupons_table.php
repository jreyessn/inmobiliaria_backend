<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons_movements', function (Blueprint $table) {
            $table->foreignId("payment_method_id")->after("is_automatic")->nullable();
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
            $table->dropForeign("payment_method_id");
            $table->dropColumn(["payment_method_id"]);
        });
    }
}
