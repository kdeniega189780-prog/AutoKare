<?php

namespace Tests\Feature;

use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleAndScheduleFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_vehicle_then_schedule_maintenance(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $mechanic = User::factory()->mechanic()->create();

        $this->actingAs($owner)->post(route('vehicles.store'), [
            'make' => 'Ford',
            'model' => 'F-150',
            'year' => 2019,
            'license_plate' => 'FLOW-001',
            'vin' => null,
            'notes' => 'Integration test vehicle',
        ])->assertSessionHasNoErrors()
            ->assertRedirect(route('vehicles.index'));

        $vehicle = Vehicle::query()->where('license_plate', 'FLOW-001')->first();
        $this->assertNotNull($vehicle);
        $this->assertSame($owner->id, $vehicle->user_id);

        $this->actingAs($owner)->post(route('schedules.store'), [
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'scheduled_at' => now()->addDays(3)->format('Y-m-d\TH:i'),
            'task_description' => 'Oil change',
        ])->assertSessionHasNoErrors()
            ->assertRedirect(route('schedules.index'));

        $this->assertDatabaseHas('maintenance_schedules', [
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'task_description' => 'Oil change',
        ]);
    }

    public function test_owner_cannot_schedule_maintenance_for_another_owners_vehicle(): void
    {
        $alice = User::factory()->create(['role' => 'owner']);
        $bob = User::factory()->create(['role' => 'owner']);
        $mechanic = User::factory()->mechanic()->create();

        $bobsVehicle = Vehicle::create([
            'user_id' => $bob->id,
            'make' => 'Mazda',
            'model' => '3',
            'year' => 2018,
            'license_plate' => 'BOB-ONLY',
        ]);

        $this->actingAs($alice)->post(route('schedules.store'), [
            'vehicle_id' => $bobsVehicle->id,
            'mechanic_id' => $mechanic->id,
            'scheduled_at' => now()->addDay()->format('Y-m-d\TH:i'),
            'task_description' => 'Unauthorized',
        ])->assertForbidden();

        $this->assertSame(0, MaintenanceSchedule::where('vehicle_id', $bobsVehicle->id)->count());
    }
}
