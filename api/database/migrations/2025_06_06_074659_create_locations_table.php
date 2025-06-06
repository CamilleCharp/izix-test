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
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid(column: 'uuid')->primary();
            $table->timestamps();
            $table->string(column: 'name', length: 256)->unique();
            $table->geography(column: 'coordinate', subtype: 'point');
            $table->integer(column: 'capacity', unsigned: true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
