<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('property_type', 30)->nullable()->after('title');
            $table->unsignedSmallInteger('rooms')->nullable()->after('property_type');
            $table->unsignedSmallInteger('bathrooms')->nullable()->after('rooms');
            $table->smallInteger('floor')->nullable()->after('bathrooms');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['property_type', 'rooms', 'bathrooms', 'floor']);
        });
    }
};
