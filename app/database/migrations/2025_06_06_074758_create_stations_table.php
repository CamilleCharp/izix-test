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
        Schema::create('stations', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->timestamps();
            $table->timestamp('last_used_at');
            $table->integer('spot');
            $table->foreignId('type_id')->references('id')->on('station_types');
            $table->foreignUuid('location_uuid')->references('uuid')->on('locations')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
