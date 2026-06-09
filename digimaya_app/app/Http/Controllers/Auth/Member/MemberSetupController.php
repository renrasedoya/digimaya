<?php

namespace App\Http\Controllers\Auth\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MemberSetupController extends Controller
{
    /**
     * Show the setup-password form (or password-reset form, since single system).
     * Triggered by clicking link in welcome email or admin-shared setup link.
     */
    public function show(string $token): View
    {
        $member = Member::where('setup_token', $token)->first();

        if (!$member || !$member->isSetupTokenValid()) {
            abort(404, 'Setup link sudah expired atau tidak valid. Hubungi admin Digimaya untuk minta link baru.');
        }

        return view('auth.member.setup', [
            'member' => $member,
            'token' => $token,
        ]);
    }

    /**
     * Process the setup form: set password, clear token, auto-login.
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimum 8 karakter.',
        ]);

        $member = Member::where('setup_token', $token)->first();

        if (!$member || !$member->isSetupTokenValid()) {
            abort(404, 'Setup link sudah expired atau tidak valid.');
        }

        if (!$member->is_active) {
            abort(403, 'Akun ini sedang non-aktif. Hubungi admin Digimaya.');
        }

        // Set password (auto-hashed via 'hashed' cast)
        $member->password = $request->password;
        $member->save();

        // Clear setup token
        $member->clearSetupToken();

        // Auto-login via member guard
        Auth::guard('member')->login($member);

        $request->session()->regenerate();

        return redirect()->route('academy.dashboard')
            ->with('status', 'Password berhasil di-set. Selamat datang di Digimaya Academy!');
    }
}
