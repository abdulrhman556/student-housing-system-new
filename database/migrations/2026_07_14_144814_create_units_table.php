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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')
                ->constrained()
                ->restrictOnDelete();
            $table->enum('unit_type', ['apartment', 'room', 'bed']);
            $table->string('title', 150);
            $table->decimal('price', 10, 2);
            $table->integer('capacity');
            $table->integer('available_count');
            $table->enum('gender', ['male', 'female']);
            $table->enum('status', ['available', 'reserved', 'occupied'])
                ->default('available');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['property_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
