<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChannelToTicketsMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets_messages', function (Blueprint $table) {
            $table->string("channel", 100)->nullable()->after("cc");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets_messages', function (Blueprint $table) {
            $table->dropColumn(["channel"]);
        });
    }
}
