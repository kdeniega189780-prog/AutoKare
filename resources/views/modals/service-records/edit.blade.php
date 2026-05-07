<div class="p-2">
    <div class="mb-3">
        <h4 class="mb-0">{{ __('Service Report') }}</h4>
        <div class="text-muted small">{{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }} — {{ $schedule->task_description }}</div>
    </div>

    <form method="POST" action="{{ route('service-records.update', $schedule) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="notes" class="font-weight-bold">{{ __('Work Completed Summary / Notes') }}</label>
            <textarea id="notes" name="notes" class="form-control" rows="4" placeholder="{{ __('Describe the work performed…') }}">{{ old('notes', $record->notes) }}</textarea>
            @error('notes')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="parts_used" class="font-weight-bold">{{ __('Parts & Materials Used') }}</label>
            <textarea id="parts_used" name="parts_used" class="form-control" rows="3" placeholder="{{ __('List parts used with quantities…') }}">{{ old('parts_used', $record->parts_used) }}</textarea>
            @error('parts_used')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="labor_hours" class="font-weight-bold">{{ __('Labor (hours)') }}</label>
                <input id="labor_hours" name="labor_hours" type="number" step="0.01" min="0" class="form-control" value="{{ old('labor_hours', $record->labor_hours) }}">
                @error('labor_hours')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="form-group col-md-4">
                <label for="labor_cost" class="font-weight-bold">{{ __('Labor Cost') }}</label>
                <input id="labor_cost" name="labor_cost" type="number" step="0.01" min="0" class="form-control" value="{{ old('labor_cost', $record->labor_cost) }}">
                @error('labor_cost')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="form-group col-md-4">
                <label for="parts_cost" class="font-weight-bold">{{ __('Parts Cost') }}</label>
                <input id="parts_cost" name="parts_cost" type="number" step="0.01" min="0" class="form-control" value="{{ old('parts_cost', $record->parts_cost) }}">
                @error('parts_cost')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group mb-0">
            <div class="custom-control custom-checkbox">
                <input type="hidden" name="mark_completed" value="0">
                <input type="checkbox" class="custom-control-input" id="mark_completed" name="mark_completed" value="1" @checked(old('mark_completed'))>
                <label class="custom-control-label" for="mark_completed">{{ __('Confirm & mark completed') }}</label>
            </div>
            @error('mark_completed')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-between pt-3">
            <button type="submit" class="btn btn-dark px-5">{{ __('Save') }}</button>
            <button class="btn btn-outline-secondary px-5" data-modal-close>{{ __('Close') }}</button>
        </div>
    </form>
</div>
