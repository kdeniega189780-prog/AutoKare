@php $isAdmin = Auth::user()->isAdmin(); @endphp

<div class="modal-header">
    <h5 class="modal-title">{{ $isAdmin ? 'Add Vehicle for Customer' : 'Add New Vehicle' }}</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<form method="POST" action="{{ route('vehicles.store') }}" id="vmsAddVehicleForm">
    @csrf
    <div class="modal-body">
        @if ($isAdmin)
            <div class="card card-outline card-info mb-3">
                <div class="card-header py-2">
                    <h3 class="card-title font-weight-bold text-info">
                        <i class="fas fa-search mr-1"></i> Select Existing Customer
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group mb-2">
                        <label>Search Customer by Name or Phone <span class="text-danger">*</span></label>
                        <input type="hidden" name="user_id" id="vms_customer_id" value="{{ old('user_id') }}">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="vms_customer_search" autocomplete="off" class="form-control"
                                   placeholder="Type to search... (e.g., 'Sarah' or '555')">
                        </div>
                        @error('user_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        <div id="vms_customer_results" class="list-group mt-2 d-none" style="max-height:240px; overflow-y:auto;"></div>
                    </div>
                    <div class="alert alert-warning mb-0 small">
                        <i class="fas fa-lightbulb mr-1"></i>
                        <strong>Walk-in/Phone Customers:</strong>
                        Create their account in <em>User Management</em> tab first (takes 30 seconds), then book appointment here.
                    </div>
                </div>
            </div>
        @endif

        <div class="card card-outline card-secondary">
            <div class="card-header py-2">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-car mr-1"></i> Vehicle Information
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="make_model">Vehicle Make &amp; Model <span class="text-danger">*</span></label>
                        <input id="make_model" class="form-control" placeholder="e.g., 2020 Honda Civic"
                               value="{{ old('make') ? old('year').' '.old('make').' '.old('model') : '' }}" required>
                        <input type="hidden" id="make" name="make" value="{{ old('make') }}">
                        <input type="hidden" id="model" name="model" value="{{ old('model') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="license_plate">License Plate <span class="text-danger">*</span></label>
                        <input id="license_plate" name="license_plate" class="form-control @error('license_plate') is-invalid @enderror"
                               placeholder="e.g., ABC-123" value="{{ old('license_plate') }}" required>
                        @error('license_plate')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="type">Type</label>
                        <select id="type" name="type" class="form-control">
                            @foreach (['Sedan','SUV','Truck','Van','Coupe','Hatchback','Wagon','Other'] as $t)
                                <option value="{{ $t }}" @selected(old('type') === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="year">Year <span class="text-danger">*</span></label>
                        <input id="year" name="year" type="number" class="form-control @error('year') is-invalid @enderror"
                               placeholder="e.g., 2020" value="{{ old('year') }}" required>
                        @error('year')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="mileage">Current Mileage</label>
                        <input id="mileage" name="mileage" type="number" class="form-control"
                               placeholder="e.g., 45000" value="{{ old('mileage') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="color">Color (Optional)</label>
                        <input id="color" name="color" class="form-control" placeholder="e.g., Silver"
                               value="{{ old('color') }}">
                    </div>
                </div>
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
    var input = document.getElementById('make_model');
    var make = document.getElementById('make');
    var model = document.getElementById('model');
    if (input && make && model) {
        var sync = function () {
            var txt = input.value.trim();
            var parts = txt.split(/\s+/);
            if (/^\d{4}$/.test(parts[0])) {
                var year = document.getElementById('year');
                if (year && !year.value) year.value = parts[0];
                parts.shift();
            }
            make.value = parts[0] || '';
            model.value = parts.slice(1).join(' ') || '';
        };
        input.addEventListener('input', sync);
        sync();
    }

    var search = document.getElementById('vms_customer_search');
    var results = document.getElementById('vms_customer_results');
    var hiddenId = document.getElementById('vms_customer_id');
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
