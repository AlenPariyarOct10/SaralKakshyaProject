<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAllowedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->deleted_at !== null) {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors(['Your account has been deleted by super-admin.']);
            }

            if ($user->is_approved == 0) {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors(['Your account is not approved yet.']);
            }


        }

        return $next($request);
    }


}
