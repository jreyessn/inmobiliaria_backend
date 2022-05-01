<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrenciesToAnyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->foreignId("currency_id")->after("total")->nullable()->constrained();
            $table->float("rate")->after("currency_id")->default(0)->comment("Tasa");
        });

        Schema::table('credit_payments', function (Blueprint $table) {
            $table->foreignId("currency_id")->after("amount")->nullable()->constrained();
            $table->float("rate")->after("currency_id")->default(0)->comment("Tasa");
        });

        Schema::table('furniture', function (Blueprint $table) {
            $table->foreignId("currency_id")->after("is_credit")->nullable()->constrained();
            $table->float("rate")->after("currency_id")->default(0)->comment("Tasa");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->dropForeign("credits_currency_id_foreign");
            $table->dropColumn(["currency_id", "rate"]);
        });

        Schema::table('credit_payments', function (Blueprint $table) {
            $table->dropForeign("credit_payments_currency_id_foreign");
            $table->dropColumn(["currency_id", "rate"]);
        });

        Schema::table('furniture', function (Blueprint $table) {
            $table->dropForeign("furniture_currency_id_foreign");
            $table->dropColumn(["currency_id", "rate"]);
        });
    }
}
