<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session()->has('user_id')) {
            return $this->redirectToDashboard(session('role'));
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $throttleKey = Str::lower($credentials['phone']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('phone'))
                ->with('error', "Too many login attempts. Try again in {$seconds} seconds.");
        }

        $user = User::where('phone', $credentials['phone'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withInput($request->only('phone'))
                ->with('error', 'Invalid phone number or password.');
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        session([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'role' => $user->role,
        ]);

        return $this->redirectToDashboard($user->role);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectToDashboard(string $role)
    {
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }
}
