<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies_status', function (Blueprint $table) {
            $table->id();
            $table->string("description", 100);
            $table->string("background_color", 100);
            $table->string("border_color", 100)->nullable();
            $table->string("color", 100)->default('#000');
            $table->boolean("show_in_list")->default(1);
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
        Schema::dropIfExists('replies_status');
    }
}
