<?php

namespace App\Http\Controllers\Admin\Academy;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Module::query();

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $modules = $query
            ->withCount('materials')
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.academy.modules.index', compact('modules'));
    }

    public function create(): View
    {
        return view('admin.academy.modules.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        $coverImage = $this->handleImage(
            $request->file('cover_image_file'),
            $request->input('cover_image_url'),
            null
        );

        $module = Module::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverImage,
            'display_order' => $validated['display_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
            'tier' => $validated['tier'],
        ]);

        return redirect()
            ->route('admin.academy.modules.show', $module)
            ->with('success', 'Module berhasil dibuat.');
    }

    public function show(Module $module): View
    {
        $module->load(['materials' => fn($q) => $q->orderBy('display_order')->orderBy('id')]);
        return view('admin.academy.modules.show', compact('module'));
    }

    public function edit(Module $module): View
    {
        return view('admin.academy.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module): RedirectResponse
    {
        $validated = $this->validateRequest($request, $module);

        $coverImage = $this->handleImage(
            $request->file('cover_image_file'),
            $request->input('cover_image_url'),
            $module->cover_image,
            $request->boolean('remove_cover_image')
        );

        $module->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? $module->slug,
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverImage,
            'display_order' => $validated['display_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
            'tier' => $validated['tier'],
        ]);

        return redirect()
            ->route('admin.academy.modules.show', $module)
            ->with('success', 'Module berhasil diupdate.');
    }

    public function destroy(Module $module): RedirectResponse
    {
        // Delete local cover image if exists
        if ($module->cover_image && !$module->coverImageIsExternal()) {
            Storage::disk('public')->delete($module->cover_image);
        }

        $module->delete();

        return redirect()
            ->route('admin.academy.modules.index')
            ->with('success', 'Module berhasil dihapus beserta materinya.');
    }

    // ============== Private helpers ==============

    private function validateRequest(Request $request, ?Module $module = null): array
    {
        \App\Services\UrlNormalizer::normalizeRequest($request, ['cover_image_url']);
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255',
                $module ? Rule::unique('modules')->ignore($module->id) : 'unique:modules,slug',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'
            ],
            'description' => ['nullable', 'string'],
            'cover_image_file' => ['nullable', 'image', 'max:1024'],
            'cover_image_url' => ['nullable', 'url', 'max:500'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['nullable', 'boolean'],
            'tier' => ['required', Rule::in(Module::TIERS)],
        ], [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung (-).',
            'cover_image_file.image' => 'Cover image harus file gambar (JPG, PNG, GIF, WebP).',
            'cover_image_file.max' => 'Cover image maksimum 1MB.',
            'cover_image_url.url' => 'Cover image URL harus URL valid (mulai dengan http/https).',
            'tier.required' => 'Tier wajib dipilih.',
            'tier.in' => 'Tier harus Free atau Paid.',
        ]);
    }

    private function handleImage(?UploadedFile $file, ?string $url, ?string $existingPath = null, bool $remove = false): ?string
    {
        // Explicit removal
        if ($remove) {
            if ($existingPath && !str_starts_with($existingPath, 'http')) {
                Storage::disk('public')->delete($existingPath);
            }
            return null;
        }

        // New file upload
        if ($file) {
            $path = $file->store('modules', 'public');
            if ($existingPath && !str_starts_with($existingPath, 'http')) {
                Storage::disk('public')->delete($existingPath);
            }
            return $path;
        }

        // External URL
        if ($url) {
            if ($existingPath && !str_starts_with($existingPath, 'http')) {
                Storage::disk('public')->delete($existingPath);
            }
            return $url;
        }

        return $existingPath;
    }
}
