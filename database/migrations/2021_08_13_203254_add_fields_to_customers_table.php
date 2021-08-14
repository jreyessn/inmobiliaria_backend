<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string("street", 200)->after("business_name")->nullable();
            $table->string("street_number", 200)->after("street")->nullable();
            $table->string("colony", 200)->after("street_number")->nullable();
            $table->string("phone", 200)->after("colony")->nullable();
            $table->string("email", 200)->after("phone")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(["street", "street_number", "colony", "phone", "email"]);
        });
    }
}
