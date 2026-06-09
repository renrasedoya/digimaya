<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show profile page (read-only info + change password form).
     */
    public function edit(): View
    {
        $member = Auth::guard('member')->user();
        return view('academy.profile', compact('member'));
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimum 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
        ]);

        $member = Auth::guard('member')->user();

        // Verify current password
        if (!Hash::check($request->current_password, $member->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini salah.',
            ]);
        }

        // Update password (auto-hashed via 'hashed' cast)
        $member->password = $request->password;
        $member->save();

        return redirect()->route('academy.profile.edit')
            ->with('status', 'Password berhasil diupdate.');
    }
}
