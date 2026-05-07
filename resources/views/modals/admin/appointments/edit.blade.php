<div class="modal-header">
    <h5 class="modal-title">Edit Appointment</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<form method="POST" action="{{ route('schedules.update', $schedule) }}">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Vehicle</label>
                <select name="vehicle_id" class="form-control">
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->id }}" @selected($v->id === $schedule->vehicle_id)>{{ $v->displayLabel() }} — {{ $v->owner?->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Mechanic</label>
                <select name="mechanic_id" class="form-control">
                    <option value="">— None —</option>
                    @foreach ($mechanics as $m)
                        <option value="{{ $m->id }}" @selected($m->id === $schedule->mechanic_id)>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Service Type</label>
                <input type="text" name="task_description" class="form-control" value="{{ $schedule->task_description }}" required>
            </div>
            <div class="form-group col-md-6">
                <label>Priority</label>
                <select name="priority" class="form-control">
                    @foreach (['low' => 'Low', 'normal' => 'Normal', 'high' => 'High'] as $val => $lbl)
                        <option value="{{ $val }}" @selected($schedule->priority === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Date</label>
                <input type="date" name="scheduled_date" class="form-control" value="{{ $schedule->scheduled_at?->format('Y-m-d') }}" required>
            </div>
            <div class="form-group col-md-4">
                <label>Time</label>
                <input type="time" name="scheduled_time" class="form-control" value="{{ $schedule->scheduled_at?->format('H:i') }}" required>
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
                <textarea name="description" class="form-control" rows="3">{{ $schedule->description }}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Changes</button>
    </div>
</form>
