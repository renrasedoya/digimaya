<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogoWallItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LogoWallController extends Controller
{
    private const PER_PAGE = 50;
    private const GROUP_REGEX = '/^[a-z0-9_]+$/';

    public function index(Request $request)
    {
        $query = LogoWallItem::query()->ordered();

        $groupFilter = trim((string) $request->query('group', ''));
        if ($groupFilter !== '' && $groupFilter !== 'all') {
            $query->where('group', $groupFilter);
        }

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $items = $query->paginate(self::PER_PAGE)->withQueryString();

        // Group counts for tab bar (always show all groups, even if filter applied)
        $groupCounts = LogoWallItem::selectRaw('`group`, COUNT(*) as count')
            ->groupBy('group')
            ->orderBy('group')
            ->pluck('count', 'group')
            ->toArray();

        $totalCount = array_sum($groupCounts);

        // Existing groups (for datalist auto-suggest in modal)
        $existingGroups = array_keys($groupCounts);

        return view('admin.logo-wall.index', [
            'items'          => $items,
            'groupFilter'    => $groupFilter,
            'search'         => $search,
            'groupCounts'    => $groupCounts,
            'totalCount'     => $totalCount,
            'existingGroups' => $existingGroups,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        $imagePath = $this->handleImage(
            $request->file('image_file'),
            $request->input('image_url'),
            null
        );

        LogoWallItem::create([
            'name'           => $data['name'],
            'image'          => $imagePath,
            'group'          => $data['group'],
            'is_active'      => $data['is_active'],
            'position_order' => $data['position_order'],
            'created_by'     => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.logo-wall.index')
            ->with('success', 'Logo wall item added successfully.');
    }

    public function update(Request $request, LogoWallItem $logoWallItem): RedirectResponse
    {
        // Quick toggle path
        if ($request->has('toggle_only')) {
            $logoWallItem->update(['is_active' => $request->boolean('is_active')]);
            return back()->with('success', 'Logo wall item status updated.');
        }

        $data = $this->validateRequest($request);

        $imagePath = $this->handleImage(
            $request->file('image_file'),
            $request->input('image_url'),
            $logoWallItem->image
        );

        $logoWallItem->update([
            'name'           => $data['name'],
            'image'          => $imagePath,
            'group'          => $data['group'],
            'is_active'      => $data['is_active'],
            'position_order' => $data['position_order'],
        ]);

        return redirect()
            ->route('admin.logo-wall.index')
            ->with('success', 'Logo wall item updated successfully.');
    }

    public function destroy(LogoWallItem $logoWallItem): RedirectResponse
    {
        if ($logoWallItem->image && ! $logoWallItem->imageIsExternal()) {
            Storage::disk('public')->delete($logoWallItem->image);
        }

        $logoWallItem->delete();

        return redirect()
            ->route('admin.logo-wall.index')
            ->with('success', 'Logo wall item deleted.');
    }

    // ============== Private helpers ==============

    private function validateRequest(Request $request): array
    {
        if ($request->has('toggle_only')) {
            return $request->validate([
                'is_active' => 'required|boolean',
            ]);
        }

        return $request->validate([
            'name'           => 'required|string|max:255',
            'group'          => ['required', 'string', 'max:50', 'regex:' . self::GROUP_REGEX],
            'image_file'     => 'nullable|image|max:1024', // 1MB
            'image_url'      => 'nullable|url|max:500',
            'is_active'      => 'nullable|boolean',
            'position_order' => 'nullable|integer|min:0|max:9999',
        ], [
            'group.regex' => 'Group must contain only lowercase letters, numbers, and underscores (e.g. clients, badges, social_proof).',
        ]) + [
            'is_active'      => $request->boolean('is_active'),
            'position_order' => (int) $request->input('position_order', 0),
        ];
    }

    private function handleImage(?UploadedFile $file, ?string $url, ?string $existingPath = null): ?string
    {
        if ($file) {
            $path = $file->store('logo-wall', 'public');
            if ($existingPath && ! str_starts_with($existingPath, 'http')) {
                Storage::disk('public')->delete($existingPath);
            }
            return $path;
        }

        if ($url) {
            if ($existingPath && ! str_starts_with($existingPath, 'http')) {
                Storage::disk('public')->delete($existingPath);
            }
            return $url;
        }

        return $existingPath;
    }
}
