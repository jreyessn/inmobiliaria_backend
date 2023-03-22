<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BranchOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id();
            $table->string("name", 150);
            $table->timestamps();
            $table->softDeletes();
        });        
        
        Schema::create('model_has_branch_offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("branch_office_id")->constrained();
            $table->morphs("model");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_branch_offices');
        Schema::dropIfExists('branch_offices');
    }
}
