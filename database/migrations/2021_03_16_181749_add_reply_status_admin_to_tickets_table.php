<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReplyStatusAdminToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // $table->string("reply_status_to_internal", 100)->after("reply_status_to_contact")->nullable();
            $table->unsignedBigInteger("last_replied_internal_user_id")->after("reply_status_to_contact")->nullable();
            $table->foreign("last_replied_internal_user_id")->on("users")->references("id");
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
            $table->dropForeign("last_replied_internal_user_id");
            $table->dropColumn(["last_replied_internal_user_id"]);
        });
    }
}
