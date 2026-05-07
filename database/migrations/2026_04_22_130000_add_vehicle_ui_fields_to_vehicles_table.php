<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('type', 50)->nullable()->after('license_plate');
            $table->unsignedInteger('mileage')->nullable()->after('type');
            $table->string('color', 50)->nullable()->after('mileage');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['type', 'mileage', 'color']);
        });
    }
};

