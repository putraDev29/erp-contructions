<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('role')->latest()->paginate(15);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
        ]);

        ActivityLog::record(
            'created',
            'User',
            $user->id,
            "Membuat akun pengguna baru {$user->name} ({$user->email}) dengan peran {$user->role?->display_name}"
        );

        return back()->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', "Anda tidak dapat menonaktifkan akun Anda sendiri!");
        }

        $user->is_active = !$user->is_active;
        $user->save();

        ActivityLog::record(
            'updated',
            'User',
            $user->id,
            "Mengubah status keaktifan akun {$user->name} menjadi " . ($user->is_active ? 'AKTIF' : 'NONAKTIF')
        );

        return back()->with('success', "Status akun {$user->name} berhasil diperbarui.");
    }
}
