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
        Schema::create('connector_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 256)->unique();
            $table->integer('max_voltage')->unsigned();
            $table->integer('max_current')->unsigned();
            $table->integer('max_watts')->unsigned();
            $table->enum('current_type', ['AC', 'DC']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connector_types');
    }
};
