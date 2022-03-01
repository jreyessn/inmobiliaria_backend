<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string("serie")->nullable();
            $table->string("number")->nullable();
            $table->foreignId("furniture_id")->constrained();
            $table->foreignId("document_id")->constrained();
            $table->foreignId("customer_id")->constrained();
            $table->foreignId("payment_method_id")->constrained();
            $table->float("tax_percentage")->default(0);
            $table->float("subtotal")->default(0);
            $table->text("note")->nullable();
            $table->boolean("is_credit")->default(0);
            $table->boolean("status")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
