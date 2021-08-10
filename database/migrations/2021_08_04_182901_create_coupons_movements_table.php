<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id")->constrained();
            $table->string("type_movement", 20);
            $table->integer("quantity")->default(0);
            $table->decimal("price")->default(0);
            $table->boolean("is_automatic")->default(false);
            $table->text("comment")->nullable();
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
        Schema::dropIfExists('coupons_movements');
    }
}
