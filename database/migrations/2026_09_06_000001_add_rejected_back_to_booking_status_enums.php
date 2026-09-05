<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending','contacting_owner','contacting_student','availability_confirmed','completed','cancelled','rejected') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE booking_history MODIFY COLUMN status ENUM('pending','contacting_owner','contacting_student','availability_confirmed','completed','cancelled','rejected') NOT NULL");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending','contacting_owner','contacting_student','availability_confirmed','completed','cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE booking_history MODIFY COLUMN status ENUM('pending','contacting_owner','contacting_student','availability_confirmed','completed','cancelled') NOT NULL");
    }
};
