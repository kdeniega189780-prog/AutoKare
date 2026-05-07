<div class="modal-header">
    <h5 class="modal-title">Schedule Service</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<form method="POST" action="{{ route('schedules.store') }}">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label>Vehicle <span class="text-danger">*</span></label>
            <select name="vehicle_id" class="form-control" required>
                @foreach ($vehicles as $v)
                    <option value="{{ $v->id }}" @selected(($vehicle?->id ?? null) === $v->id)>{{ $v->displayLabel() }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Service Type <span class="text-danger">*</span></label>
            <select name="task_description" class="form-control" required>
                <option value="">Select service...</option>
                @foreach (['Oil Change','Tire Rotation','Brake Inspection','Brake Replacement','Air Filter','Engine Diagnostic','Transmission Service','General Checkup'] as $svc)
                    <option value="{{ $svc }}">{{ $svc }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label>Date <span class="text-danger">*</span></label>
                <input type="date" name="scheduled_date" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
                <label>Time <span class="text-danger">*</span></label>
                <select name="scheduled_time" class="form-control" required>
                    <option value="">Select time...</option>
                    @foreach (['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'] as $t)
                        <option value="{{ $t }}">{{ \Carbon\Carbon::createFromFormat('H:i', $t)->format('g:i A') }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control">
                <option value="pay_at_shop">Pay at Shop</option>
                <option value="online">Pay Online (coming soon)</option>
            </select>
        </div>

        <div class="form-group mb-0">
            <label>Notes <small class="text-muted">(optional)</small></label>
            <textarea name="description" rows="3" class="form-control"
                      placeholder="Any specific concerns or details about the service needed..."></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-calendar-plus mr-1"></i> Schedule Appointment</button>
    </div>
</form>
