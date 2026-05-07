<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // VEHICLES: ERD expects owner_id + status. Keep existing user_id for backward compatibility.
        Schema::table('vehicles', function (Blueprint $table) {
            if (! Schema::hasColumn('vehicles', 'owner_id')) {
                $table->foreignId('owner_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('vehicles', 'status')) {
                $table->string('status', 32)->default('active')->after('license_plate');
            }
        });

        if (Schema::hasColumn('vehicles', 'user_id') && Schema::hasColumn('vehicles', 'owner_id')) {
            DB::table('vehicles')->whereNull('owner_id')->update(['owner_id' => DB::raw('user_id')]);
        }

        // MAINTENANCE_SCHEDULES: ERD expects maintenance_task with assigned_mechanic_id, created_by, scheduled_date, description.
        // Keep existing columns (mechanic_id, scheduled_at, task_description) so current UI continues working.
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('maintenance_schedules', 'assigned_mechanic_id')) {
                $table->foreignId('assigned_mechanic_id')->nullable()->after('mechanic_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('maintenance_schedules', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('vehicle_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('maintenance_schedules', 'scheduled_date')) {
                $table->date('scheduled_date')->nullable()->after('scheduled_at');
            }

            if (! Schema::hasColumn('maintenance_schedules', 'description')) {
                $table->string('description', 500)->nullable()->after('task_description');
            }
        });

        if (Schema::hasColumn('maintenance_schedules', 'mechanic_id') && Schema::hasColumn('maintenance_schedules', 'assigned_mechanic_id')) {
            DB::table('maintenance_schedules')->whereNull('assigned_mechanic_id')->update(['assigned_mechanic_id' => DB::raw('mechanic_id')]);
        }

        if (Schema::hasColumn('maintenance_schedules', 'task_description') && Schema::hasColumn('maintenance_schedules', 'description')) {
            DB::table('maintenance_schedules')->whereNull('description')->update(['description' => DB::raw('task_description')]);
        }

        // SERVICE_RECORDS: ERD expects task_id (FK), service_date, odometer, labor_rate, labor_cost.
        // Keep existing maintenance_schedule_id and cost columns to avoid breaking current forms.
        Schema::table('service_records', function (Blueprint $table) {
            if (! Schema::hasColumn('service_records', 'task_id')) {
                $table->foreignId('task_id')->nullable()->after('id')->constrained('maintenance_schedules')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('service_records', 'service_date')) {
                $table->date('service_date')->nullable()->after('task_id');
            }

            if (! Schema::hasColumn('service_records', 'odometer')) {
                $table->unsignedInteger('odometer')->nullable()->after('service_date');
            }

            if (! Schema::hasColumn('service_records', 'labor_rate')) {
                $table->decimal('labor_rate', 10, 2)->nullable()->after('labor_hours');
            }
        });

        if (Schema::hasColumn('service_records', 'maintenance_schedule_id') && Schema::hasColumn('service_records', 'task_id')) {
            DB::table('service_records')->whereNull('task_id')->update(['task_id' => DB::raw('maintenance_schedule_id')]);
        }

        // PARTS + SERVICE_PARTS (pivot): aligns with ERD (PART, SERVICE_PART).
        if (! Schema::hasTable('parts')) {
            Schema::create('parts', function (Blueprint $table) {
                $table->id();
                $table->string('part_number', 64)->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('unit_price', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('service_parts')) {
            Schema::create('service_parts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_id')->constrained('service_records')->cascadeOnDelete();
                $table->foreignId('part_id')->constrained('parts')->cascadeOnDelete();
                $table->unsignedInteger('quantity_used')->default(1);
                $table->decimal('unit_price_at_time', 10, 2)->default(0);
                $table->decimal('line_total', 10, 2)->default(0);
                $table->timestamps();

                $table->unique(['service_id', 'part_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_parts');
        Schema::dropIfExists('parts');

        Schema::table('service_records', function (Blueprint $table) {
            if (Schema::hasColumn('service_records', 'task_id')) {
                $table->dropConstrainedForeignId('task_id');
            }
            $table->dropColumn(array_values(array_filter([
                Schema::hasColumn('service_records', 'service_date') ? 'service_date' : null,
                Schema::hasColumn('service_records', 'odometer') ? 'odometer' : null,
                Schema::hasColumn('service_records', 'labor_rate') ? 'labor_rate' : null,
            ])));
        });

        Schema::table('maintenance_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('maintenance_schedules', 'assigned_mechanic_id')) {
                $table->dropConstrainedForeignId('assigned_mechanic_id');
            }
            if (Schema::hasColumn('maintenance_schedules', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }
            $table->dropColumn(array_values(array_filter([
                Schema::hasColumn('maintenance_schedules', 'scheduled_date') ? 'scheduled_date' : null,
                Schema::hasColumn('maintenance_schedules', 'description') ? 'description' : null,
            ])));
        });

        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'owner_id')) {
                $table->dropConstrainedForeignId('owner_id');
            }
            if (Schema::hasColumn('vehicles', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};

