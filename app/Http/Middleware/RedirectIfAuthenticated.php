<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('admin')->check()) {
            return redirect('dashboardadmin');
        }
        if (Auth::guard('web')->check()) {
            return redirect('dashboard');
        }
        return $next($request);
    }
}
