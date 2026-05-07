<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MaintenanceSchedule extends Model
{
    protected static function booted(): void
    {
        // Auto-populate required_parts from task_type if not explicitly provided,
        // so seeded rows and future inserts both end up with a real DB value.
        static::creating(function (MaintenanceSchedule $schedule) {
            if (empty($schedule->required_parts) && ! empty($schedule->task_type)) {
                $schedule->required_parts = self::defaultPartsFor((string) $schedule->task_type);
            }
        });
    }

    protected $fillable = [
        'vehicle_id',
        'mechanic_id',
        'assigned_mechanic_id',
        'created_by',
        'scheduled_at',
        'scheduled_date',
        'started_at',
        'completed_at',
        'estimated_minutes',
        'task_type',
        'task_description',
        'description',
        'service_instructions',
        'initial_notes',
        'progress_notes',
        'payment_method',
        'status',
        'priority',
        'required_parts',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'scheduled_date' => 'date',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'estimated_minutes' => 'integer',
            'required_parts' => 'array',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function assignedMechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_mechanic_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function serviceRecord(): HasOne
    {
        return $this->hasOne(ServiceRecord::class);
    }

    public function priorityLabel(): string
    {
        return match (strtolower((string) $this->priority)) {
            'high' => 'High Priority',
            'low' => 'Low Priority',
            default => 'Medium Priority',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Scheduled',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst((string) $this->status),
        };
    }

    public function durationMinutes(): ?int
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInMinutes($this->completed_at);
        }

        return $this->estimated_minutes;
    }

    /**
     * Returns the required parts list for this task. Falls back to a sensible
     * default keyed off task_type so legacy rows still render something.
     *
     * @return array<int, array{name: string, qty: string}>
     */
    public function requiredPartsList(): array
    {
        if (is_array($this->required_parts) && count($this->required_parts) > 0) {
            return array_map(fn ($p) => [
                'name' => (string) ($p['name'] ?? ''),
                'qty' => (string) ($p['qty'] ?? '1'),
            ], $this->required_parts);
        }

        return self::defaultPartsFor((string) $this->task_type);
    }

    /**
     * Default parts list per task type, used by the seeder and as a fallback.
     *
     * @return array<int, array{name: string, qty: string}>
     */
    public static function defaultPartsFor(string $taskType): array
    {
        return match ($taskType) {
            'oil_change' => [
                ['name' => 'Oil Filter', 'qty' => '1'],
                ['name' => 'Motor Oil (5W-30)', 'qty' => '5 quarts'],
            ],
            'brake' => [
                ['name' => 'Brake Pads (Front Set)', 'qty' => '1 set'],
                ['name' => 'Brake Fluid (DOT 4)', 'qty' => '1 bottle'],
            ],
            'tire_rotation' => [
                ['name' => 'Wheel Lug Nuts', 'qty' => '20'],
            ],
            'engine_diagnostic' => [
                ['name' => 'OBD-II Scanner Session', 'qty' => '1'],
            ],
            'air_filter' => [
                ['name' => 'Engine Air Filter', 'qty' => '1'],
            ],
            'transmission' => [
                ['name' => 'Transmission Fluid (ATF)', 'qty' => '6 quarts'],
                ['name' => 'Transmission Filter Kit', 'qty' => '1'],
            ],
            default => [],
        };
    }
}
