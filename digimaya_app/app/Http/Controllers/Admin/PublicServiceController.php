<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicServiceController extends Controller
{
    public function index()
    {
        $services = PublicService::ordered()->get();

        return view('admin.public-services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.public-services.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['icon_image'] = $this->handleIconUpload($request);
        $data['is_active'] = $request->boolean('is_active');

        PublicService::create($data);

        return redirect()->route('admin.public-services.index')
            ->with('success', 'Service berhasil dibuat.');
    }

    public function edit(PublicService $publicService)
    {
        return view('admin.public-services.edit', ['service' => $publicService]);
    }

    public function update(Request $request, PublicService $publicService)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        $newIcon = $this->handleIconUpload($request);
        if ($newIcon !== null) {
            if ($publicService->icon_image) {
                Storage::disk('public')->delete($publicService->icon_image);
            }
            $data['icon_image'] = $newIcon;
        }

        if ($request->boolean('remove_icon_image') && $publicService->icon_image) {
            Storage::disk('public')->delete($publicService->icon_image);
            $data['icon_image'] = null;
        }

        $publicService->update($data);

        return redirect()->route('admin.public-services.index')
            ->with('success', 'Service berhasil diupdate.');
    }

    public function destroy(PublicService $publicService)
    {
        if ($publicService->icon_image) {
            Storage::disk('public')->delete($publicService->icon_image);
        }

        $publicService->delete();

        return redirect()->route('admin.public-services.index')
            ->with('success', 'Service berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'icon_url' => 'nullable|url|max:500',
            'position' => 'nullable|integer|min:0',
        ]);
    }

    private function handleIconUpload(Request $request): ?string
    {
        if (!$request->hasFile('icon_image')) {
            return null;
        }

        $request->validate([
            'icon_image' => 'image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        return $request->file('icon_image')->store('public-services', 'public');
    }
}
