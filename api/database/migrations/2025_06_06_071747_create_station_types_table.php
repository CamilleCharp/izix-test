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
        Schema::create('station_types', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
            $table->foreignId('station_level_id')->references('id')->on('station_levels');
            $table->enum(column: 'current', allowed: ['AC', 'DC']);
            $table->integer(column: 'power');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'station_types');
    }
};
