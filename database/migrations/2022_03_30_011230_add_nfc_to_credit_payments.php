<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNfcToCreditPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_payments', function (Blueprint $table) {
            $table->string("nfc", 50)->nullable()->after("note");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_payments', function (Blueprint $table) {
            $table->dropColumn(["nfc"]);
        });
    }
}
