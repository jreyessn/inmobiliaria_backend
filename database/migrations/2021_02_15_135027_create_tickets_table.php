<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->foreignId("contact_id")->constrained();
            $table->string("cc")->nullable();
            $table->foreignId("type_ticket_id")->constrained();
            $table->foreignId("status_ticket_id")->constrained();
            $table->foreignId("priority_id")->constrained();
            $table->foreignId("group_id")->nullable()->constrained();
            $table->foreignId("user_id")->nullable()->comment("Asignado al usuario")->constrained();
            $table->boolean("spam")->default(0);
            $table->timestamp("deadline")->nullable();
            $table->timestamp("tracked_initial_time")->nullable();
            $table->timestamp("tracked_end_time")->nullable();
            $table->timestamp("first_reply_time")->nullable();
            $table->timestamp("closed_at")->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
