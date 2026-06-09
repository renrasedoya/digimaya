<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMemberIsActive
{
    /**
     * Handle an incoming request.
     *
     * Logs out a member whose account has been deactivated by admin.
     * Run AFTER 'auth:member' middleware.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $member = Auth::guard('member')->user();

        if ($member && !$member->is_active) {
            Auth::guard('member')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('member.login')
                ->withErrors(['email' => 'Akun kamu sedang non-aktif. Hubungi admin Digimaya.']);
        }

        return $next($request);
    }
}
