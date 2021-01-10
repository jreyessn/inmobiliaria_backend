<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsMortalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits_mortalities', function (Blueprint $table) {
            $table->id();
            $table->integer('building');
            $table->float('mort_acum', 8, 2)->default(0);
            $table->float('mort_current_week', 8, 2)->default(0);
            $table->float('pigs_age', 8, 2)->default(0);
            $table->float('pigs_fever', 8, 2)->default(0);
            $table->string('activity', 50);
            $table->float('cought', 8, 2)->default(0);
            $table->float('diarrhea', 8, 2)->default(0);
            $table->float('pigs_treated_day', 8, 2)->default(0);
            $table->foreignId('visit_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits_mortalities');
    }
}
