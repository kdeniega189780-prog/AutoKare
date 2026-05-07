<div class="p-2">
    <div class="mb-3">
        <h4 class="mb-0">{{ Auth::user()->isAdmin() ? __('Book Appointment for Customer') : __('Book Service Appointment') }}</h4>
    </div>

    <form method="POST" action="{{ route('schedules.store') }}">
        @csrf

        <div class="form-group">
            <label for="vehicle_id" class="font-weight-bold">{{ __('Select Vehicle') }}</label>
            <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                <option value="">{{ __('Choose a vehicle') }}</option>
                @foreach ($vehicles as $v)
                    <option value="{{ $v->id }}" @selected(old('vehicle_id', $selectedVehicleId) == $v->id)>
                        {{ $v->make }} {{ $v->model }} ({{ $v->license_plate }})
                    </option>
                @endforeach
            </select>
            @error('vehicle_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        @if (Auth::user()->isAdmin())
            <div class="form-group">
                <label for="mechanic_id" class="font-weight-bold">{{ __('Assign mechanic (optional)') }}</label>
                <select id="mechanic_id" name="mechanic_id" class="form-control">
                    <option value="">{{ __('Auto / admin assigns later') }}</option>
                    @foreach ($mechanics as $m)
                        <option value="{{ $m->id }}" @selected(old('mechanic_id') == $m->id)>{{ $m->name }}</option>
                    @endforeach
                </select>
                @error('mechanic_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        @endif

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="scheduled_at" class="font-weight-bold">{{ __('Preferred Date & Time') }}</label>
                <input id="scheduled_at" name="scheduled_at" type="datetime-local" class="form-control" value="{{ old('scheduled_at') }}" required>
                @error('scheduled_at')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="form-group col-md-6">
                <label for="status" class="font-weight-bold">{{ __('Status') }}</label>
                <select id="status" name="status" class="form-control" {{ Auth::user()->isAdmin() ? '' : 'disabled' }}>
                    @foreach (['pending', 'in_progress', 'completed', 'cancelled'] as $st)
                        <option value="{{ $st }}" @selected(old('status', 'pending') === $st)>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                    @endforeach
                </select>
                @if (!Auth::user()->isAdmin())
                    <input type="hidden" name="status" value="{{ old('status', 'pending') }}">
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="task_description" class="font-weight-bold">{{ __('Service Type / Notes') }}</label>
            <textarea id="task_description" name="task_description" class="form-control" rows="3" required placeholder="{{ __('Describe the work needed (e.g., Oil Change)…') }}">{{ old('task_description') }}</textarea>
            @error('task_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-between pt-2">
            <button type="submit" class="btn btn-dark px-5">{{ __('Book Appointment') }}</button>
            <button class="btn btn-outline-secondary px-5" data-modal-close>{{ __('Cancel') }}</button>
        </div>
    </form>
</div>
