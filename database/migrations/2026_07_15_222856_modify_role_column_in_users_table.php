<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // 👈 ضفنا الـ DB عشان الـ Statement تشتغل صح

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'owner') NOT NULL");
        }

        if (!Schema::hasColumn('users', 'university_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('university_id')
                      ->nullable()
                      ->after('role')
                      ->constrained('universities')
                      ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'university_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['university_id']);
                $table->dropColumn('university_id');
            });
        }

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'owner', 'admin') NOT NULL");
        }
    }
};
