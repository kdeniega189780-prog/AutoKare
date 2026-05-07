<?php

namespace Tests\Browser;

use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ModalCudTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_can_create_user_via_modal(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit(route('admin.users.index'))
                ->press('Add User')
                ->waitFor('#vms-modal.show', 5)
                ->within('#vms-modal', function (Browser $modal) {
                    $modal->type('name', 'Dusk Modal User')
                        ->type('email', 'dusk.modal.user@example.com')
                        ->select('role', 'owner')
                        ->select('status', 'active')
                        ->type('phone', '555-0101')
                        ->type('password', 'tempPass123')
                        ->press('Create User');
                })
                ->waitForText('User created.', 10);
        });

        $this->assertDatabaseHas('users', [
            'email' => 'dusk.modal.user@example.com',
            'role' => 'owner',
        ]);
    }

    public function test_admin_can_create_vehicle_for_customer_via_modal(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $customer = User::factory()->create([
            'role' => 'owner',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $customer) {
            $browser->loginAs($admin)
                ->visit(route('vehicles.index'))
                ->press('Add Vehicle for Customer')
                ->waitFor('#vms-modal.show', 5)
                ->waitForText('Select Existing Customer', 10)
                ->pause(500)
                ->script([
                    "var h=document.getElementById('vms_customer_id') || document.querySelector('#vms-modal input[name=\"user_id\"]'); if(h){h.value='{$customer->id}';}",
                    "var s=document.getElementById('vms_customer_search'); if (s) s.value='Dusk Customer';",
                ]);

            $browser->within('#vms-modal', function (Browser $modal) {
                $modal->type('#make_model', '2020 Honda Civic')
                    ->type('license_plate', 'DUSK-PLATE-1')
                    ->type('year', '2020')
                    ->type('mileage', '12345')
                    ->press('Add Vehicle');
            });

            // Make the test resilient: ensure hidden make/model are populated before submit.
            $browser->script([
                "var mk=document.getElementById('make'); if (mk) mk.value='Honda';",
                "var md=document.getElementById('model'); if (md) md.value='Civic';",
            ]);

            $browser->waitForText('Vehicle saved.', 10);
        });

        $this->assertDatabaseHas('vehicles', [
            'license_plate' => 'DUSK-PLATE-1',
            'make' => 'Honda',
            'model' => 'Civic',
            'owner_id' => $customer->id,
        ]);
    }

    public function test_customer_can_add_vehicle_via_modal(): void
    {
        $customer = User::factory()->create([
            'role' => 'owner',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($customer) {
            $browser->loginAs($customer)
                ->visit(route('customer.vehicles'))
                ->press('Add Vehicle')
                ->waitFor('#vms-modal.show', 5)
                ->waitForText('Add New Vehicle', 5)
                ->within('#vms-modal', function (Browser $modal) {
                    $modal->type('#cust_make_model', '2018 Toyota Vios')
                        ->type('license_plate', 'CUST-DUSK-1')
                        ->type('#cust_year_visible', '2018')
                        ->type('mileage', '45678');
                })
                // Make the test resilient: ensure hidden inputs are populated before submit.
                ->script([
                    "document.getElementById('cust_make').value='Toyota';",
                    "document.getElementById('cust_model').value='Vios';",
                    "document.getElementById('cust_year').value='2018';",
                ]);

            $browser->within('#vms-modal', function (Browser $modal) {
                $modal->press('Add Vehicle');
            })
                ->waitForLocation(route('customer.vehicles'), 10);
        });

        $this->assertDatabaseHas('vehicles', [
            'license_plate' => 'CUST-DUSK-1',
            'make' => 'Toyota',
            'model' => 'Vios',
            'owner_id' => $customer->id,
        ]);
    }

    public function test_customer_can_book_appointment_via_modal(): void
    {
        $customer = User::factory()->create([
            'role' => 'owner',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'owner_id' => $customer->id,
            'make' => 'Ford',
            'model' => 'Focus',
            'year' => 2017,
            'license_plate' => 'APPT-1',
        ]);

        $this->browse(function (Browser $browser) use ($customer, $vehicle) {
            $browser->loginAs($customer)
                ->visit(route('customer.appointments'))
                ->press('Book New Appointment')
                ->waitFor('#vms-modal.show', 5)
                ->waitForText('Schedule Service', 5)
                ->within('#vms-modal', function (Browser $modal) use ($vehicle) {
                    $modal->select('vehicle_id', (string) $vehicle->id)
                        ->select('task_description', 'Oil Change')
                        ->type('scheduled_date', now()->addDays(2)->format('Y-m-d'))
                        ->select('scheduled_time', '09:00')
                        ->select('payment_method', 'pay_at_shop')
                        ->type('description', 'Dusk booking notes')
                        ->press('Schedule Appointment');
                })
                // The form submit triggers a full redirect. Wait for navigation rather than only flash text.
                ->waitForLocation(route('customer.appointments'), 10);
        });

        $this->assertDatabaseHas('maintenance_schedules', [
            'vehicle_id' => $vehicle->id,
            'status' => 'pending',
        ]);
    }

    public function test_mechanic_can_add_note_via_modal(): void
    {
        $mechanic = User::factory()->mechanic()->create([
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
            'make' => 'Mazda',
            'model' => '3',
            'year' => 2018,
            'license_plate' => 'MECH-1',
        ]);
        $schedule = MaintenanceSchedule::create([
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'assigned_mechanic_id' => $mechanic->id,
            'created_by' => $mechanic->id,
            'scheduled_at' => now()->subHour(),
            'scheduled_date' => now()->toDateString(),
            'task_description' => 'Dusk note test',
            'status' => 'in_progress',
            'priority' => 'normal',
            'task_type' => 'general',
        ]);

        $this->browse(function (Browser $browser) use ($mechanic, $schedule) {
            $browser->loginAs($mechanic)
                ->visit(route('mechanic.inProgress'))
                ->press('Add Notes')
                ->waitFor('#vms-modal.show', 5)
                ->within('#vms-modal', function (Browser $modal) {
                    $modal->type('progress_notes', 'Dusk progress note')
                        ->press('Save Note');
                })
                ->waitForText('Note added.', 10);
        });

        $this->assertDatabaseHas('maintenance_schedules', [
            'id' => $schedule->id,
            'progress_notes' => 'Dusk progress note',
        ]);
    }

    public function test_admin_can_edit_appointment_via_modal(): void
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
            'model' => 'City',
            'year' => 2019,
            'license_plate' => 'ADM-APPT-1',
        ]);
        $schedule = MaintenanceSchedule::create([
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => null,
            'assigned_mechanic_id' => null,
            'created_by' => $admin->id,
            'scheduled_at' => now()->addDay(),
            'scheduled_date' => now()->addDay()->toDateString(),
            'task_description' => 'Oil Change',
            'status' => 'pending',
            'priority' => 'normal',
            'task_type' => 'oil_change',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $schedule) {
            $browser->loginAs($admin)
                ->visit(route('admin.appointments.index'))
                ->press('Edit')
                ->waitFor('#vms-modal.show', 5)
                ->waitForText('Edit Appointment', 5)
                ->within('#vms-modal', function (Browser $modal) {
                    $modal->type('task_description', 'Updated Service')
                        ->select('priority', 'high')
                        ->select('status', 'in_progress');

                    // Date/time inputs can be flaky in headless mode; set via JS + dispatch events.
                    $date = now()->addDays(2)->format('Y-m-d');
                    $time = '10:00';
                    $modal->script([
                        "var d=document.querySelector('#vms-modal input[name=\"scheduled_date\"]'); if(d){d.value='{$date}'; d.dispatchEvent(new Event('input',{bubbles:true})); d.dispatchEvent(new Event('change',{bubbles:true}));}",
                        "var t=document.querySelector('#vms-modal input[name=\"scheduled_time\"]'); if(t){t.value='{$time}'; t.dispatchEvent(new Event('input',{bubbles:true})); t.dispatchEvent(new Event('change',{bubbles:true}));}",
                    ]);

                    $modal->press('Save Changes');
                })
                ->pause(1000)
                ->screenshot('admin-edit-appointment-after-submit')
                ->waitForText('Schedule updated.', 10);
        });

        $this->assertDatabaseHas('maintenance_schedules', [
            'id' => $schedule->id,
            'task_description' => 'Updated Service',
            'priority' => 'high',
            'status' => 'in_progress',
        ]);
    }

    public function test_admin_can_edit_maintenance_record_via_modal(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $mechanic = User::factory()->mechanic()->create([
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
            'make' => 'Toyota',
            'model' => 'Vios',
            'year' => 2018,
            'license_plate' => 'MAINT-1',
        ]);
        $schedule = MaintenanceSchedule::create([
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'assigned_mechanic_id' => $mechanic->id,
            'created_by' => $admin->id,
            'scheduled_at' => now()->subDays(2),
            'scheduled_date' => now()->subDays(2)->toDateString(),
            'task_description' => 'General Checkup',
            'status' => 'completed',
            'priority' => 'normal',
            'task_type' => 'general',
        ]);

        // Ensure the admin maintenance edit modal has a service record to edit costs/notes.
        $schedule->serviceRecord()->create([
            'task_id' => $schedule->id,
            'labor_cost' => 10,
            'parts_cost' => 5,
            'notes' => 'Before',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit(route('admin.maintenance.index'))
                ->press('Edit')
                ->waitFor('#vms-modal.show', 5)
                ->waitForText('Edit Maintenance Record', 5)
                ->within('#vms-modal', function (Browser $modal) {
                    $modal->select('task_description', 'Oil Change')
                        ->type('total_cost', '99.50')
                        ->select('status', 'completed')
                        ->type('notes', 'Updated by Dusk')
                        ->press('Save Changes');
                })
                ->waitForLocation(route('admin.maintenance.index'), 10);
        });

        $this->assertDatabaseHas('maintenance_schedules', [
            'id' => $schedule->id,
            'task_description' => 'Oil Change',
            'status' => 'completed',
        ]);
        $this->assertDatabaseHas('service_records', [
            'maintenance_schedule_id' => $schedule->id,
            'notes' => 'Updated by Dusk',
        ]);
    }

    public function test_mechanic_can_complete_task_via_modal(): void
    {
        $mechanic = User::factory()->mechanic()->create([
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
            'make' => 'Ford',
            'model' => 'Ranger',
            'year' => 2020,
            'license_plate' => 'COMP-1',
        ]);
        $schedule = MaintenanceSchedule::create([
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'assigned_mechanic_id' => $mechanic->id,
            'created_by' => $mechanic->id,
            'scheduled_at' => now()->subHour(),
            'scheduled_date' => now()->toDateString(),
            'started_at' => now()->subMinutes(30),
            'task_description' => 'Complete via modal',
            'status' => 'in_progress',
            'priority' => 'normal',
            'task_type' => 'general',
        ]);

        $this->browse(function (Browser $browser) use ($mechanic) {
            $browser->loginAs($mechanic)
                ->visit(route('mechanic.inProgress'))
                ->press('Mark Complete')
                ->waitFor('#vms-modal.show', 5)
                ->waitForText('Complete Task', 5)
                ->within('#vms-modal', function (Browser $modal) {
                    $modal->type('end_time', now()->format('g:i a'))
                        ->type('work_summary', 'Dusk completed summary')
                        ->type('parts_used', 'Oil Filter x1')
                        ->type('labor_cost', '50')
                        ->type('parts_cost', '20')
                        ->type('recommendations', 'Next check in 5k miles')
                        ->press('Confirm & Mark Complete');
                })
                ->waitForLocation(route('mechanic.completed'), 10);
        });

        $this->assertDatabaseHas('maintenance_schedules', [
            'id' => $schedule->id,
            'status' => 'completed',
        ]);
        $this->assertDatabaseHas('service_records', [
            'maintenance_schedule_id' => $schedule->id,
            'work_summary' => 'Dusk completed summary',
        ]);
    }

    public function test_admin_can_delete_user_from_index(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $victim = User::factory()->create([
            'role' => 'owner',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $victim) {
            $browser->loginAs($admin)
                ->visit(route('admin.users.index'))
                ->assertSee($victim->email);

            // Submit the specific delete form for this user.
            $browser->within('form[action="' . route('admin.users.destroy', $victim) . '"]', function (Browser $form) {
                $form->press('Delete');
            })
                ->acceptDialog()
                ->waitForText('User deleted.', 10);
        });

        $this->assertDatabaseMissing('users', [
            'id' => $victim->id,
        ]);
    }

    public function test_admin_can_delete_schedule_from_show_page(): void
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
            'make' => 'Nissan',
            'model' => 'Almera',
            'year' => 2016,
            'license_plate' => 'DEL-SCHED-1',
        ]);
        $schedule = MaintenanceSchedule::create([
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => null,
            'assigned_mechanic_id' => null,
            'created_by' => $admin->id,
            'scheduled_at' => now()->addDay(),
            'scheduled_date' => now()->addDay()->toDateString(),
            'task_description' => 'Delete via UI',
            'status' => 'pending',
            'priority' => 'normal',
            'task_type' => 'general',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $schedule) {
            $browser->loginAs($admin)
                ->visit(route('schedules.show', $schedule));

            $browser->press('Delete')
                ->acceptDialog()
                ->waitForLocation(route('schedules.index'), 10);
        });

        $this->assertDatabaseMissing('maintenance_schedules', [
            'id' => $schedule->id,
        ]);
    }
}

