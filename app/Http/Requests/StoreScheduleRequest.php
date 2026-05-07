<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Some UI modals submit scheduled_date + scheduled_time instead of scheduled_at.
        if (! $this->filled('scheduled_at') && $this->filled('scheduled_date')) {
            $time = $this->input('scheduled_time') ?: '09:00';
            $this->merge([
                'scheduled_at' => $this->input('scheduled_date') . ' ' . $time,
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'mechanic_id' => ['nullable', 'exists:users,id'],
            'scheduled_at' => ['required', 'date'],
            'scheduled_date' => ['nullable', 'date'],
            'scheduled_time' => ['nullable', 'string', 'max:16'],
            'task_description' => ['required', 'string', 'max:500'],
            'status' => ['nullable', 'in:pending,in_progress,completed,cancelled'],
            'priority' => ['nullable', 'in:low,normal,high'],
            'payment_method' => ['nullable', 'string', 'max:32'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

