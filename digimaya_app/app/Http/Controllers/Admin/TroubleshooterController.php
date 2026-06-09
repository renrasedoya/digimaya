<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TroubleshooterNode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TroubleshooterController extends Controller
{
    public function index(): View
    {
        $nodes = TroubleshooterNode::orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'parent_id', 'type', 'label', 'answers', 'videos', 'sort_order', 'is_active']);

        $nodesByParent = $nodes->groupBy(fn($n) => $n->parent_id ?? 'root');
        $rootNodes = $nodesByParent->get('root') ?? collect();

        return view('admin.troubleshooter.index', compact('nodes', 'nodesByParent', 'rootNodes'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->validationRules());

        $node = TroubleshooterNode::create([
            'parent_id' => $validated['parent_id'] ?? null,
            'type' => $validated['type'],
            'label' => $validated['label'],
            'answers' => $validated['answers'] ?? null,
            'videos' => $validated['videos'] ?? null,
            'sort_order' => $validated['sort_order'] ?? $this->nextSortOrder($validated['parent_id'] ?? null),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Node created successfully.',
            'node' => $node->fresh(),
        ]);
    }

    public function update(Request $request, TroubleshooterNode $troubleshooter): JsonResponse
    {
        $validated = $request->validate($this->validationRules());

        $newType = $validated['type'];
        $oldType = $troubleshooter->type;

        if ($newType === 'leaf' && $oldType === 'question') {
            $childCount = $troubleshooter->children()->count();
            if ($childCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak bisa ubah ke Leaf karena node ini punya {$childCount} children. Hapus children dulu.",
                ], 422);
            }
        }

        $payload = [
            'type' => $newType,
            'label' => $validated['label'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($newType === 'leaf') {
            $payload['answers'] = $validated['answers'] ?? null;
            $payload['videos'] = $validated['videos'] ?? null;
        } else {
            $payload['answers'] = null;
            $payload['videos'] = null;
        }

        $troubleshooter->update($payload);

        return response()->json([
            'success' => true,
            'message' => 'Node updated successfully.',
            'node' => $troubleshooter->fresh(),
        ]);
    }
    public function destroy(TroubleshooterNode $troubleshooter): JsonResponse
    {
        $deletedCount = 0;

        DB::transaction(function () use ($troubleshooter, &$deletedCount) {
            $deletedCount = $this->cascadeSoftDelete($troubleshooter);
        });

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} node(s) deleted.",
            'deleted_count' => $deletedCount,
        ]);
    }
    private function validationRules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'exists:troubleshooter_nodes,id'],
            'type' => ['required', 'in:question,leaf'],
            'label' => ['required', 'string', 'max:255'],
            'answers' => ['nullable', 'array'],
            'answers.*.cause' => ['nullable', 'string', 'max:2000'],
            'answers.*.solution' => ['nullable', 'string', 'max:5000'],
            'videos' => ['nullable', 'array'],
            'videos.*.youtube_id' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9_-]{11}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    private function nextSortOrder(?int $parentId): int
    {
        $max = TroubleshooterNode::where('parent_id', $parentId)->max('sort_order');
        return ($max ?? -1) + 1;
    }

    private function cascadeSoftDelete(TroubleshooterNode $node): int
    {
        $count = 0;
        foreach ($node->children as $child) {
            $count += $this->cascadeSoftDelete($child);
        }
        $node->delete();
        return $count + 1;
    }
}
