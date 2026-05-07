<?php

namespace Tests\Feature;

use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAppointmentsBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_book_appointment_using_modal_payload(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'status' => 'active',
        ]);

        $vehicle = Vehicle::create([
            'user_id' => $owner->id,
            'owner_id' => $owner->id,
            'make' => 'Toyota',
            'model' => 'Vios',
            'year' => 2018,
            'license_plate' => 'BOOK-1',
        ]);

        $payload = [
            'vehicle_id' => $vehicle->id,
            'task_description' => 'Oil Change',
            'scheduled_date' => now()->addDays(2)->format('Y-m-d'),
            'scheduled_time' => '09:00',
            'payment_method' => 'pay_at_shop',
            'description' => 'Customer modal booking test',
        ];

        $this->actingAs($owner)
            ->post(route('schedules.store'), $payload)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('customer.appointments'));

        $this->assertSame(1, MaintenanceSchedule::count());
        $this->assertDatabaseHas('maintenance_schedules', [
            'vehicle_id' => $vehicle->id,
            'task_description' => 'Oil Change',
            'status' => 'pending',
        ]);
    }
}

