<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('charging_sessions', function (Blueprint $table) {
            $table->timestamp('started_at')->useCurrent()->change();
            $table->timestamp('last_status_update')->useCurrentOnUpdate()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('charging_sessions', function (Blueprint $table) {
            $table->timestamp('started_at')->default(Carbon::createFromTimestamp(0))->change();
            $table->timestamp('last_status_update')->default(Carbon::createFromTimestamp(0))->change();
        });
    }
};
