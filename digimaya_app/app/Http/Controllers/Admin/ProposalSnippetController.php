<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProposalSnippet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProposalSnippetController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProposalSnippet::query();

        if ($filter = $request->input('filter')) {
            match ($filter) {
                'active' => $query->where('is_active', true),
                'inactive' => $query->where('is_active', false),
                default => null,
            };
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $snippets = $query->ordered()->paginate(20)->withQueryString();

        $counts = [
            'total' => ProposalSnippet::count(),
            'active' => ProposalSnippet::where('is_active', true)->count(),
            'inactive' => ProposalSnippet::where('is_active', false)->count(),
        ];

        return view('admin.proposal-snippets.index', compact('snippets', 'counts'));
    }

    public function create(): View
    {
        return view('admin.proposal-snippets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $validated['is_active'] = $request->has('is_active');
        $validated['body'] = clean($validated['body'] ?? '', 'blog');

        ProposalSnippet::create($validated);

        return redirect()
            ->route('admin.proposal-snippets.index')
            ->with('success', 'Snippet created successfully.');
    }

    public function edit(ProposalSnippet $proposalSnippet): View
    {
        return view('admin.proposal-snippets.edit', compact('proposalSnippet'));
    }

    public function update(Request $request, ProposalSnippet $proposalSnippet): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $validated['is_active'] = $request->has('is_active');
        $validated['body'] = clean($validated['body'] ?? '', 'blog');

        $proposalSnippet->update($validated);

        return redirect()
            ->route('admin.proposal-snippets.index')
            ->with('success', 'Snippet updated successfully.');
    }

    public function destroy(ProposalSnippet $proposalSnippet): RedirectResponse
    {
        $proposalSnippet->delete();

        return redirect()
            ->route('admin.proposal-snippets.index')
            ->with('success', 'Snippet deleted successfully.');
    }

    private function validationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'body' => ['nullable', 'string', 'max:50000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
