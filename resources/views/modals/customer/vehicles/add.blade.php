<div class="modal-header">
    <h5 class="modal-title">Add New Vehicle</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<form method="POST" action="{{ route('customer.vehicles.store') }}">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label>Vehicle Make &amp; Model <span class="text-danger">*</span></label>
            <input type="text" id="cust_make_model" class="form-control" placeholder="e.g., 2020 Honda Civic" required>
            <input type="hidden" id="cust_make" name="make">
            <input type="hidden" id="cust_model" name="model">
            <input type="hidden" id="cust_year" name="year">
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label>License Plate <span class="text-danger">*</span></label>
                <input type="text" name="license_plate" class="form-control" placeholder="e.g., ABC-123" required>
            </div>
            <div class="form-group col-md-6">
                <label>Year <span class="text-danger">*</span></label>
                <input type="number" id="cust_year_visible" class="form-control" placeholder="e.g., 2020" required>
            </div>
            <div class="form-group col-md-12">
                <label>Current Mileage</label>
                <input type="number" name="mileage" class="form-control" placeholder="e.g., 45000">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Vehicle</button>
    </div>
</form>

<script>
(function () {
    var form = document.querySelector('form[action="{{ route('customer.vehicles.store') }}"]');
    var mk = document.getElementById('cust_make_model');
    var yearV = document.getElementById('cust_year_visible');
    var make = document.getElementById('cust_make');
    var model = document.getElementById('cust_model');
    var year = document.getElementById('cust_year');
    function syncMakeModel() {
        var txt = mk.value.trim();
        var parts = txt.split(/\s+/);
        if (/^\d{4}$/.test(parts[0])) {
            year.value = parts.shift();
            yearV.value = year.value;
        }
        make.value = parts[0] || '';
        model.value = parts.slice(1).join(' ') || '';
    }
    function syncYear() { year.value = yearV.value; }
    if (mk) mk.addEventListener('input', syncMakeModel);
    if (yearV) yearV.addEventListener('input', syncYear);

    // Run once on load and again right before submit.
    try { syncMakeModel(); syncYear(); } catch (e) { /* ignore */ }
    if (form) {
        form.addEventListener('submit', function () {
            try { syncMakeModel(); syncYear(); } catch (e) { /* ignore */ }
        });
    }
})();
</script>
