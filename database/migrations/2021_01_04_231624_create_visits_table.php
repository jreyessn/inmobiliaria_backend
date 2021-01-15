<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('cost_center', 200)->nullable();
            $table->float('result', 8, 2)->default(0);
            $table->text('comment', 8, 2)->nullable();
            $table->timestamp('date')->nullable();
            $table->foreignId('user_id')->constrained()->comment("Supervisor");
            $table->foreignId('farm_id')->constrained()->comment("Granja");
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
        Schema::dropIfExists('visits');
    }
}
