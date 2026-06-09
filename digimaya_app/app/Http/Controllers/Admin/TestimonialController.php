<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    private const PER_PAGE = 20;

    /**
     * List all testimonials with pagination.
     */
    public function index(Request $request)
    {
        $query = Testimonial::query()->ordered();

        // Optional filter: active state
        $statusFilter = $request->query('status');
        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Optional search
        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('company', 'like', $like)
                  ->orWhere('quote', 'like', $like);
            });
        }

        $testimonials = $query->paginate(self::PER_PAGE)->withQueryString();

        return view('admin.testimonials.index', [
            'testimonials' => $testimonials,
            'statusFilter' => $statusFilter,
            'search'       => $search,
        ]);
    }

    /**
     * Store a new testimonial.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        $photo = $this->handlePhoto(
            $request->file('photo_file'),
            $request->input('photo_url'),
            null
        );

        [$clientId, $company] = $this->resolveCompanyPicker(
            $request->input('company_picker'),
            $request->input('company') // text fallback if needed
        );

        Testimonial::create([
            'name'           => $data['name'],
            'position'       => $data['position'],
            'company'        => $company,
            'client_id'      => $clientId,
            'photo'          => $photo,
            'quote'          => $data['quote'],
            'rating'         => $data['rating'],
            'is_active'      => $data['is_active'],
            'position_order' => $data['position_order'],
            'created_by'     => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial added successfully.');
    }

    /**
     * Update an existing testimonial.
     */
    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $this->validateRequest($request);

        // Quick toggle path: only is_active in payload (used by inline toggle button)
        if ($request->has('toggle_only')) {
            $testimonial->update(['is_active' => $request->boolean('is_active')]);
            return back()->with('success', 'Testimonial status updated.');
        }

        $photo = $this->handlePhoto(
            $request->file('photo_file'),
            $request->input('photo_url'),
            $testimonial->photo
        );

        [$clientId, $company] = $this->resolveCompanyPicker(
            $request->input('company_picker'),
            $request->input('company')
        );

        $testimonial->update([
            'name'           => $data['name'],
            'position'       => $data['position'],
            'company'        => $company,
            'client_id'      => $clientId,
            'photo'          => $photo,
            'quote'          => $data['quote'],
            'rating'         => $data['rating'],
            'is_active'      => $data['is_active'],
            'position_order' => $data['position_order'],
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Soft-delete a testimonial.
     */
    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        // Cleanup internal photo file (skip external URLs)
        if ($testimonial->photo && ! $testimonial->photoIsExternal()) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted.');
    }

    // ============== Private helpers ==============

    /**
     * Validate request payload — handles both full form & quick toggle.
     */
    private function validateRequest(Request $request): array
    {
        // Quick toggle: only is_active needs validation
        if ($request->has('toggle_only')) {
            return $request->validate([
                'is_active' => 'required|boolean',
            ]);
        }

        return $request->validate([
            'name'           => 'required|string|max:255',
            'position'       => 'nullable|string|max:255',
            'company_picker' => 'nullable|string|max:255',
            'quote'          => 'required|string|max:5000',
            'rating'         => 'nullable|integer|min:1|max:5',
            'photo_file'     => 'nullable|image|max:2048', // 2MB
            'photo_url'      => 'nullable|url|max:500',
            'is_active'      => 'nullable|boolean',
            'position_order' => 'nullable|integer|min:0|max:9999',
        ]) + [
            'is_active'      => $request->boolean('is_active'),
            'position_order' => (int) $request->input('position_order', 0),
        ];
    }

    /**
     * Resolve the Tom Select company_picker value into [client_id, company_text].
     *
     * The picker value can be either:
     *  - A numeric client ID (existing client selected from dropdown)
     *  - A free-text string (typed by user, no matching client)
     *  - Empty/null (no company)
     *
     * Returns [int|null $clientId, string|null $companyText].
     * For matched client: snapshot the client name into company text (Q2 decision: frozen snapshot).
     */
    private function resolveCompanyPicker(?string $picker, ?string $fallbackText = null): array
    {
        $value = trim((string) $picker);

        if ($value === '') {
            return [null, null];
        }

        // Numeric → check if it matches an existing client
        if (ctype_digit($value)) {
            $client = \App\Models\Client::find((int) $value);
            if ($client) {
                return [$client->id, $client->business_name];
            }
        }

        // Free-text fallback (or numeric that doesn't match a client → treat as text)
        return [null, $value];
    }

    /**
     * Handle photo upload OR external URL (hybrid pattern from BlogPostController).
     *
     * Priority:
     *  1. New file uploaded → store, cleanup old internal file if any.
     *  2. External URL provided → use as-is, cleanup old internal file if any.
     *  3. No new input → keep existing.
     */
    private function handlePhoto(?UploadedFile $file, ?string $url, ?string $existingPath = null): ?string
    {
        // 1. New file upload takes priority
        if ($file) {
            $path = $file->store('testimonials', 'public');
            // Cleanup old internal file (skip external URLs)
            if ($existingPath && ! str_starts_with($existingPath, 'http')) {
                Storage::disk('public')->delete($existingPath);
            }
            return $path;
        }

        // 2. External URL
        if ($url) {
            // Cleanup old internal file if switching from upload to URL
            if ($existingPath && ! str_starts_with($existingPath, 'http')) {
                Storage::disk('public')->delete($existingPath);
            }
            return $url;
        }

        // 3. No new input — keep existing
        return $existingPath;
    }
}