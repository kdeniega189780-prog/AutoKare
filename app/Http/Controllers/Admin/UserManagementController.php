<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $role = trim((string) $request->query('role', ''));

        $query = User::query()->orderBy('name');
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('role', 'like', "%{$q}%");
            });
        }
        if (in_array($role, ['admin', 'mechanic', 'owner'], true)) {
            $query->where('role', $role);
        }

        $users = $query->paginate(20)->appends(['q' => $q, 'role' => $role]);

        $counts = [
            'admin' => User::where('role', 'admin')->count(),
            'mechanic' => User::where('role', 'mechanic')->count(),
            'owner' => User::where('role', 'owner')->count(),
        ];

        return view('admin.users.index', compact('users', 'q', 'role', 'counts'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function createModal(): View
    {
        $view = $this->create();
        return view('modals.admin.users.create', $view->getData());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'owner', 'mechanic'])],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'phone' => ['nullable', 'string', 'max:32'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'role' => $validated['role'],
            'status' => $validated['status'] ?? 'active',
            'current_status' => $validated['role'] === 'mechanic' ? 'working' : 'off_duty',
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User created.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function editModal(User $user): View
    {
        $view = $this->edit($user);
        return view('modals.admin.users.edit', $view->getData());
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'owner', 'mechanic'])],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'phone' => ['nullable', 'string', 'max:32'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->status = $validated['status'] ?? $user->status ?? 'active';

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }

    public function suggest(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 1) {
            return response()->json([]);
        }

        $users = User::query()
            ->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('role', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'email', 'role']);

        return response()->json($users->map(fn ($u) => [
            'value' => $u->name,
            'primary' => $u->name,
            'secondary' => $u->email,
            'meta' => $u->role === 'owner' ? 'Customer' : ucfirst((string) $u->role),
        ]));
    }

    public function searchCustomers(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        $users = User::query()
            ->where('role', 'owner')
            ->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json(
            $users->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone,
            ])
        );
    }
}
