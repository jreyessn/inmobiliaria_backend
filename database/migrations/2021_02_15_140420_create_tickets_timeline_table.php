<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTimelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_timeline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("made_by_user")->nullable();
            $table->foreign("made_by_user")->references('id')->on('users')->constrained();
            $table->text("note")->nullable();
            $table->unsignedBigInteger("assigned_to_user_id")->nullable();
            $table->foreign("assigned_to_user_id")->references('id')->on('users')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_timeline');
    }
}
