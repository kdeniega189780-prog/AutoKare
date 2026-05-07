<?php

namespace Tests\Feature;

use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MechanicTeamAssignTest extends TestCase
{
    use RefreshDatabase;

    public function test_on_break_mechanic_cannot_be_assigned_to_task(): void
    {
        $senior = User::factory()->mechanic()->create([
            'current_status' => 'working',
            'status' => 'active',
        ]);

        $onBreakMechanic = User::factory()->mechanic()->create([
            'current_status' => 'on_break',
            'status' => 'active',
        ]);

        $vehicle = Vehicle::create([
            'user_id' => $senior->id,
            'owner_id' => $senior->id,
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2019,
            'license_plate' => 'MB-1',
            'status' => 'active',
        ]);

        $schedule = MaintenanceSchedule::create([
            'vehicle_id' => $vehicle->id,
            'scheduled_at' => Carbon::now()->addDay(),
            'task_description' => 'Oil Change',
            'status' => 'pending',
            'created_by' => $senior->id,
        ]);

        $response = $this->actingAs($senior)
            ->from(route('mechanic.teamOverview'))
            ->post(route('mechanic.tasks.assign', $schedule), [
                'mechanic_id' => $onBreakMechanic->id,
                'initial_notes' => 'Test notes',
                'estimated_minutes' => 30,
            ]);

        $response
            ->assertRedirect(route('mechanic.teamOverview'))
            ->assertSessionHasErrors(['mechanic_id']);

        $this->assertSame('pending', MaintenanceSchedule::find($schedule->id)->status);
    }

    public function test_assignment_sets_status_to_in_progress_and_started_at(): void
    {
        $senior = User::factory()->mechanic()->create([
            'current_status' => 'working',
            'status' => 'active',
        ]);

        $workingMechanic = User::factory()->mechanic()->create([
            'current_status' => 'working',
            'status' => 'active',
        ]);

        $vehicle = Vehicle::create([
            'user_id' => $senior->id,
            'owner_id' => $senior->id,
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2020,
            'license_plate' => 'MB-2',
            'status' => 'active',
        ]);

        $schedule = MaintenanceSchedule::create([
            'vehicle_id' => $vehicle->id,
            'scheduled_at' => Carbon::now()->addDay(),
            'task_description' => 'Tire Rotation',
            'status' => 'pending',
            'created_by' => $senior->id,
        ]);

        $this->actingAs($senior)
            ->from(route('mechanic.teamOverview'))
            ->post(route('mechanic.tasks.assign', $schedule), [
                'mechanic_id' => $workingMechanic->id,
                'initial_notes' => 'Assigning now',
                'estimated_minutes' => 45,
            ])
            ->assertRedirect(route('mechanic.teamOverview'));

        $schedule->refresh();

        $this->assertSame('in_progress', $schedule->status);
        $this->assertSame($workingMechanic->id, $schedule->assigned_mechanic_id);
        $this->assertSame($workingMechanic->id, $schedule->mechanic_id);
        $this->assertNotNull($schedule->started_at);
    }
}

