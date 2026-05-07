<?php

namespace Tests\Feature;

use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_protected_routes(): void
    {
        $this->get(route('vehicles.index'))->assertRedirect(route('login'));
        $this->get(route('schedules.index'))->assertRedirect(route('login'));
        $this->get(route('reports.index'))->assertRedirect(route('login'));
    }

    public function test_mechanic_cannot_access_reports(): void
    {
        $user = User::factory()->mechanic()->create();

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertForbidden();
    }

    public function test_mechanic_cannot_access_admin_user_management(): void
    {
        $user = User::factory()->mechanic()->create();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_owner_can_access_reports(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk();
    }

    public function test_owner_cannot_access_admin_user_management(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_reports_and_user_management(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertOk();
    }

    public function test_mechanic_cannot_open_vehicle_create_form(): void
    {
        $user = User::factory()->mechanic()->create();

        $this->actingAs($user)
            ->get(route('vehicles.create'))
            ->assertForbidden();
    }

    public function test_owner_can_open_vehicle_create_form(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        $this->actingAs($user)
            ->get(route('vehicles.create'))
            ->assertOk();
    }

    public function test_mechanic_cannot_open_schedule_create_form(): void
    {
        $user = User::factory()->mechanic()->create();

        $this->actingAs($user)
            ->get(route('schedules.create'))
            ->assertForbidden();
    }

    public function test_owner_can_open_schedule_create_form(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        $this->actingAs($user)
            ->get(route('schedules.create'))
            ->assertOk();
    }

    public function test_owner_cannot_open_service_record_edit(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $mechanic = User::factory()->mechanic()->create();
        $schedule = $this->makeScheduleForOwner($owner, $mechanic);

        $this->actingAs($owner)
            ->get(route('service-records.edit', $schedule))
            ->assertForbidden();
    }

    public function test_mechanic_cannot_open_service_record_for_job_assigned_to_someone_else(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $assigned = User::factory()->mechanic()->create();
        $other = User::factory()->mechanic()->create();
        $schedule = $this->makeScheduleForOwner($owner, $assigned);

        $this->actingAs($other)
            ->get(route('service-records.edit', $schedule))
            ->assertForbidden();
    }

    public function test_mechanic_can_open_service_record_for_assigned_job(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $mechanic = User::factory()->mechanic()->create();
        $schedule = $this->makeScheduleForOwner($owner, $mechanic);

        $this->actingAs($mechanic)
            ->get(route('service-records.edit', $schedule))
            ->assertOk();
    }

    public function test_admin_can_open_service_record_edit(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $mechanic = User::factory()->mechanic()->create();
        $schedule = $this->makeScheduleForOwner($owner, $mechanic);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('service-records.edit', $schedule))
            ->assertOk();
    }

    public function test_mechanic_cannot_export_reports_csv(): void
    {
        $user = User::factory()->mechanic()->create();

        $this->actingAs($user)
            ->get(route('reports.export'))
            ->assertForbidden();
    }

    public function test_owner_can_export_reports_csv(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($user)
            ->get(route('reports.export'));

        $response->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->assertStringStartsWith("\xEF\xBB\xBF", $response->streamedContent());
    }

    public function test_admin_can_export_reports_csv(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->get(route('reports.export'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_mechanic_cannot_store_new_schedule_via_post(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $mechanic = User::factory()->mechanic()->create();
        $vehicle = Vehicle::create([
            'user_id' => $owner->id,
            'make' => 'Subaru',
            'model' => 'Outback',
            'year' => 2021,
            'license_plate' => 'RBAC-POST',
        ]);

        $this->actingAs($mechanic)->post(route('schedules.store'), [
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'scheduled_at' => now()->addDay()->format('Y-m-d\TH:i'),
            'task_description' => 'Should be rejected',
        ])->assertForbidden();

        $this->assertDatabaseMissing('maintenance_schedules', [
            'task_description' => 'Should be rejected',
        ]);
    }

    private function makeScheduleForOwner(User $owner, User $mechanic): MaintenanceSchedule
    {
        $vehicle = Vehicle::create([
            'user_id' => $owner->id,
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2020,
            'license_plate' => 'TEST-'.substr((string) random_int(1000, 9999), 0, 4),
        ]);

        return MaintenanceSchedule::create([
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'scheduled_at' => now()->addDay(),
            'task_description' => 'Brake check',
            'status' => 'pending',
        ]);
    }
}
