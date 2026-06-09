<?php

namespace App\Http\Controllers\Admin\Academy;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialController extends Controller
{
    /**
     * Store a newly created material under given module.
     */
    public function store(Request $request, Module $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'youtube_id' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z0-9_-]{11}$/'],
            'notes' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'youtube_id.regex' => 'YouTube ID harus 11 karakter (huruf, angka, _, atau -).',
        ]);

        $module->materials()->create([
            'title' => $validated['title'],
            'youtube_id' => $validated['youtube_id'],
            'notes' => isset($validated['notes']) ? Purifier::clean($validated['notes']) : null,
            'display_order' => $validated['display_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.academy.modules.show', $module)
            ->with('success', 'Material berhasil ditambahkan.');
    }

    /**
     * Show edit form for a material.
     */
    public function edit(Module $module, Material $material): View
    {
        // Defensive: ensure material belongs to this module (route binding doesn't enforce this)
        abort_if($material->module_id !== $module->id, 404);

        return view('admin.academy.materials.edit', compact('module', 'material'));
    }

    /**
     * Update material.
     */
    public function update(Request $request, Module $module, Material $material): RedirectResponse
    {
        abort_if($material->module_id !== $module->id, 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'youtube_id' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z0-9_-]{11}$/'],
            'notes' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'youtube_id.regex' => 'YouTube ID harus 11 karakter (huruf, angka, _, atau -).',
        ]);

        $material->update([
            'title' => $validated['title'],
            'youtube_id' => $validated['youtube_id'],
            'notes' => isset($validated['notes']) ? Purifier::clean($validated['notes']) : null,
            'display_order' => $validated['display_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.academy.modules.show', $module)
            ->with('success', 'Material berhasil diupdate.');
    }

    /**
     * Delete material.
     */
    public function destroy(Module $module, Material $material): RedirectResponse
    {
        abort_if($material->module_id !== $module->id, 404);

        $material->delete();

        return redirect()
            ->route('admin.academy.modules.show', $module)
            ->with('success', 'Material berhasil dihapus.');
    }
}
