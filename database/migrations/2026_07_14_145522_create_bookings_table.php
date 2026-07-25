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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('users');
            $table->foreignId('unit_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('admins')
                ->cascadeOnDelete();
            $table->enum('status', [
                'pending',
                'contacting_owner',
                'contacting_student',
                'availability_confirmed',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->date('booking_date');
            $table->date('check_in_date');
            $table->timestamps();
        });
    }
    /**


    * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
