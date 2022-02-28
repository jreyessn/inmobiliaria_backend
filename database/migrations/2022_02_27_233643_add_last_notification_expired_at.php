<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastNotificationExpiredAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->timestamp("last_notification_expired_at")->nullable()->after("maintenance_limit_at");
        });
        Schema::table('permissions_vehicles', function (Blueprint $table) {
            $table->timestamp("last_notification_expired_at")->nullable()->after("expiration_at");
        });
        Schema::table('license_plates', function (Blueprint $table) {
            $table->timestamp("last_notification_expired_at")->nullable()->after("expiration_at");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(["last_notification_expired_at"]);
        });
        
        Schema::table('permissions_vehicles', function (Blueprint $table) {
            $table->dropColumn(["last_notification_expired_at"]);
        });

        Schema::table('license_plates', function (Blueprint $table) {
            $table->dropColumn(["last_notification_expired_at"]);
        });

    }
}
