<div class="modal-header">
    <h5 class="modal-title">Edit User</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="form-group col-md-6">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>
            <div class="form-group col-md-6">
                <label>Role <span class="text-danger">*</span></label>
                <select name="role" class="form-control" required>
                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                    <option value="mechanic" @selected($user->role === 'mechanic')>Mechanic</option>
                    <option value="owner" @selected($user->role === 'owner')>Customer</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" @selected(($user->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(($user->status ?? 'active') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
            </div>
            <div class="form-group col-md-6">
                <label>New Password <small class="text-muted">(optional)</small></label>
                <input type="text" name="password" class="form-control" placeholder="Leave blank to keep current">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Changes</button>
    </div>
</form>
