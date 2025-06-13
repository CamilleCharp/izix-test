<?php

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
        Schema::create('charging_sessions', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->timestamp('started_at');
            $table->timestamp('last_status_update');
            $table->integer('starting_battery_percent');
            $table->integer('current_battery_percent');
            $table->foreignUuid('vehicle_uuid')->references('uuid')->on('vehicles');
            $table->foreignUuid('connector_uuid')->references('uuid')->on('connectors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charging_sessions');
    }
};
