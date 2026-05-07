<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status', 16)->default('active')->after('role');
            }
            if (! Schema::hasColumn('users', 'current_status')) {
                // Mechanic-only working state shown on Team Overview.
                $table->string('current_status', 16)->default('off_duty')->after('status');
            }
            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('current_status');
            }
        });

        Schema::table('maintenance_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('maintenance_schedules', 'started_at')) {
                $table->dateTime('started_at')->nullable()->after('scheduled_at');
            }
            if (! Schema::hasColumn('maintenance_schedules', 'completed_at')) {
                $table->dateTime('completed_at')->nullable()->after('started_at');
            }
            if (! Schema::hasColumn('maintenance_schedules', 'estimated_minutes')) {
                $table->unsignedSmallInteger('estimated_minutes')->nullable()->after('completed_at');
            }
            if (! Schema::hasColumn('maintenance_schedules', 'task_type')) {
                $table->string('task_type', 64)->default('general')->after('estimated_minutes');
            }
            if (! Schema::hasColumn('maintenance_schedules', 'service_instructions')) {
                $table->text('service_instructions')->nullable()->after('task_type');
            }
            if (! Schema::hasColumn('maintenance_schedules', 'initial_notes')) {
                $table->text('initial_notes')->nullable()->after('service_instructions');
            }
            if (! Schema::hasColumn('maintenance_schedules', 'progress_notes')) {
                $table->text('progress_notes')->nullable()->after('initial_notes');
            }
            if (! Schema::hasColumn('maintenance_schedules', 'payment_method')) {
                $table->string('payment_method', 32)->nullable()->after('progress_notes');
            }
        });

        Schema::table('service_records', function (Blueprint $table) {
            if (! Schema::hasColumn('service_records', 'work_summary')) {
                $table->text('work_summary')->nullable()->after('parts_used');
            }
            if (! Schema::hasColumn('service_records', 'recommendations')) {
                $table->text('recommendations')->nullable()->after('work_summary');
            }
        });

        if (Schema::hasTable('parts')) {
            Schema::table('parts', function (Blueprint $table) {
                if (! Schema::hasColumn('parts', 'brand')) {
                    $table->string('brand', 64)->nullable()->after('name');
                }
                if (! Schema::hasColumn('parts', 'oem_number')) {
                    $table->string('oem_number', 64)->nullable()->after('brand');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('parts')) {
            Schema::table('parts', function (Blueprint $table) {
                foreach (['brand', 'oem_number'] as $col) {
                    if (Schema::hasColumn('parts', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        Schema::table('service_records', function (Blueprint $table) {
            foreach (['work_summary', 'recommendations'] as $col) {
                if (Schema::hasColumn('service_records', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('maintenance_schedules', function (Blueprint $table) {
            foreach ([
                'started_at',
                'completed_at',
                'estimated_minutes',
                'task_type',
                'service_instructions',
                'initial_notes',
                'progress_notes',
                'payment_method',
            ] as $col) {
                if (Schema::hasColumn('maintenance_schedules', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('users', function (Blueprint $table) {
            foreach (['status', 'current_status', 'last_login_at'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
