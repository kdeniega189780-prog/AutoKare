<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('maintenance_schedules', 'required_parts')) {
                // Per-task required parts: array of ['name' => string, 'qty' => string].
                $table->json('required_parts')->nullable()->after('initial_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('maintenance_schedules', 'required_parts')) {
                $table->dropColumn('required_parts');
            }
        });
    }
};
