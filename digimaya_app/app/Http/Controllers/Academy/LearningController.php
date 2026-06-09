<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MemberProgress;
use App\Models\BlogPost;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class LearningController extends Controller
{
    /**
     * Member dashboard — list published modules with progress.
     */
    public function dashboard(): View
    {
        $member = Auth::guard('member')->user();

        // All published modules with their published materials count
        $modules = Module::published()
            ->ordered()
            ->withCount(['materials' => fn($q) => $q->where('is_published', true)])
            ->get();

        // Get member's completed material IDs (for progress calc)
        $completedMaterialIds = MemberProgress::where('member_id', $member->id)
            ->pluck('material_id')
            ->toArray();

        // Compute progress per module
        $modules->each(function ($module) use ($completedMaterialIds) {
            $publishedMaterialIds = $module->materials()
                ->where('is_published', true)
                ->pluck('id')
                ->toArray();

            $totalCount = count($publishedMaterialIds);
            $completedCount = count(array_intersect($publishedMaterialIds, $completedMaterialIds));

            $module->total_published_materials = $totalCount;
            $module->completed_materials = $completedCount;
            $module->progress_percent = $totalCount > 0
                ? round(($completedCount / $totalCount) * 100)
                : 0;
        });

        return view('academy.dashboard', compact('member', 'modules'));
    }

    /**
     * Module detail page — list materials with completion status.
     */
    public function showModule(Module $module): View|\Illuminate\Http\RedirectResponse
    {
        $member = Auth::guard('member')->user();

        // Defensive: only show published modules
        abort_if(!$module->is_published, 404);

        // Tier gate: free member cannot access paid modules
        if (! $member->canAccessModule($module)) {
            return redirect()->route('academy.upgrade')
                ->with('warning', 'Module ini hanya untuk Paid member. Upgrade untuk akses.');
        }

        // Load published materials only, ordered
        $module->load(['materials' => fn($q) => $q->where('is_published', true)->orderBy('display_order')->orderBy('id')]);

        // Get completed material IDs for this member
        $completedIds = MemberProgress::where('member_id', $member->id)
            ->whereIn('material_id', $module->materials->pluck('id'))
            ->pluck('material_id')
            ->toArray();

        $totalCount = $module->materials->count();
        $completedCount = count($completedIds);
        $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;

        return view('academy.module', compact('member', 'module', 'completedIds', 'totalCount', 'completedCount', 'progressPercent'));
    }

    /**
     * Material detail page — YouTube embed + notes + mark complete + prev/next.
     */
    public function showMaterial(Module $module, Material $material): View|\Illuminate\Http\RedirectResponse
    {
        $member = Auth::guard('member')->user();

        // Defensive checks
        abort_if(!$module->is_published, 404);
        abort_if($material->module_id !== $module->id, 404);
        abort_if(!$material->is_published, 404);

        // Tier gate: free member cannot access paid modules
        if (! $member->canAccessModule($module)) {
            return redirect()->route('academy.upgrade')
                ->with('warning', 'Module ini hanya untuk Paid member. Upgrade untuk akses.');
        }

        // Get all published materials in this module (ordered) for prev/next nav
        $allMaterials = $module->materials()
            ->where('is_published', true)
            ->orderBy('display_order')
            ->orderBy('id')
            ->get(['id', 'title', 'display_order']);

        // Find prev/next based on current material position
        $currentIndex = $allMaterials->search(fn($m) => $m->id === $material->id);
        $prevMaterial = $currentIndex > 0 ? $allMaterials[$currentIndex - 1] : null;
        $nextMaterial = $currentIndex < ($allMaterials->count() - 1) ? $allMaterials[$currentIndex + 1] : null;

        // Check if current material is completed by member
        $isCompleted = MemberProgress::where('member_id', $member->id)
            ->where('material_id', $material->id)
            ->exists();

        // Get all completed material IDs (for sidebar list)
        $completedIds = MemberProgress::where('member_id', $member->id)
            ->whereIn('material_id', $allMaterials->pluck('id'))
            ->pluck('material_id')
            ->toArray();

        return view('academy.material', compact(
            'member', 'module', 'material', 'allMaterials',
            'prevMaterial', 'nextMaterial', 'isCompleted', 'completedIds'
        ));
    }

    /**
     * Toggle material completion (AJAX endpoint).
     */
    public function toggleProgress(Request $request, Material $material): JsonResponse
    {
        $member = Auth::guard('member')->user();

        // Defensive checks
        if (!$material->is_published) {
            return response()->json(['error' => 'Material not available'], 403);
        }

        $module = $material->module;
        if (!$module || !$module->is_published) {
            return response()->json(['error' => 'Module not available'], 403);
        }

        // Tier gate: free member cannot toggle progress on paid module materials
        if (! $member->canAccessModule($module)) {
            return response()->json(['error' => 'This module requires Paid membership'], 403);
        }

        // Toggle: kalau sudah complete, hapus. Kalau belum, tambah.
        $existing = MemberProgress::where('member_id', $member->id)
            ->where('material_id', $material->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isCompleted = false;
            $message = 'Material ditandai belum selesai.';
        } else {
            MemberProgress::create([
                'member_id' => $member->id,
                'material_id' => $material->id,
                'completed_at' => now(),
            ]);
            $isCompleted = true;
            $message = 'Material ditandai selesai.';
        }

        // Recompute module progress (untuk update UI realtime)
        $publishedMaterialIds = $module->materials()
            ->where('is_published', true)
            ->pluck('id');

        $completedCount = MemberProgress::where('member_id', $member->id)
            ->whereIn('material_id', $publishedMaterialIds)
            ->count();

        $totalCount = $publishedMaterialIds->count();
        $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;

        return response()->json([
            'success' => true,
            'is_completed' => $isCompleted,
            'message' => $message,
            'progress' => [
                'completed' => $completedCount,
                'total' => $totalCount,
                'percent' => $progressPercent,
            ],
        ]);
    }
    /**
     * Announcements feed — list of newly published modules, materials, and blog articles.
     * Merged + sorted by published date desc, paginated 15/page.
     */
    public function announcements(\Illuminate\Http\Request $request): View
    {
        $member = Auth::guard('member')->user();

        // 1. Published modules (use created_at as published date)
        $modules = Module::published()->get()->map(function ($m) {
            return [
                'type' => 'module',
                'title' => $m->title,
                'subtitle' => $m->description ? \Illuminate\Support\Str::limit($m->description, 100) : null,
                'tier' => $m->tier,
                'date' => $m->created_at,
                'url' => route('academy.module.show', $m),
                'cta_label' => 'Buka',
            ];
        });

        // 2. Published materials in published modules (use created_at)
        $materials = \App\Models\Material::where('is_published', true)
            ->whereHas('module', fn($q) => $q->where('is_published', true))
            ->with('module:id,title,slug,tier,is_published')
            ->get()
            ->map(function ($mat) {
                return [
                    'type' => 'material',
                    'title' => $mat->title,
                    'subtitle' => 'Di module ' . $mat->module->title,
                    'tier' => $mat->module->tier,
                    'date' => $mat->created_at,
                    'url' => route('academy.material.show', [$mat->module, $mat]),
                    'cta_label' => 'Tonton',
                ];
            });

        // 3. Published blog posts (use published_at, only past or now)
        $posts = BlogPost::where('status', BlogPost::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get()
            ->map(function ($p) {
                return [
                    'type' => 'article',
                    'title' => $p->title,
                    'subtitle' => null,
                    'tier' => null,
                    'date' => $p->published_at,
                    'url' => route('public.blog.show', ['public_id' => $p->public_id, 'slug' => $p->slug]),
                    'cta_label' => 'Baca',
                ];
            });

        // Merge + sort by date desc
        $all = $modules->concat($materials)->concat($posts)
            ->sortByDesc('date')
            ->values();

        // Manual paginate (15 per page)
        $perPage = 15;
        $currentPage = max(1, (int) $request->input('page', 1));
        $offset = ($currentPage - 1) * $perPage;
        $items = $all->slice($offset, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $items,
            $all->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('academy.announcements', compact('member', 'paginator'));
    }

    /**
     * Upgrade page — info + CTA WA for Paid tier.
     * Accessible to all logged-in members (free + paid).
     */
    public function upgrade(): View
    {
        $member = Auth::guard('member')->user();
        return view('academy.upgrade', compact('member'));
    }

}
