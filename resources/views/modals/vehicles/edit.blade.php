<div class="modal-header">
    <h5 class="modal-title">Edit Vehicle</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<form method="POST" action="{{ route('vehicles.update', $vehicle) }}">
    @csrf
    @method('PUT')
    <div class="modal-body">
        @if (Auth::user()->isAdmin())
            <div class="form-group">
                <label>Owner</label>
                <select name="user_id" class="form-control">
                    @foreach ($owners as $owner)
                        <option value="{{ $owner->id }}" @selected((int) ($vehicle->owner_id ?? $vehicle->user_id) === $owner->id)>{{ $owner->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="row">
            <div class="form-group col-md-6">
                <label>Vehicle Make &amp; Model</label>
                <input class="form-control" id="make_model_e" value="{{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}">
                <input type="hidden" id="make_e" name="make" value="{{ $vehicle->make }}">
                <input type="hidden" id="model_e" name="model" value="{{ $vehicle->model }}">
            </div>
            <div class="form-group col-md-6">
                <label>License Plate</label>
                <input class="form-control" name="license_plate" value="{{ $vehicle->license_plate }}">
            </div>
            <div class="form-group col-md-6">
                <label>Type</label>
                <select name="type" class="form-control">
                    @foreach (['Sedan','SUV','Truck','Van','Coupe','Hatchback','Wagon','Other'] as $t)
                        <option value="{{ $t }}" @selected($vehicle->type === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Year</label>
                <input type="number" class="form-control" name="year" value="{{ $vehicle->year }}">
            </div>
            <div class="form-group col-md-6">
                <label>Current Mileage</label>
                <input type="number" class="form-control" name="mileage" value="{{ $vehicle->mileage }}">
            </div>
            <div class="form-group col-md-6">
                <label>Color</label>
                <input class="form-control" name="color" value="{{ $vehicle->color }}">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Changes</button>
    </div>
</form>

<script>
(function () {
    var input = document.getElementById('make_model_e');
    var make = document.getElementById('make_e');
    var model = document.getElementById('model_e');
    if (input && make && model) {
        input.addEventListener('input', function () {
            var txt = input.value.trim();
            var parts = txt.split(/\s+/);
            if (/^\d{4}$/.test(parts[0])) parts.shift();
            make.value = parts[0] || '';
            model.value = parts.slice(1).join(' ') || '';
        });
    }
})();
</script>
