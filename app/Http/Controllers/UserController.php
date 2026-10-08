<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of registered users and their roles.
     */
    public function index(): View
    {
        $users = User::with('roles')->latest()->paginate(15);
        $roles = Role::orderBy('name')->get();

        return view('users.manage', compact('users', 'roles'));
    }

    /**
     * Store a newly created staff account (Admin only).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => "Admin created new staff account for {$user->name} ({$user->email}) with role '{$validated['role']}'",
            'target_table' => 'users',
            'target_id' => $user->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.manage')->with('success', "Staff account for {$user->name} ({$user->email}) created successfully.");
    }

    /**
     * Update the role of a specific user.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $newRole = $validated['role'];

        // Do not allow an admin to remove their own admin role
        if ($user->id === auth()->id() && $user->hasRole('admin') && $newRole !== 'admin') {
            abort(403, 'You cannot remove your own admin role.');
        }

        $oldRole = $user->roles->pluck('name')->first() ?? 'none';

        $user->syncRoles([$newRole]);

        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => "Changed role of {$user->name} (User #{$user->id}) from '{$oldRole}' to '{$newRole}'",
            'target_table' => 'users',
            'target_id' => $user->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.manage')->with('success', "Role for {$user->name} updated from '{$oldRole}' to '{$newRole}' successfully.");
    }
}
