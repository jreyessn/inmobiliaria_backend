<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttendedByTicketsToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger("attended_by_user_id")->after("user_id")->nullable();
            $table->foreign("attended_by_user_id")->on("users")->references("id");
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
            $table->dropForeign([ "attended_by_user_id" ]);
            $table->dropColumn([ "attended_by_user_id" ]);
        });
    }
}
