<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending','contacting_owner','contacting_student','availability_confirmed','completed','cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE booking_history MODIFY COLUMN status ENUM('pending','contacting_owner','contacting_student','availability_confirmed','completed','cancelled') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending','availability_confirmed','completed','cancelled','rejected') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE booking_history MODIFY COLUMN status ENUM('pending','availability_confirmed','completed','cancelled','rejected') NOT NULL");
    }
};
