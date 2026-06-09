<?php

namespace App\Http\Controllers\Auth\Member;

use App\Http\Controllers\Controller;
use App\Mail\Academy\PasswordResetRequest;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MemberForgotPasswordController extends Controller
{
    /**
     * Show the forgot-password form.
     */
    public function create(): View
    {
        return view('auth.member.forgot-password');
    }

    /**
     * Process forgot-password request.
     * For security, ALWAYS show generic success message regardless of whether
     * email exists in DB (prevents email enumeration).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $this->ensureIsNotRateLimited($request);

        $member = Member::where('email', $request->email)->first();

        // Send email only if member exists AND active. But always show same generic message.
        if ($member && $member->is_active) {
            $token = $member->generateSetupToken();
            $this->sendResetEmail($member, $token);
        }

        RateLimiter::hit($this->throttleKey($request));

        return redirect()->route('member.password.request')
            ->with('status', 'Kalau email tersebut terdaftar di Digimaya Academy, kami sudah kirim link reset password ke inbox. Cek email kamu (termasuk folder spam) dalam 1-2 menit.');
    }

    /**
     * Send reset password email. Wrapped try/catch to never break flow.
     */
    private function sendResetEmail(Member $member, string $token): void
    {
        try {
            Mail::to($member->email)->send(new PasswordResetRequest($member, $token));
        } catch (\Throwable $e) {
            Log::error('Member password reset email failed', [
                'member_id' => $member->id,
                'email' => $member->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Rate limit: 3 requests per email per 15 minutes.
     */
    private function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 3)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        $minutes = ceil($seconds / 60);

        abort(429, "Terlalu banyak request. Coba lagi dalam {$minutes} menit.");
    }

    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email', '')) . '|reset|' . $request->ip());
    }
}
