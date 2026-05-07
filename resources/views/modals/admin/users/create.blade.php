<div class="modal-header">
    <h5 class="modal-title">Add New User</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="modal-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="form-group col-md-6">
                <label>Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Enter name" required value="{{ old('name') }}">
            </div>
            <div class="form-group col-md-6">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="user@example.com" required value="{{ old('email') }}">
            </div>
            <div class="form-group col-md-6">
                <label>Role <span class="text-danger">*</span></label>
                <select name="role" class="form-control" required>
                    <option value="">Select role</option>
                    <option value="admin">Admin</option>
                    <option value="mechanic">Mechanic</option>
                    <option value="owner">Customer</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" placeholder="e.g. (555) 123-4567" value="{{ old('phone') }}">
            </div>
            <div class="form-group col-md-6">
                <label>Temporary Password <span class="text-danger">*</span></label>
                <input type="text" name="password" class="form-control" placeholder="Generate or enter password" required minlength="6">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus mr-1"></i> Create User</button>
    </div>
</form>
