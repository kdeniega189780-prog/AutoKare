<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id',
        'owner_id',
        'make',
        'model',
        'year',
        'license_plate',
        'status',
        'type',
        'mileage',
        'color',
        'vin',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'mileage' => 'integer',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function displayLabel(): string
    {
        $parts = array_filter([$this->year, $this->make, $this->model]);

        return $parts ? implode(' ', $parts) : ('Vehicle #' . $this->id);
    }

    public function shortId(): string
    {
        return 'Vehicle ' . strtoupper(substr(md5((string) $this->id . $this->license_plate), 0, 4));
    }
}
