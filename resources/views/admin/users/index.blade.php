<x-app-layout>
    <div class="vms-page-header">
        <div>
            <h1 class="vms-page-title">User Management</h1>
            <p class="vms-page-subtitle">Manage admin, mechanic and customer accounts</p>
        </div>
        <button type="button" class="btn btn-dark"
                data-modal-url="{{ route('admin.users.createModal') }}">
            <i class="fas fa-user-plus mr-1"></i> Add User
        </button>
    </div>

    <div class="vms-banner vms-banner-info">
        <div class="d-flex align-items-start">
            <i class="fas fa-info-circle mr-3 mt-1 text-info"></i>
            <div>
                <h5>Why Create Customer Accounts?</h5>
                <p>
                    Every customer gets an <strong>online portal account</strong> to track service history and book future appointments.
                    For <strong>walk-in customers</strong>, create their account here first (takes 30 seconds), then add their vehicle and book appointments.
                    This gives them immediate online access and improves customer retention.
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="vms-role-tile">
                <div class="vms-role-tile-icon bg-danger"><i class="fas fa-user-shield"></i></div>
                <div>
                    <div class="vms-role-tile-label">Admins</div>
                    <div class="vms-role-tile-value">{{ $counts['admin'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="vms-role-tile">
                <div class="vms-role-tile-icon bg-success"><i class="fas fa-wrench"></i></div>
                <div>
                    <div class="vms-role-tile-label">Mechanics</div>
                    <div class="vms-role-tile-value">{{ $counts['mechanic'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="vms-role-tile">
                <div class="vms-role-tile-icon bg-purple"><i class="fas fa-user"></i></div>
                <div>
                    <div class="vms-role-tile-label">Customers</div>
                    <div class="vms-role-tile-value">{{ $counts['owner'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary mt-3">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.users.index') }}" class="form-inline w-100">
                <div class="form-row align-items-center w-100">
                    <div class="col-md-9 mb-2 mb-md-0">
                        <div class="input-group vms-suggest-wrap">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                            <input type="text" name="q" value="{{ $q }}" placeholder="Search users by name, email, or role..." class="form-control"
                                   data-suggest-url="{{ route('admin.users.suggest') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-control" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            <option value="admin" @selected($role === 'admin')>Admin</option>
                            <option value="mechanic" @selected($role === 'mechanic')>Mechanic</option>
                            <option value="owner" @selected($role === 'owner')>Customer</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover vms-light mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php
                            $initials = collect(explode(' ', $user->name))->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('');
                            $bg = match ($user->role) {
                                'admin' => 'bg-danger',
                                'mechanic' => 'bg-success',
                                default => 'bg-purple',
                            };
                            $roleBadge = match ($user->role) {
                                'admin' => 'badge-danger',
                                'mechanic' => 'badge-success',
                                default => 'badge-purple',
                            };
                            $roleLabel = $user->role === 'owner' ? 'Customer' : ucfirst($user->role);
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="vms-role-tile-icon {{ $bg }} mr-2" style="width:36px;height:36px;font-size:13px;">{{ $initials }}</div>
                                    <div>
                                        <div class="font-weight-bold text-dark">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge {{ $roleBadge }} p-2">{{ $roleLabel }}</span></td>
                            <td>
                                @if (($user->status ?? 'active') === 'active')
                                    <span class="badge badge-success p-2">Active</span>
                                @else
                                    <span class="badge badge-secondary p-2">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $user->last_login_at?->format('Y-m-d') ?? '—' }}</td>
                            <td class="text-right text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-modal-url="{{ route('admin.users.editModal', $user) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline-block"
                                      onsubmit="return confirm('Delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="card-footer">{{ $users->links() }}</div>
        @endif
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="vms-info-card">
                <div class="vms-info-card-title text-danger"><i class="fas fa-user-shield mr-1"></i> Admin Role</div>
                <ul>
                    <li>Full system access</li>
                    <li>User management</li>
                    <li>System configuration</li>
                    <li>All reports and analytics</li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="vms-info-card">
                <div class="vms-info-card-title text-success"><i class="fas fa-wrench mr-1"></i> Mechanic Role</div>
                <ul>
                    <li>View assigned tasks</li>
                    <li>Update task status</li>
                    <li>Add work notes and reports</li>
                    <li>View task history</li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="vms-info-card">
                <div class="vms-info-card-title text-purple"><i class="fas fa-user mr-1"></i> Customer Role</div>
                <ul>
                    <li>View own vehicles</li>
                    <li>Book appointments</li>
                    <li>View service history</li>
                    <li>Request quotes</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
