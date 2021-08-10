<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id");
            $table->integer("quantity_coupons");
            
            $table->unsignedBigInteger("user_request_id")->nullable();
            $table->foreign("user_request_id")->on('users')->references("id")->constrained();

            $table->tinyInteger("approved")->default(0)->comment("0 sin aprobar, 1 aprobado, 2 rechazado");
            $table->text("observation")->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('coupons_requests');
    }
}
