<div class="modal-header">
    <h5 class="modal-title">Edit Maintenance Record</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<form method="POST" action="{{ route('admin.maintenance.update', $schedule) }}">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Vehicle</label>
                <select name="vehicle_id" class="form-control">
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->id }}" @selected($v->id === $schedule->vehicle_id)>{{ $v->shortId() }} — {{ $v->displayLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Maintenance Type</label>
                <select name="task_description" class="form-control" required>
                    @foreach (['Oil Change','Tire Rotation','Brake Inspection','Brake Replacement','Air Filter','Engine Diagnostic','Transmission Service','General Checkup'] as $svc)
                        <option value="{{ $svc }}" @selected($schedule->task_description === $svc)>{{ $svc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Date</label>
                <input type="date" name="scheduled_at" class="form-control" value="{{ $schedule->scheduled_at?->format('Y-m-d') }}">
            </div>
            <div class="form-group col-md-4">
                <label>Cost</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                    <input type="number" step="0.01" name="total_cost" class="form-control" value="{{ $record?->totalCost() ?? '' }}" placeholder="0.00">
                </div>
            </div>
            <div class="form-group col-md-4">
                <label>Status</label>
                <select name="status" class="form-control">
                    @foreach (['pending' => 'Scheduled', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $lbl)
                        <option value="{{ $val }}" @selected($schedule->status === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12">
                <label>Notes</label>
                <textarea name="notes" rows="4" class="form-control" placeholder="Enter notes...">{{ $record?->notes }}</textarea>
            </div>
        </div>
        <input type="hidden" name="mechanic_id" value="{{ $schedule->mechanic_id }}">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Changes</button>
    </div>
</form>
