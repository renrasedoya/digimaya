<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Path-based redirect: member area uses /login, admin area uses /admin/login
        if ($request->is('academy', 'academy/*')) {
            return route('member.login');
        }

        return route('login');
    }
}
