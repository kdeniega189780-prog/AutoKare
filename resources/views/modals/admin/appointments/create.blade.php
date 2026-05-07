<div class="modal-header">
    <h5 class="modal-title">Book Appointment for Customer</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<form method="POST" action="{{ route('schedules.store') }}">
    @csrf
    <div class="modal-body">
        <div class="card card-outline card-info mb-3">
            <div class="card-header py-2">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-search mr-1"></i> Select Existing Customer
                </h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-2">
                    <label>Search Customer by Name or Phone <span class="text-danger">*</span></label>
                    <input type="hidden" name="customer_id" id="vms_appt_customer_id">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                        <input type="text" id="vms_appt_customer_search" autocomplete="off" class="form-control"
                               placeholder="Type to search... (e.g., 'Sarah' or '555')">
                    </div>
                    <div id="vms_appt_customer_results" class="list-group mt-2 d-none" style="max-height:240px; overflow-y:auto;"></div>
                </div>
                <div class="alert alert-warning mb-0 small">
                    <i class="fas fa-lightbulb mr-1"></i>
                    <strong>Walk-in/Phone Customers:</strong>
                    Create their account in <em>User Management</em> tab first (takes 30 seconds), then book appointment here.
                </div>
            </div>
        </div>

        <div class="card card-outline card-secondary">
            <div class="card-header py-2">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-car mr-1"></i> Vehicle &amp; Service Details
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Vehicle <span class="text-danger">*</span></label>
                        <select name="vehicle_id" class="form-control" required>
                            <option value="">Select vehicle...</option>
                            @foreach ($vehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->displayLabel() }} — {{ $v->owner?->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Mechanic</label>
                        <select name="mechanic_id" class="form-control">
                            <option value="">Auto-assign</option>
                            @foreach ($mechanics as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Service Type <span class="text-danger">*</span></label>
                        <select name="task_description" class="form-control" required>
                            <option value="">Select service...</option>
                            @foreach (['Oil Change','Tire Rotation','Brake Inspection','Brake Replacement','Air Filter','Engine Diagnostic','Transmission Service','General Checkup'] as $svc)
                                <option value="{{ $svc }}">{{ $svc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Priority</label>
                        <select name="priority" class="form-control">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
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
                    <div class="form-group col-md-12">
                        <label>Notes</label>
                        <textarea name="description" rows="3" class="form-control"
                                  placeholder="Any special instructions or issues reported by customer..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-calendar-plus mr-1"></i> Book Appointment</button>
    </div>
</form>

<script>
(function () {
    var search = document.getElementById('vms_appt_customer_search');
    var results = document.getElementById('vms_appt_customer_results');
    var hiddenId = document.getElementById('vms_appt_customer_id');
    if (!search || !results || !hiddenId) return;

    var url = @json(route('admin.customers.search'));
    var debounce = null;

    function hide() { results.classList.add('d-none'); results.innerHTML = ''; }
    function show(items) {
        results.innerHTML = '';
        if (!items.length) { hide(); return; }
        items.forEach(function (item) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action';
            var phone = item.phone ? ' • ' + item.phone : '';
            btn.innerHTML = '<div class="font-weight-bold">' + item.name + '</div><small class="text-muted">' + item.email + phone + '</small>';
            btn.addEventListener('click', function () {
                hiddenId.value = item.id;
                search.value = item.name + (item.phone ? ' (' + item.phone + ')' : '');
                hide();
            });
            results.appendChild(btn);
        });
        results.classList.remove('d-none');
    }

    search.addEventListener('input', function () {
        if (debounce) clearTimeout(debounce);
        debounce = setTimeout(function () {
            var q = search.value.trim();
            hiddenId.value = '';
            if (q.length < 2) { hide(); return; }
            fetch(url + '?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            }).then(function (res) { return res.json(); }).then(show).catch(hide);
        }, 200);
    });
})();
</script>
