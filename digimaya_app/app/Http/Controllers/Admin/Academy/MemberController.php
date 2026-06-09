<?php

namespace App\Http\Controllers\Admin\Academy;

use App\Http\Controllers\Controller;
use App\Mail\Academy\WelcomeMember;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index(Request $request): View
    {
        $query = Member::with('enroller:id,name');

        // Filter by status (active / inactive / all)
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by tier (free / paid)
        if ($request->filled('tier') && in_array($request->tier, Member::TIERS, true)) {
            $query->where('tier', $request->tier);
        }

        // Filter by enrolled_by (admin who enrolled)
        if ($request->filled('enrolled_by')) {
            $query->where('enrolled_by', $request->enrolled_by);
        }

        // Filter by enrolled date (month + year) - applied to created_at
        $month = (int) $request->input('month', 0);
        $year = (int) $request->input('year', now()->year);
        if ($month > 0) {
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        } elseif ($request->filled('year')) {
            $query->whereYear('created_at', $year);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // For filter dropdown: list of enrollers
        $enrollers = User::whereIn('id', Member::query()->whereNotNull('enrolled_by')->distinct()->pluck('enrolled_by'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.academy.members.index', compact('members', 'enrollers', 'month', 'year'));
    }

    /**
     * Show form to create a new member.
     */
    public function create(): View
    {
        return view('admin.academy.members.create');
    }

    /**
     * Store a newly enrolled member + send welcome email.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:members,email'],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
            'tier' => ['required', Rule::in(Member::TIERS)],
        ], [
            'tier.required' => 'Tier wajib dipilih.',
            'tier.in' => 'Tier harus Free atau Paid.',
        ]);

        $member = Member::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => null,
            'is_active' => $request->boolean('is_active', true),
            'notes' => $validated['notes'] ?? null,
            'enrolled_by' => $request->user()->id,
            'tier' => $validated['tier'],
        ]);

        // Generate setup token
        $token = $member->generateSetupToken();

        // Send welcome email (non-blocking — failure logged but does not abort flow)
        $emailSent = $this->sendWelcomeEmail($member, $token);

        $flashKey = $emailSent ? 'success' : 'warning';
        $flashMessage = $emailSent
            ? 'Member berhasil di-enroll. Welcome email sudah dikirim ke ' . $member->email . '.'
            : 'Member berhasil di-enroll, tapi email gagal dikirim. Copy setup link manual dari halaman ini.';

        return redirect()
            ->route('admin.academy.members.show', $member)
            ->with($flashKey, $flashMessage);
    }

    /**
     * Show member detail (read-only) with setup link if applicable.
     */
    public function show(Member $member): View
    {
        $member->load('enroller:id,name');

        $setupUrl = $member->isSetupTokenValid()
            ? route('member.setup', $member->setup_token)
            : null;

        return view('admin.academy.members.show', compact('member', 'setupUrl'));
    }

    /**
     * Show form to edit a member.
     */
    public function edit(Member $member): View
    {
        return view('admin.academy.members.edit', compact('member'));
    }

    /**
     * Update a member.
     */
    public function update(Request $request, Member $member): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('members')->ignore($member->id)],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
            'tier' => ['required', Rule::in(Member::TIERS)],
        ], [
            'tier.required' => 'Tier wajib dipilih.',
            'tier.in' => 'Tier harus Free atau Paid.',
        ]);

        $member->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $request->boolean('is_active'),
            'notes' => $validated['notes'] ?? null,
            'tier' => $validated['tier'],
        ]);

        return redirect()
            ->route('admin.academy.members.show', $member)
            ->with('success', 'Member berhasil diupdate.');
    }

    /**
     * Soft delete a member.
     */
    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()
            ->route('admin.academy.members.index')
            ->with('success', 'Member berhasil dihapus.');
    }

    /**
     * Resend the welcome email (regenerate token first to ensure fresh).
     */
    public function resendSetup(Member $member): RedirectResponse
    {
        if ($member->password) {
            return redirect()
                ->route('admin.academy.members.show', $member)
                ->with('warning', 'Member sudah punya password. Pakai "Regenerate Token" kalau mau force reset password.');
        }

        $token = $member->generateSetupToken();
        $emailSent = $this->sendWelcomeEmail($member, $token);

        $flashKey = $emailSent ? 'success' : 'warning';
        $flashMessage = $emailSent
            ? 'Welcome email berhasil dikirim ulang ke ' . $member->email . '.'
            : 'Email gagal dikirim. Copy setup link manual dari halaman ini.';

        return redirect()
            ->route('admin.academy.members.show', $member)
            ->with($flashKey, $flashMessage);
    }

    /**
     * Regenerate setup token (for force password reset). Sends email + shows link.
     */
    public function regenerateToken(Member $member): RedirectResponse
    {
        $token = $member->generateSetupToken();
        $emailSent = $this->sendWelcomeEmail($member, $token);

        $flashKey = $emailSent ? 'success' : 'warning';
        $flashMessage = $emailSent
            ? 'Setup token baru sudah di-generate. Email reset dikirim ke ' . $member->email . '.'
            : 'Setup token baru sudah di-generate, tapi email gagal dikirim. Copy link manual.';

        return redirect()
            ->route('admin.academy.members.show', $member)
            ->with($flashKey, $flashMessage);
    }

    /**
     * Toggle is_active boolean.
     */
    public function toggleActive(Member $member): RedirectResponse
    {
        $member->is_active = !$member->is_active;
        $member->save();

        $status = $member->is_active ? 'aktif' : 'non-aktif';
        return redirect()
            ->route('admin.academy.members.show', $member)
            ->with('success', "Member sekarang {$status}.");
    }

    /**
     * Helper: send WelcomeMember email, return success/fail bool.
     */
    private function sendWelcomeEmail(Member $member, string $token): bool
    {
        try {
            Mail::to($member->email)->send(new WelcomeMember($member, $token));
            return true;
        } catch (\Throwable $e) {
            Log::error('Academy welcome email failed', [
                'member_id' => $member->id,
                'email' => $member->email,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * AJAX search endpoint for autocomplete dropdowns.
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim($request->input('q', ''));
        $limit = min((int) $request->input('limit', 20), 50);

        $members = \App\Models\Member::active()
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($w) use ($query) {
                    $w->where('name', 'like', '%' . $query . '%')
                      ->orWhere('email', 'like', '%' . $query . '%');
                });
            })
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name', 'email'])
            ->map(fn ($m) => [
                'value' => (string) $m->id,
                'text' => $m->name . ' (' . $m->email . ')',
                'name' => $m->name,
                'email' => $m->email,
            ]);

        return response()->json($members);
    }

}
