<?php

namespace Tests\Feature;

use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScheduleUpdateRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_put_schedule_update_redirects_to_admin_appointments_index(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $owner = User::factory()->create([
            'role' => 'owner',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $vehicle = Vehicle::create([
            'user_id' => $owner->id,
            'owner_id' => $owner->id,
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2020,
            'license_plate' => 'REDIR-1',
        ]);
        $schedule = MaintenanceSchedule::create([
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => null,
            'assigned_mechanic_id' => null,
            'created_by' => $admin->id,
            'scheduled_at' => now()->addDay(),
            'scheduled_date' => now()->addDay()->toDateString(),
            'task_description' => 'Oil change',
            'status' => 'pending',
            'priority' => 'normal',
            'task_type' => 'oil_change',
        ]);

        $newTime = now()->addDays(3)->format('Y-m-d H:i:s');

        $this->actingAs($admin)->put(route('schedules.update', $schedule), [
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => null,
            'scheduled_at' => $newTime,
            'task_description' => 'Full synthetic service',
            'status' => 'in_progress',
            'priority' => 'high',
        ])
            ->assertRedirect(route('admin.appointments.index'))
            ->assertSessionHas('status', 'Schedule updated.');

        $this->assertDatabaseHas('maintenance_schedules', [
            'id' => $schedule->id,
            'task_description' => 'Full synthetic service',
            'status' => 'in_progress',
            'priority' => 'high',
        ]);
    }
}
