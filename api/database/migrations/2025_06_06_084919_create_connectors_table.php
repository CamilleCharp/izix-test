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
        Schema::create('connectors', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->timestamps();
            $table->timestamp('last_used');
            $table->foreignUuid('station_uuid')->references('uuid')->on('stations');
            $table->foreignId('type_id')->references('id')->on('connector_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connectors');
    }
};
