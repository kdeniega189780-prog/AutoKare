@php
    $asPage = $asPage ?? false;
@endphp

@if ($asPage)
    <div class="vms-page-header mb-3">
        <div>
            <h1 class="vms-page-title">Vehicle Details</h1>
        </div>
        <a href="{{ route('vehicles.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Back to vehicles
        </a>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Vehicle Details</h5>
        <button type="button" class="close" data-modal-close data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
    </div>
@endif

<div class="{{ $asPage ? 'p-3' : 'modal-body' }}">
    <div class="row">
        <div class="form-group col-md-12">
            <label>Vehicle Make &amp; Model</label>
            <div class="form-control bg-light">{{ $vehicle->displayLabel() }}</div>
        </div>
        <div class="form-group col-md-6">
            <label>License Plate</label>
            <div class="form-control bg-light">{{ $vehicle->license_plate }}</div>
        </div>
        <div class="form-group col-md-6">
            <label>Year</label>
            <div class="form-control bg-light">{{ $vehicle->year }}</div>
        </div>
        <div class="form-group col-md-6">
            <label>Type</label>
            <div class="form-control bg-light">{{ $vehicle->type ?? '—' }}</div>
        </div>
        <div class="form-group col-md-6">
            <label>Current Mileage</label>
            <div class="form-control bg-light">{{ $vehicle->mileage !== null ? number_format($vehicle->mileage) . ' mi' : '—' }}</div>
        </div>
        @if ($vehicle->color)
            <div class="form-group col-md-6">
                <label>Color</label>
                <div class="form-control bg-light">{{ $vehicle->color }}</div>
            </div>
        @endif
        @if ($vehicle->owner)
            <div class="form-group col-md-6">
                <label>Owner</label>
                <div class="form-control bg-light">{{ $vehicle->owner->name }}</div>
            </div>
        @endif
    </div>
</div>

@if ($asPage)
    <div class="p-3 border-top">
        <a href="{{ route('vehicles.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left mr-1"></i> Back to vehicles
        </a>
    </div>
@else
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-modal-close data-dismiss="modal">Close</button>
    </div>
@endif
