<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'license_plate' => ['required', 'string', 'max:32'],
            'type' => ['nullable', 'string', 'max:50'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:50'],
            'vin' => ['nullable', 'string', 'max:64'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];

        if ($this->user()?->isAdmin()) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        return $rules;
    }
}

