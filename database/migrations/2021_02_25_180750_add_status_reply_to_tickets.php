<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusReplyToTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->enum("reply_status_to_contact", ["Soporte ha abierto el Ticket" ,'Soporte ha respondido', 'Has respondido'])->after("spam")->nullable();
            $table->enum("reply_status_to_users", ["Cliente ha abierto el Ticket", "Cliente ha respondido", 'Respondido'])->after("spam")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(["reply_status_to_contact", "reply_status_to_users"]);
        });
    }
}
