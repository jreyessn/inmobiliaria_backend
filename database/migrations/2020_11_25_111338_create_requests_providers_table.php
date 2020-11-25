<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_providers', function (Blueprint $table) {
            $table->id();
            $table->string('reason', 255);
            $table->string('type_provider', 100);
            $table->string("tradename")->comment("Nombre sugerido");
            $table->string("business_name");
            $table->string("name_contact");
            $table->string("phone_provider");
            $table->string("email_provider");
            $table->string("fullname_applicant");
            $table->string("email_applicant");
            $table->string("microbusiness");
            $table->string("authorization_file")->comment("Autorización Director de Área");
            $table->foreignId('user_id')->nullable()->constrained();

            $table->boolean("status")->default(0)->comment("0 espera, 1 aprobado, 2 rechazado");
            $table->text("note")->nullable()->comment("Motivo de aprobacion/rechazo");
            $table->timestamp('approved_at')->nullable();
            $table->bigInteger('approver_by_user_id')->unsigned()->nullable();
            $table->foreign("approver_by_user_id")
                  ->nullable()
                  ->references('id')
                  ->on('users')
                  ->comment("Usuario que aprobó");

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
        Schema::dropIfExists('applicant_providers');
    }
}
