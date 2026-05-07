<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRecord extends Model
{
    protected $fillable = [
        'maintenance_schedule_id',
        'task_id',
        'service_date',
        'odometer',
        'parts_used',
        'work_summary',
        'recommendations',
        'labor_hours',
        'labor_rate',
        'labor_cost',
        'parts_cost',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'odometer' => 'integer',
            'labor_hours' => 'decimal:2',
            'labor_rate' => 'decimal:2',
            'labor_cost' => 'decimal:2',
            'parts_cost' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'task_id');
    }

    public function maintenanceSchedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(ServicePart::class, 'service_id');
    }

    public function totalCost(): float
    {
        return (float) ($this->labor_cost ?? 0) + (float) ($this->parts_cost ?? 0);
    }
}
