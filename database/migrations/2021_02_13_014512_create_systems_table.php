<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("url_production")->nullable();
            $table->string("url_qa")->nullable();
            $table->string("url_admin")->nullable();
            $table->string("url_customers")->nullable();
            $table->boolean("active")->default(0);
            $table->boolean("app_mobile")->default(0);
            $table->string("link_download_app")->nullable();
            $table->boolean("backup")->default(0);
            $table->foreignId("customer_id")->nullable()->constrained();
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
        Schema::dropIfExists('systems');
    }
}
