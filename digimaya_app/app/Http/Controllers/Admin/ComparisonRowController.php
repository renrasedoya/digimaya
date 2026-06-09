<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComparisonRow;
use Illuminate\Http\Request;

class ComparisonRowController extends Controller
{
    public function index()
    {
        $rows = ComparisonRow::ordered()->get();

        return view('admin.comparison-rows.index', compact('rows'));
    }

    public function create()
    {
        return view('admin.comparison-rows.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        ComparisonRow::create($data);

        return redirect()->route('admin.comparison-rows.index')
            ->with('success', 'Comparison row berhasil dibuat.');
    }

    public function edit(ComparisonRow $comparisonRow)
    {
        return view('admin.comparison-rows.edit', ['row' => $comparisonRow]);
    }

    public function update(Request $request, ComparisonRow $comparisonRow)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        $comparisonRow->update($data);

        return redirect()->route('admin.comparison-rows.index')
            ->with('success', 'Comparison row berhasil diupdate.');
    }

    public function destroy(ComparisonRow $comparisonRow)
    {
        $comparisonRow->delete();

        return redirect()->route('admin.comparison-rows.index')
            ->with('success', 'Comparison row berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'aspect' => 'required|string|max:255',
            'value_a' => 'required|string|max:500',
            'value_b' => 'required|string|max:500',
            'position' => 'nullable|integer|min:0',
        ]);
    }
}
