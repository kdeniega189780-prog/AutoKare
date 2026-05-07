<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePart extends Model
{
    protected $fillable = [
        'service_id',
        'part_id',
        'quantity_used',
        'unit_price_at_time',
        'line_total',
    ];

    protected function casts(): array
    {
        return [
            'quantity_used' => 'integer',
            'unit_price_at_time' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceRecord::class, 'service_id');
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class, 'part_id');
    }
}

