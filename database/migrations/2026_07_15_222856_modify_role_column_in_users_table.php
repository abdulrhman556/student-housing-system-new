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
        // 1. تعديل عمود الـ role باستخدام الـ DB Statement اللي كنت كاتبها
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'owner') NOT NULL");

        // 2. إضافة عمود الـ university_id وربطه بجدول الجامعات
        Schema::table('users', function (Blueprint $table) {
            // بنخليه nullable عشان الأونر مش هيختار جامعة، ويكون مكانه بعد الـ role
            $table->foreignId('university_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('universities')
                  ->onDelete('set null'); // لو الجامعة اتمسحت، الطالب يفضل موجود والعمود يبقى null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // 1. حذف الربط والعمود في حالة الـ Rollback
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropColumn('university_id');
        });

        // 2. إرجاع عمود الـ role لحالته القديمة
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'owner', 'admin') NOT NULL");
    }
};
