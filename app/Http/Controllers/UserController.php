<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * Update the role of a specific user.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->syncRoles([$validated['role']]);

        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_user_role:' . $validated['role'],
            'target_table' => 'users',
            'target_id' => $user->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.manage')->with('success', "Role for {$user->name} updated to '{$validated['role']}' successfully.");
    }
}
