<?php

namespace Tests\Feature;

use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreventDuplicateCustomerBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_cannot_create_second_active_appointment_for_same_vehicle(): void
    {
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
            'license_plate' => 'DUP-1',
        ]);

        // First booking succeeds
        $this->actingAs($owner)->post(route('schedules.store'), [
            'vehicle_id' => $vehicle->id,
            'task_description' => 'Oil Change',
            'scheduled_date' => now()->addDays(1)->format('Y-m-d'),
            'scheduled_time' => '09:00',
            'payment_method' => 'pay_at_shop',
            'description' => 'First booking',
        ])->assertRedirect(route('customer.appointments'));

        $this->assertSame(1, MaintenanceSchedule::count());

        // Second booking for same vehicle should fail validation due to active appointment guard
        $response = $this->actingAs($owner)->from(route('customer.vehicles'))->post(route('schedules.store'), [
            'vehicle_id' => $vehicle->id,
            'task_description' => 'Brake Inspection',
            'scheduled_date' => now()->addDays(2)->format('Y-m-d'),
            'scheduled_time' => '10:00',
            'payment_method' => 'pay_at_shop',
            'description' => 'Second booking attempt',
        ]);

        $response
            ->assertRedirect(route('customer.vehicles'))
            ->assertSessionHasErrors(['vehicle_id']);

        $this->assertSame(1, MaintenanceSchedule::count());
    }
}

