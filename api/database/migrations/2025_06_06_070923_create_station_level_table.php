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
        Schema::create('station_level', function (Blueprint $table) {
            $table->id();
            $table->integer(column: 'level', unsigned: false);
            $table->integer(column:'minimum_output', unsigned: false);
            $table->integer(column:'maximum_output', unsigned: false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_levels');
    }
};
