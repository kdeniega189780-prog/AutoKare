<?php

namespace Database\Seeders;

use App\Models\MaintenanceSchedule;
use App\Models\Part;
use App\Models\ServicePart;
use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed users, vehicles, schedules, parts and service records to mirror the
     * reference UI screenshots in `ref_ui/`. All passwords are "password".
     */
    public function run(): void
    {
        // -------------------------------------------------------------------
        // USERS
        // -------------------------------------------------------------------
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '(555) 000-1000',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'current_status' => 'off_duty',
            'email_verified_at' => now(),
            'last_login_at' => Carbon::create(2026, 1, 26, 9, 12),
        ]);

        $mechanics = collect([
            ['name' => 'Mike Mechanic', 'email' => 'mechanic@example.com', 'current_status' => 'working', 'phone' => '(555) 200-1001', 'last' => '2026-01-25'],
            ['name' => 'Sarah Mechanic', 'email' => 'sarah@example.com', 'current_status' => 'working', 'phone' => '(555) 200-1002', 'last' => '2026-01-24'],
            ['name' => 'Tom Tech', 'email' => 'tom@example.com', 'current_status' => 'on_break', 'phone' => '(555) 200-1003', 'last' => '2026-01-22'],
            ['name' => 'Sarah Tech', 'email' => 'sarah.tech@example.com', 'current_status' => 'working', 'phone' => '(555) 200-1004', 'last' => '2026-01-23'],
            ['name' => 'Tom Auto', 'email' => 'tom.auto@example.com', 'current_status' => 'on_break', 'phone' => '(555) 200-1005', 'last' => '2026-01-21'],
            ['name' => 'Lisa Service', 'email' => 'lisa@example.com', 'current_status' => 'working', 'phone' => '(555) 200-1006', 'last' => '2026-01-20'],
            ['name' => 'Chris Repair', 'email' => 'chris@example.com', 'current_status' => 'off_duty', 'phone' => '(555) 200-1007', 'last' => '2026-01-19'],
            ['name' => 'Alice Johnson', 'email' => 'alice@example.com', 'current_status' => 'working', 'phone' => '(555) 200-1008', 'last' => '2026-01-26'],
            ['name' => 'Bob Smith', 'email' => 'bob@example.com', 'current_status' => 'on_break', 'phone' => '(555) 200-1009', 'last' => '2026-01-26'],
            ['name' => 'Charlie Brown', 'email' => 'charlie@example.com', 'current_status' => 'working', 'phone' => '(555) 200-1010', 'last' => '2026-01-26'],
            ['name' => 'Diana Prince', 'email' => 'diana@example.com', 'current_status' => 'on_break', 'phone' => '(555) 200-1011', 'last' => '2026-01-26'],
            ['name' => 'Ethan Hunt', 'email' => 'ethan@example.com', 'current_status' => 'on_break', 'phone' => '(555) 200-1012', 'last' => '2026-01-26'],
        ])->mapWithKeys(function (array $row) {
            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'password' => Hash::make('password'),
                'role' => 'mechanic',
                'status' => 'active',
                'current_status' => $row['current_status'],
                'email_verified_at' => now(),
                'last_login_at' => Carbon::parse($row['last'] . ' 08:00:00'),
            ]);

            return [$row['name'] => $user];
        });

        $customers = collect([
            ['name' => 'John Customer', 'email' => 'customer@example.com', 'phone' => '(555) 123-4567', 'last' => '2026-01-26'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '(555) 234-5678', 'last' => '2026-01-25'],
            ['name' => 'Bob Johnson', 'email' => 'bob.johnson@example.com', 'phone' => '(555) 345-6789', 'last' => '2026-01-24'],
            ['name' => 'Jane Customer', 'email' => 'jane.customer@example.com', 'phone' => '(555) 456-7890', 'last' => '2026-01-23'],
        ])->mapWithKeys(function (array $row) {
            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'password' => Hash::make('password'),
                'role' => 'owner',
                'status' => 'active',
                'current_status' => 'off_duty',
                'email_verified_at' => now(),
                'last_login_at' => Carbon::parse($row['last'] . ' 10:00:00'),
            ]);

            return [$row['name'] => $user];
        });

        // -------------------------------------------------------------------
        // PARTS CATALOG
        // -------------------------------------------------------------------
        $oilFilter = Part::create([
            'part_number' => '04152-YZZA6',
            'name' => 'Oil Filter',
            'brand' => 'OEM',
            'oem_number' => '04152-YZZA6',
            'description' => 'Standard OEM oil filter.',
            'unit_price' => 12.00,
        ]);
        $motorOil = Part::create([
            'part_number' => 'MOBIL1-5W30',
            'name' => 'Synthetic Motor Oil 5W-30 (5 qt)',
            'brand' => 'Mobil 1',
            'oem_number' => 'MOBIL1-5W30',
            'description' => 'Mobil 1 5W-30 synthetic motor oil, 5 quart jug.',
            'unit_price' => 28.00,
        ]);
        $motorOil5qt = Part::create([
            'part_number' => 'OIL-5QT',
            'name' => 'Motor Oil (5qt)',
            'brand' => 'Generic',
            'oem_number' => 'OIL-5QT',
            'description' => 'Conventional motor oil, 5 quart jug.',
            'unit_price' => 25.00,
        ]);
        Part::create([
            'part_number' => 'BRAKE-PAD-FRONT',
            'name' => 'Front Brake Pads',
            'brand' => 'Akebono',
            'oem_number' => 'ACT787',
            'description' => 'Ceramic front brake pads.',
            'unit_price' => 80.00,
        ]);
        Part::create([
            'part_number' => 'AIR-FILTER-A1',
            'name' => 'Air Filter',
            'brand' => 'K&N',
            'oem_number' => 'KN-A1',
            'description' => 'High-flow air filter.',
            'unit_price' => 22.00,
        ]);

        // -------------------------------------------------------------------
        // VEHICLES (12 total to match dashboard count)
        // -------------------------------------------------------------------
        $vehicles = collect([
            ['owner' => 'John Customer', 'make' => 'Honda', 'model' => 'Civic', 'year' => 2020, 'license_plate' => 'ABC-123', 'type' => 'Sedan', 'mileage' => 45230, 'color' => 'Silver', 'vin' => '1HGBH41JXMN109186'],
            ['owner' => 'Jane Smith', 'make' => 'Toyota', 'model' => 'Camry', 'year' => 2019, 'license_plate' => 'XYZ-789', 'type' => 'Truck', 'mileage' => 78000, 'color' => 'Blue', 'vin' => '1HGCM82633A0043524'],
            ['owner' => 'Bob Johnson', 'make' => 'Ford', 'model' => 'Van', 'year' => 2021, 'license_plate' => 'DEF-456', 'type' => 'Van', 'mileage' => 32000, 'color' => 'White', 'vin' => '1FAHP3F61EJ100001'],
            ['owner' => 'John Customer', 'make' => 'Tesla', 'model' => 'Model Y', 'year' => 2022, 'license_plate' => 'TSL-001', 'type' => 'SUV', 'mileage' => 15000, 'color' => 'Red', 'vin' => '5YJYGDEE1MF100123'],
            ['owner' => 'Jane Customer', 'make' => 'Toyota', 'model' => 'Camry', 'year' => 2018, 'license_plate' => 'JAN-018', 'type' => 'Sedan', 'mileage' => 50000, 'color' => 'Black', 'vin' => '4T1B11HK1KU000001'],
            ['owner' => 'John Customer', 'make' => 'Ford', 'model' => 'Mustang', 'year' => 2021, 'license_plate' => 'MUS-021', 'type' => 'Coupe', 'mileage' => 30000, 'color' => 'Yellow', 'vin' => '1FATP8UH1J5100001'],
            ['owner' => 'Bob Johnson', 'make' => 'Chevrolet', 'model' => 'Silverado', 'year' => 2017, 'license_plate' => 'TRK-017', 'type' => 'Truck', 'mileage' => 92000, 'color' => 'Gray', 'vin' => '1GCRYDED5KZ100001'],
            ['owner' => 'Jane Smith', 'make' => 'Nissan', 'model' => 'Rogue', 'year' => 2020, 'license_plate' => 'NSN-020', 'type' => 'SUV', 'mileage' => 41000, 'color' => 'Pearl', 'vin' => '5N1AT2MV9LC100001'],
            ['owner' => 'Bob Johnson', 'make' => 'BMW', 'model' => '3 Series', 'year' => 2022, 'license_plate' => 'BMW-022', 'type' => 'Sedan', 'mileage' => 12000, 'color' => 'White', 'vin' => 'WBA8E9G55GNT00001'],
            ['owner' => 'Jane Customer', 'make' => 'Subaru', 'model' => 'Outback', 'year' => 2019, 'license_plate' => 'SUB-019', 'type' => 'Wagon', 'mileage' => 67000, 'color' => 'Green', 'vin' => '4S4BSANC5K3100001'],
            ['owner' => 'John Customer', 'make' => 'Hyundai', 'model' => 'Sonata', 'year' => 2020, 'license_plate' => 'HYU-020', 'type' => 'Sedan', 'mileage' => 38000, 'color' => 'Silver', 'vin' => '5NPEH4J21LH100001'],
            ['owner' => 'Jane Smith', 'make' => 'Mazda', 'model' => 'CX-5', 'year' => 2021, 'license_plate' => 'MAZ-021', 'type' => 'SUV', 'mileage' => 25000, 'color' => 'Red', 'vin' => 'JM3KFBDM5M0100001'],
        ])->map(function (array $v) use ($customers) {
            $owner = $customers[$v['owner']];

            return Vehicle::create([
                'owner_id' => $owner->id,
                'user_id' => $owner->id,
                'make' => $v['make'],
                'model' => $v['model'],
                'year' => $v['year'],
                'license_plate' => $v['license_plate'],
                'type' => $v['type'],
                'mileage' => $v['mileage'],
                'color' => $v['color'],
                'vin' => $v['vin'],
                'status' => 'active',
            ]);
        });

        $johnCivic = $vehicles[0];
        $janeCamry = $vehicles[1];
        $bobVan = $vehicles[2];
        $johnTesla = $vehicles[3];

        // -------------------------------------------------------------------
        // MAINTENANCE SCHEDULES + SERVICE RECORDS
        // -------------------------------------------------------------------
        // Helper to create a completed schedule + service record + parts.
        $completed = function (Vehicle $vehicle, User $mechanic, string $taskType, string $desc, string $date, float $labor, float $parts, ?string $summary = null, ?string $recommendations = null, ?string $notes = null, array $partLines = [], int $durationMin = 30) use ($admin) {
            $start = Carbon::parse($date . ' 09:00:00');
            $end = $start->copy()->addMinutes($durationMin);

            $schedule = MaintenanceSchedule::create([
                'vehicle_id' => $vehicle->id,
                'mechanic_id' => $mechanic->id,
                'assigned_mechanic_id' => $mechanic->id,
                'created_by' => $admin->id,
                'scheduled_at' => $start,
                'scheduled_date' => $start->toDateString(),
                'started_at' => $start,
                'completed_at' => $end,
                'estimated_minutes' => $durationMin,
                'task_type' => $taskType,
                'task_description' => $desc,
                'description' => $desc,
                'status' => 'completed',
                'priority' => 'normal',
            ]);

            $record = ServiceRecord::create([
                'maintenance_schedule_id' => $schedule->id,
                'task_id' => $schedule->id,
                'service_date' => $start->toDateString(),
                'odometer' => $vehicle->mileage,
                'parts_used' => collect($partLines)->map(fn ($line) => $line['name'] . ' x' . $line['qty'])->implode(', '),
                'work_summary' => $summary,
                'recommendations' => $recommendations,
                'labor_hours' => round($durationMin / 60, 2),
                'labor_rate' => 70,
                'labor_cost' => $labor,
                'parts_cost' => $parts,
                'notes' => $notes,
                'completed_at' => $end,
            ]);

            foreach ($partLines as $line) {
                ServicePart::create([
                    'service_id' => $record->id,
                    'part_id' => $line['part']->id,
                    'quantity_used' => $line['qty'],
                    'unit_price_at_time' => $line['part']->unit_price,
                    'line_total' => $line['part']->unit_price * $line['qty'],
                ]);
            }

            return $schedule;
        };

        $mike = $mechanics['Mike Mechanic'];
        $sarah = $mechanics['Sarah Mechanic'];
        $tom = $mechanics['Tom Tech'];
        $sarahTech = $mechanics['Sarah Tech'];
        $tomAuto = $mechanics['Tom Auto'];
        $lisa = $mechanics['Lisa Service'];
        $chris = $mechanics['Chris Repair'];
        $alice = $mechanics['Alice Johnson'];
        $charlie = $mechanics['Charlie Brown'];
        $diana = $mechanics['Diana Prince'];

        // === Completed services (drives Reports + Service History + Recent Activity) ===
        // Honda Civic - Oil Change (Mike) - 2026-01-15 -> shown in customer service history & recent activity
        $completed(
            $johnCivic, $mike, 'oil_change', 'Oil Change', '2026-01-15', 35.00, 37.00,
            "Drained old oil and replaced with 5W-30 synthetic oil\nReplaced oil filter with OEM part\nChecked all fluid levels - all within normal range\nInspected belts and hoses - no visible wear\nReset maintenance reminder light\nPerformed visual inspection of brakes - 60% pad life remaining",
            "Consider tire rotation at next service (due in ~3,000 miles)\nBrake pads have 60% life remaining - monitor for next 10,000 miles\nAll other systems operating normally",
            'Regular maintenance performed. All fluids checked and topped off. Vehicle in good condition.',
            [
                ['part' => $oilFilter, 'qty' => 1, 'name' => 'Oil Filter'],
                ['part' => $motorOil5qt, 'qty' => 1, 'name' => 'Motor Oil (5qt)'],
            ],
            30
        );

        // Toyota Camry - Brake Inspection (Sarah) - 2025-12-20 (in service history)
        $completed(
            $janeCamry, $sarah, 'brake', 'Brake Inspection', '2025-12-20', 90.00, 30.00,
            'Inspected front and rear brake pads, cleaned calipers, tested brake fluid.',
            'Front pads at 40% — recommend replacement within 5,000 miles.',
            'Customer reported soft pedal — bled rear lines, restored firmness.',
            [],
            60
        );

        // Honda Civic - Tire Rotation (Mike) - 2025-10-10 (in service history)
        $completed($johnCivic, $mike, 'tire_rotation', 'Tire Rotation', '2025-10-10', 60.00, 0.00,
            'Rotated tires front-to-back, torqued lug nuts, checked tread depth.',
            'Even tread wear, ready for next service in ~6,000 miles.',
            null, [], 30);

        // === Maintenance records visible in admin maintenance list (matches admin_maintenance.png) ===
        // Vehicle A123 - Oil Change - 2026-01-15 - Mike - $45 (already created above as Civic; total = 35 + 37 ≈ $72 — adjust)
        // Vehicle B456 - Tire Rotation - 2026-01-18 - Sarah - $60
        $completed($janeCamry, $sarah, 'tire_rotation', 'Tire Rotation', '2026-01-18', 60.00, 0.00,
            'Rotated tires, balanced wheels, set TPMS.', null, null, [], 30);

        // Vehicle C789 - Brake Inspection - 2026-01-10 - Tom - $120
        $completed($bobVan, $tom, 'brake', 'Brake Inspection', '2026-01-10', 100.00, 20.00,
            'Full brake inspection. Replaced wear sensors.', null, null, [], 60);

        // Vehicle A123 - Air Filter - 2026-01-20 - Mike - $35
        $completed($johnCivic, $mike, 'air_filter', 'Air Filter', '2026-01-20', 13.00, 22.00,
            'Replaced engine air filter.', null, null, [], 15);

        // === Senior mechanic completed list (matches mechanic_completed.png) ===
        // Vehicle B456 - Air Filter - 2026-01-24 - 45 min - "You (Senior Mechanic)"
        $completed($janeCamry, $mike, 'air_filter', 'Air Filter', '2026-01-24', 13.00, 22.00, null, null, null, [], 45);

        // Vehicle A123 - Oil Change - 2026-01-23 - 30 min - "You (Senior Mechanic)"
        $completed($johnCivic, $mike, 'oil_change', 'Oil Change', '2026-01-23', 35.00, 37.00, null, null, null, [
            ['part' => $oilFilter, 'qty' => 1, 'name' => 'Oil Filter'],
            ['part' => $motorOil5qt, 'qty' => 1, 'name' => 'Motor Oil (5qt)'],
        ], 30);

        // Vehicle G901 - Brake Replacement - 2026-04-03 - 1-2 hours - Diana Prince
        $completed($vehicles[6], $diana, 'brake', 'Brake Replacement', '2026-04-03', 200.00, 160.00,
            'Replaced front brake pads and rotors.', 'Customer approved estimate.', null, [], 90);

        // Add more historical completed for Reports completion counts (target ~18 completed)
        $extras = [
            ['vehicle' => $vehicles[4], 'mech' => $sarahTech, 'type' => 'oil_change', 'desc' => 'Oil Change', 'date' => '2026-04-02', 'labor' => 30, 'parts' => 37, 'dur' => 30],
            ['vehicle' => $vehicles[5], 'mech' => $tomAuto, 'type' => 'oil_change', 'desc' => 'Oil Change', 'date' => '2026-04-05', 'labor' => 30, 'parts' => 37, 'dur' => 30],
            ['vehicle' => $vehicles[7], 'mech' => $lisa, 'type' => 'oil_change', 'desc' => 'Oil Change', 'date' => '2026-04-06', 'labor' => 30, 'parts' => 37, 'dur' => 30],
            ['vehicle' => $vehicles[8], 'mech' => $chris, 'type' => 'oil_change', 'desc' => 'Oil Change', 'date' => '2026-04-07', 'labor' => 30, 'parts' => 37, 'dur' => 30],
            ['vehicle' => $vehicles[9], 'mech' => $sarah, 'type' => 'tire_rotation', 'desc' => 'Tire Rotation', 'date' => '2026-04-08', 'labor' => 60, 'parts' => 0, 'dur' => 30],
            ['vehicle' => $vehicles[10], 'mech' => $tom, 'type' => 'tire_rotation', 'desc' => 'Tire Rotation', 'date' => '2026-04-09', 'labor' => 60, 'parts' => 0, 'dur' => 30],
            ['vehicle' => $vehicles[11], 'mech' => $mike, 'type' => 'engine_diagnostic', 'desc' => 'Engine Diagnostic', 'date' => '2026-04-10', 'labor' => 150, 'parts' => 30, 'dur' => 90],
            ['vehicle' => $vehicles[5], 'mech' => $alice, 'type' => 'engine_diagnostic', 'desc' => 'Engine Diagnostic', 'date' => '2026-04-12', 'labor' => 140, 'parts' => 25, 'dur' => 80],
            ['vehicle' => $vehicles[8], 'mech' => $charlie, 'type' => 'general', 'desc' => 'Multi-point Inspection', 'date' => '2026-04-15', 'labor' => 50, 'parts' => 5, 'dur' => 30],
        ];
        foreach ($extras as $e) {
            $completed($e['vehicle'], $e['mech'], $e['type'], $e['desc'], $e['date'], (float) $e['labor'], (float) $e['parts'], null, null, null, [], $e['dur']);
        }

        // === In progress (drives mechanic_progress.png) ===
        $civicInProg = MaintenanceSchedule::create([
            'vehicle_id' => $johnCivic->id,
            'mechanic_id' => $mike->id,
            'assigned_mechanic_id' => $mike->id,
            'created_by' => $admin->id,
            'scheduled_at' => Carbon::create(2026, 1, 26, 10, 0),
            'scheduled_date' => '2026-01-26',
            'started_at' => Carbon::create(2026, 1, 26, 10, 24),
            'estimated_minutes' => 30,
            'task_type' => 'oil_change',
            'task_description' => 'Oil Change',
            'description' => 'Routine oil change',
            'service_instructions' => 'Perform standard oil change. Check all fluid levels, inspect belts and hoses. Customer reported no issues, this is routine maintenance.',
            'status' => 'in_progress',
            'priority' => 'high',
        ]);

        // Bob Johnson Van - Brake Inspection - In Progress (matches admin_appointment.png)
        MaintenanceSchedule::create([
            'vehicle_id' => $bobVan->id,
            'mechanic_id' => $tom->id,
            'assigned_mechanic_id' => $tom->id,
            'created_by' => $admin->id,
            'scheduled_at' => Carbon::create(2026, 4, 8, 9, 0),
            'scheduled_date' => '2026-04-08',
            'started_at' => Carbon::create(2026, 4, 8, 9, 0),
            'estimated_minutes' => 60,
            'task_type' => 'brake',
            'task_description' => 'Brake Inspection',
            'description' => 'Customer reports squealing on left front.',
            'status' => 'in_progress',
            'priority' => 'normal',
        ]);

        // === Assigned / Pending (drives mechanic_tasks.png + admin appointments.png) ===
        // Vehicle A123 - Oil Change - High Priority - Due 2026-01-26 - Task ID #1
        MaintenanceSchedule::create([
            'vehicle_id' => $johnCivic->id,
            'mechanic_id' => $mike->id,
            'assigned_mechanic_id' => $mike->id,
            'created_by' => $admin->id,
            'scheduled_at' => Carbon::create(2026, 1, 26, 10, 0),
            'scheduled_date' => '2026-01-26',
            'estimated_minutes' => 30,
            'task_type' => 'oil_change',
            'task_description' => 'Oil Change',
            'description' => 'Perform standard oil change. Check all fluid levels, inspect belts and hoses. Customer reported no issues, this is routine maintenance.',
            'service_instructions' => 'Perform standard oil change. Check all fluid levels, inspect belts and hoses. Customer reported no issues, this is routine maintenance.',
            'status' => 'pending',
            'priority' => 'high',
        ]);

        // Vehicle C789 - Brake Inspection - Medium Priority - Due 2026-01-27 - Task ID #2
        MaintenanceSchedule::create([
            'vehicle_id' => $bobVan->id,
            'mechanic_id' => $mike->id,
            'assigned_mechanic_id' => $mike->id,
            'created_by' => $admin->id,
            'scheduled_at' => Carbon::create(2026, 1, 27, 9, 0),
            'scheduled_date' => '2026-01-27',
            'estimated_minutes' => 60,
            'task_type' => 'brake',
            'task_description' => 'Brake Inspection',
            'description' => 'Inspect front brake pads and rotors.',
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        // Vehicle D012 - Tire Rotation - Low Priority - Due 2026-01-28 - Task ID #3
        MaintenanceSchedule::create([
            'vehicle_id' => $johnTesla->id,
            'mechanic_id' => $mike->id,
            'assigned_mechanic_id' => $mike->id,
            'created_by' => $admin->id,
            'scheduled_at' => Carbon::create(2026, 1, 28, 14, 0),
            'scheduled_date' => '2026-01-28',
            'estimated_minutes' => 30,
            'task_type' => 'tire_rotation',
            'task_description' => 'Tire Rotation',
            'description' => 'Rotate tires.',
            'status' => 'pending',
            'priority' => 'low',
        ]);

        // === Admin Appointments table fixtures (admin_appointment.png) ===
        // John Customer - Honda Civic - Oil Change - 2026-04-10 10:00 AM - Scheduled
        MaintenanceSchedule::create([
            'vehicle_id' => $johnCivic->id,
            'mechanic_id' => $mike->id,
            'assigned_mechanic_id' => $mike->id,
            'created_by' => $admin->id,
            'scheduled_at' => Carbon::create(2026, 4, 10, 10, 0),
            'scheduled_date' => '2026-04-10',
            'estimated_minutes' => 30,
            'task_type' => 'oil_change',
            'task_description' => 'Oil Change',
            'status' => 'pending',
            'priority' => 'normal',
        ]);

        // Jane Smith - Toyota Camry - Tire Rotation - 2026-04-12 2:00 PM - Scheduled
        MaintenanceSchedule::create([
            'vehicle_id' => $janeCamry->id,
            'mechanic_id' => $sarah->id,
            'assigned_mechanic_id' => $sarah->id,
            'created_by' => $admin->id,
            'scheduled_at' => Carbon::create(2026, 4, 12, 14, 0),
            'scheduled_date' => '2026-04-12',
            'estimated_minutes' => 30,
            'task_type' => 'tire_rotation',
            'task_description' => 'Tire Rotation',
            'status' => 'pending',
            'priority' => 'normal',
        ]);

        // John Customer - Tesla SUV - General Checkup - 2026-04-15 11:00 AM - Scheduled
        MaintenanceSchedule::create([
            'vehicle_id' => $johnTesla->id,
            'mechanic_id' => $lisa->id,
            'assigned_mechanic_id' => $lisa->id,
            'created_by' => $admin->id,
            'scheduled_at' => Carbon::create(2026, 4, 15, 11, 0),
            'scheduled_date' => '2026-04-15',
            'estimated_minutes' => 45,
            'task_type' => 'general',
            'task_description' => 'General Checkup',
            'status' => 'pending',
            'priority' => 'normal',
        ]);

        // === Customer appointments (customer_appointment.png) ===
        // 2020 Honda Civic - Oil Change - 2026-01-28 10:00 AM
        MaintenanceSchedule::create([
            'vehicle_id' => $johnCivic->id,
            'mechanic_id' => $mike->id,
            'assigned_mechanic_id' => $mike->id,
            'created_by' => $customers['John Customer']->id,
            'scheduled_at' => Carbon::create(2026, 1, 28, 10, 0),
            'scheduled_date' => '2026-01-28',
            'estimated_minutes' => 30,
            'task_type' => 'oil_change',
            'task_description' => 'Oil Change',
            'payment_method' => 'pay_at_shop',
            'status' => 'pending',
            'priority' => 'normal',
        ]);

        // 2019 Toyota Camry - Tire Rotation - 2026-02-05 2:00 PM (booked by Jane Smith — but ref shows under John customer's account; we'll show all customer appointments via owner)
        MaintenanceSchedule::create([
            'vehicle_id' => $janeCamry->id,
            'mechanic_id' => $sarah->id,
            'assigned_mechanic_id' => $sarah->id,
            'created_by' => $customers['Jane Smith']->id,
            'scheduled_at' => Carbon::create(2026, 2, 5, 14, 0),
            'scheduled_date' => '2026-02-05',
            'estimated_minutes' => 30,
            'task_type' => 'tire_rotation',
            'task_description' => 'Tire Rotation',
            'payment_method' => 'pay_at_shop',
            'status' => 'pending',
            'priority' => 'normal',
        ]);

        // === Team-overview "Tasks Assigned to Team" (mechanic_team_overview_1+2.png) ===
        // Vehicle E345 - Engine Diagnostic - Alice Johnson - 2026-04-04 - 2-3 hours - Assigned
        MaintenanceSchedule::create([
            'vehicle_id' => $vehicles[4]->id,
            'mechanic_id' => $alice->id,
            'assigned_mechanic_id' => $alice->id,
            'created_by' => $mike->id,
            'scheduled_at' => Carbon::create(2026, 4, 4, 9, 0),
            'scheduled_date' => '2026-04-04',
            'estimated_minutes' => 150,
            'task_type' => 'engine_diagnostic',
            'task_description' => 'Engine Diagnostic',
            'description' => 'Customer reports strange engine noise. Check timing belt and valves.',
            'initial_notes' => 'Customer reports strange engine noise. Check timing belt and valves.',
            'status' => 'pending',
            'priority' => 'normal',
        ]);

        // Vehicle F678 - Transmission Service - Charlie Brown - 2026-04-03 - 3-4 hours - In Progress
        MaintenanceSchedule::create([
            'vehicle_id' => $vehicles[5]->id,
            'mechanic_id' => $charlie->id,
            'assigned_mechanic_id' => $charlie->id,
            'created_by' => $mike->id,
            'scheduled_at' => Carbon::create(2026, 4, 3, 9, 0),
            'scheduled_date' => '2026-04-03',
            'started_at' => Carbon::create(2026, 4, 3, 9, 30),
            'estimated_minutes' => 210,
            'task_type' => 'transmission',
            'task_description' => 'Transmission Service',
            'description' => 'Full transmission flush and filter replacement.',
            'initial_notes' => 'Full transmission flush and filter replacement.',
            'status' => 'in_progress',
            'priority' => 'normal',
        ]);
    }
}
