<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IssueCategory;
use App\Models\IssueSubCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IssueCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = IssueCategory::with(['subCategories' => function ($q) {
                $q->orderBy('display_order')->orderBy('name');
            }])
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.issue-categories.index', compact('categories'));
    }

    public function create(): View
    {
        $category = new IssueCategory(['display_order' => 0, 'is_active' => true]);
        $existingSubs = collect();

        return view('admin.issue-categories.create', compact('category', 'existingSubs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        DB::transaction(function () use ($validated, $request) {
            $category = IssueCategory::create([
                'name' => $validated['name'],
                'display_order' => $validated['display_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]);

            $this->syncSubCategories($category, $validated['sub_categories'] ?? []);
        });

        return redirect()
            ->route('admin.issue-categories.index')
            ->with('success', 'Issue category created successfully.');
    }

    public function edit(IssueCategory $issue_category): View
    {
        $category = $issue_category->load(['subCategories' => function ($q) {
            $q->orderBy('display_order')->orderBy('name');
        }]);
        $existingSubs = $category->subCategories;

        return view('admin.issue-categories.edit', compact('category', 'existingSubs'));
    }

    public function update(Request $request, IssueCategory $issue_category): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        DB::transaction(function () use ($issue_category, $validated, $request) {
            $issue_category->update([
                'name' => $validated['name'],
                'display_order' => $validated['display_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]);

            $this->syncSubCategories($issue_category, $validated['sub_categories'] ?? []);
        });

        return redirect()
            ->route('admin.issue-categories.index')
            ->with('success', 'Issue category updated successfully.');
    }

    public function destroy(IssueCategory $issue_category): RedirectResponse
    {
        $issue_category->delete();

        return redirect()
            ->route('admin.issue-categories.index')
            ->with('success', 'Issue category deleted successfully.');
    }

    private function validationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'sub_categories' => ['nullable', 'array'],
            'sub_categories.*.id' => ['nullable', 'integer'],
            'sub_categories.*.name' => ['required_with:sub_categories', 'string', 'max:100'],
            'sub_categories.*.display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    /**
     * Smart sync sub-categories:
     *  - Items with 'id' existing → update
     *  - Items without 'id' → create
     *  - Existing IDs missing from request → mark is_active = false (preserve FK integrity)
     */
    private function syncSubCategories(IssueCategory $category, array $subs): void
    {
        $submittedIds = [];

        foreach ($subs as $index => $sub) {
            if (empty($sub['name'])) {
                continue;
            }

            $payload = [
                'name' => $sub['name'],
                'display_order' => $sub['display_order'] ?? ($index + 1) * 10,
                'is_active' => isset($sub['is_active']) ? (bool) $sub['is_active'] : true,
            ];

            if (!empty($sub['id'])) {
                $existing = IssueSubCategory::where('id', $sub['id'])
                    ->where('issue_category_id', $category->id)
                    ->first();

                if ($existing) {
                    $existing->update($payload);
                    $submittedIds[] = $existing->id;
                    continue;
                }
            }

            // Create new
            $created = IssueSubCategory::create(array_merge($payload, [
                'issue_category_id' => $category->id,
            ]));
            $submittedIds[] = $created->id;
        }

        // Mark missing existing subs as inactive (preserve FK)
        IssueSubCategory::where('issue_category_id', $category->id)
            ->whereNotIn('id', $submittedIds)
            ->update(['is_active' => false]);
    }
}
