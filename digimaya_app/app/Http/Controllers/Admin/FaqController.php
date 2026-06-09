<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    private const PER_PAGE = 20;

    /**
     * List all FAQs with optional status filter and search.
     */
    public function index(Request $request)
    {
        $query = Faq::query()->ordered();

        $statusFilter = $request->query('status');
        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($q) use ($like) {
                $q->where('question', 'like', $like)
                  ->orWhere('answer', 'like', $like);
            });
        }

        $faqs = $query->paginate(self::PER_PAGE)->withQueryString();

        $counts = [
            'all'      => Faq::count(),
            'active'   => Faq::where('is_active', true)->count(),
            'inactive' => Faq::where('is_active', false)->count(),
        ];

        return view('admin.faqs.index', [
            'faqs'         => $faqs,
            'statusFilter' => $statusFilter,
            'search'       => $search,
            'counts'       => $counts,
        ]);
    }

    public function create()
    {
        return view('admin.faqs.create', [
            'faq' => new Faq([
                'is_active'      => true,
                'position_order' => 0,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        Faq::create([
            'question'       => $data['question'],
            'answer'         => $data['answer'],
            'is_active'      => $data['is_active'],
            'position_order' => $data['position_order'],
            'created_by'     => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', [
            'faq' => $faq,
        ]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        if ($request->has('toggle_only')) {
            $faq->update(['is_active' => $request->boolean('is_active')]);
            return back()->with('success', 'FAQ status updated.');
        }

        $data = $this->validateRequest($request);

        $faq->update([
            'question'       => $data['question'],
            'answer'         => $data['answer'],
            'is_active'      => $data['is_active'],
            'position_order' => $data['position_order'],
        ]);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ deleted.');
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
            'question'       => 'required|string|max:255',
            'answer'         => 'required|string|max:50000',
            'is_active'      => 'nullable|boolean',
            'position_order' => 'nullable|integer|min:0|max:9999',
        ]) + [
            'is_active'      => $request->boolean('is_active'),
            'position_order' => (int) $request->input('position_order', 0),
        ];
    }
}
