<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusReplyToTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {

            $table->unsignedBigInteger("reply_status_to_contact_id")->after("spam")->nullable();
            $table->foreign("reply_status_to_contact_id")->on("replies_status")->references("id");
            $table->unsignedBigInteger("reply_status_to_users_id")->after("spam")->nullable();
            $table->foreign("reply_status_to_users_id")->on("replies_status")->references("id");
      
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
            $table->dropForeign("reply_status_to_contact_id");
            $table->dropForeign("reply_status_to_users_id");
            $table->dropColumn(["reply_status_to_contact_id", "reply_status_to_users_id"]);
        });
    }
}
