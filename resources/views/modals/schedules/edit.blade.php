<div class="p-2">
    <div class="mb-3">
        <h4 class="mb-0">{{ __('Edit Maintenance Record') }}</h4>
    </div>

    <form method="POST" action="{{ route('schedules.update', $schedule) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="vehicle_id" class="font-weight-bold">{{ __('Vehicle') }}</label>
                <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->id }}" @selected(old('vehicle_id', $schedule->vehicle_id) == $v->id)>
                            {{ $v->make }} {{ $v->model }} ({{ $v->license_plate }})
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="form-group col-md-6">
                <label for="mechanic_id" class="font-weight-bold">{{ __('Maintenance Type / Mechanic') }}</label>
                <select id="mechanic_id" name="mechanic_id" class="form-control">
                    <option value="">{{ __('Unassigned') }}</option>
                    @foreach ($mechanics as $m)
                        <option value="{{ $m->id }}" @selected(old('mechanic_id', $schedule->mechanic_id) == $m->id)>{{ $m->name }}</option>
                    @endforeach
                </select>
                @error('mechanic_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="scheduled_at" class="font-weight-bold">{{ __('Date & time') }}</label>
                <input id="scheduled_at" name="scheduled_at" type="datetime-local" class="form-control"
                       value="{{ old('scheduled_at', $schedule->scheduled_at->format('Y-m-d\\TH:i')) }}" required>
                @error('scheduled_at')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="form-group col-md-6">
                <label for="status" class="font-weight-bold">{{ __('Status') }}</label>
                <select id="status" name="status" class="form-control" required>
                    @foreach (['pending', 'in_progress', 'completed', 'cancelled'] as $st)
                        <option value="{{ $st }}" @selected(old('status', $schedule->status) === $st)>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label for="task_description" class="font-weight-bold">{{ __('Notes') }}</label>
            <textarea id="task_description" name="task_description" class="form-control" rows="3" required>{{ old('task_description', $schedule->task_description) }}</textarea>
            @error('task_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-between pt-2">
            <button type="submit" class="btn btn-dark px-5">{{ __('Save Changes') }}</button>
            <button class="btn btn-outline-secondary px-5" data-modal-close>{{ __('Cancel') }}</button>
        </div>
    </form>
</div>
