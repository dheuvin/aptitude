<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
        public function index()
        {
            $users = User::withCount('testAttempts')
                ->orderByRaw("role = 'admin' DESC")
                ->orderBy('created_at', 'desc')
                ->orderBy('phone')
                ->get();

            $assignableUsers = $users->where('role', 'user')->values();
            $tests = Test::latest()->get();

            return view('admin.users.index', compact('users', 'assignableUsers', 'tests'));
        }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'role' => ['required', 'in:admin,user'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $plainPassword = $validated['password'] ?: $this->generatePassword();

        User::create([
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($plainPassword),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.')
            ->with('generated_password', $plainPassword);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,'.$user->id],
            'role' => ['required', 'in:admin,user'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user->phone = $validated['phone'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if ((int) session('user_id') === $user->id) {
            session([
                'phone' => $user->phone,
                'role' => $user->role,
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ((int) session('user_id') === $user->id) {
            return back()->with('error', 'You cannot delete the account you are currently using.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'At least one admin account must remain.');
        }

        // if ($user->testAttempts()->exists()) {
        //     return back()->with('error', 'Users with test history cannot be deleted.');
        // }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    private function generatePassword(): string
    {
        return (string) random_int(100000, 999999);
    }
}
