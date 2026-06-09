<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // check login
        if (!session()->has('user_id')) {
            return redirect('/');
        }

        // check role
        if (session('role') !== $role) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
