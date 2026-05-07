<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Part extends Model
{
    protected $fillable = [
        'part_number',
        'name',
        'brand',
        'oem_number',
        'description',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
        ];
    }

    public function serviceLines(): HasMany
    {
        return $this->hasMany(ServicePart::class, 'part_id');
    }
}

