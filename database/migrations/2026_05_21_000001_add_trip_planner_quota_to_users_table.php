<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('trip_plan_quota')->default(2)->after('otp_expires_at');
            $table->unsignedInteger('trip_plan_generated_count')->default(0)->after('trip_plan_quota');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['trip_plan_quota', 'trip_plan_generated_count']);
        });
    }
};