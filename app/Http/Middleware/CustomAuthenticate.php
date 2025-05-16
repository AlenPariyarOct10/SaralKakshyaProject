<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class CustomAuthenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if ($request->is('teacher') || $request->is('teacher/*')) {
                return route('teacher.login');
            }

            if ($request->is('student') || $request->is('student/*')) {
                return route('student.login');
            }

            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('superadmin') || $request->is('superadmin/*')) {
                return route('superadmin.login');
            }

            return route('login');
        }
    }
}
