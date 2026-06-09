<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CaseStudyController extends Controller
{
    private const PER_PAGE = 20;

    public function index(Request $request)
    {
        $query = CaseStudy::query()->ordered()->withCount('results');

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
                $q->where('client_name', 'like', $like)
                  ->orWhere('industry', 'like', $like)
                  ->orWhere('problem', 'like', $like)
                  ->orWhere('solution', 'like', $like);
            });
        }

        $caseStudies = $query->paginate(self::PER_PAGE)->withQueryString();

        $counts = [
            'all'      => CaseStudy::count(),
            'active'   => CaseStudy::where('is_active', true)->count(),
            'inactive' => CaseStudy::where('is_active', false)->count(),
        ];

        return view('admin.case-studies.index', [
            'caseStudies'  => $caseStudies,
            'statusFilter' => $statusFilter,
            'search'       => $search,
            'counts'       => $counts,
        ]);
    }

    public function create()
    {
        return view('admin.case-studies.create', [
            'caseStudy' => new CaseStudy([
                'is_active'      => true,
                'position_order' => 0,
            ]),
            'results'   => collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        $thumbnail = $this->handleThumbnail(
            $request->file('thumbnail_file'),
            $request->input('thumbnail_url'),
            null
        );

        [$clientId, $clientName] = $this->resolveClientPicker(
            $request->input('client_picker')
        );

        DB::transaction(function () use ($request, $data, $thumbnail, $clientId, $clientName) {
            $cs = CaseStudy::create([
                'client_name'    => $clientName,
                'title'          => $data['title'],
                'client_id'      => $clientId,
                'industry'       => $data['industry'],
                'thumbnail'      => $thumbnail,
                'problem'        => $data['problem'],
                'solution'       => $data['solution'],
                'is_active'      => $data['is_active'],
                'position_order' => $data['position_order'],
                'created_by'     => $request->user()->id,
            ]);

            $this->syncResults($cs, $request->input('results', []));
        });

        return redirect()
            ->route('admin.case-studies.index')
            ->with('success', 'Case study created successfully.');
    }

    public function edit(CaseStudy $caseStudy)
    {
        $caseStudy->load('results');

        return view('admin.case-studies.edit', [
            'caseStudy' => $caseStudy,
            'results'   => $caseStudy->results,
        ]);
    }

    public function update(Request $request, CaseStudy $caseStudy): RedirectResponse
    {
        if ($request->has('toggle_only')) {
            $caseStudy->update(['is_active' => $request->boolean('is_active')]);
            return back()->with('success', 'Case study status updated.');
        }

        $data = $this->validateRequest($request);

        $thumbnail = $this->handleThumbnail(
            $request->file('thumbnail_file'),
            $request->input('thumbnail_url'),
            $caseStudy->thumbnail
        );

        [$clientId, $clientName] = $this->resolveClientPicker(
            $request->input('client_picker')
        );

        DB::transaction(function () use ($request, $data, $thumbnail, $clientId, $clientName, $caseStudy) {
            $caseStudy->update([
                'client_name'    => $clientName,
                'title'          => $data['title'],
                'client_id'      => $clientId,
                'industry'       => $data['industry'],
                'thumbnail'      => $thumbnail,
                'problem'        => $data['problem'],
                'solution'       => $data['solution'],
                'is_active'      => $data['is_active'],
                'position_order' => $data['position_order'],
            ]);

            $this->syncResults($caseStudy, $request->input('results', []));
        });

        return redirect()
            ->route('admin.case-studies.index')
            ->with('success', 'Case study updated successfully.');
    }

    public function destroy(CaseStudy $caseStudy): RedirectResponse
    {
        if ($caseStudy->thumbnail && ! $caseStudy->thumbnailIsExternal()) {
            Storage::disk('public')->delete($caseStudy->thumbnail);
        }

        $caseStudy->delete();

        return redirect()
            ->route('admin.case-studies.index')
            ->with('success', 'Case study deleted.');
    }

    // ============== Private helpers ==============

    private function validateRequest(Request $request): array
    {
        \App\Services\UrlNormalizer::normalizeRequest($request, ['thumbnail_url']);

        if ($request->has('toggle_only')) {
            return $request->validate([
                'is_active' => 'required|boolean',
            ]);
        }

        return $request->validate([
            'client_picker'      => 'required|string|max:255',
            'title'              => 'required|string|max:255',
            'industry'           => 'nullable|string|max:255',
            'thumbnail_file'     => 'nullable|image|max:2048',
            'thumbnail_url'      => 'nullable|url|max:500',
            'problem'            => 'nullable|string|max:50000',
            'solution'           => 'nullable|string|max:50000',
            'results'            => 'nullable|array|max:20',
            'results.*.value'    => 'required_with:results.*.label|string|max:100',
            'results.*.label'    => 'required_with:results.*.value|string|max:100',
            'is_active'          => 'nullable|boolean',
            'position_order'     => 'nullable|integer|min:0|max:9999',
        ]) + [
            'is_active'      => $request->boolean('is_active'),
            'position_order' => (int) $request->input('position_order', 0),
        ];
    }

    private function resolveClientPicker(?string $picker): array
    {
        $value = trim((string) $picker);

        if ($value === '') {
            return [null, null];
        }

        if (ctype_digit($value)) {
            $client = Client::find((int) $value);
            if ($client) {
                return [$client->id, $client->business_name];
            }
        }

        return [null, $value];
    }

    private function syncResults(CaseStudy $caseStudy, array $submitted): void
    {
        $caseStudy->results()->delete();

        $order = 0;
        foreach ($submitted as $row) {
            $value = trim((string) ($row['value'] ?? ''));
            $label = trim((string) ($row['label'] ?? ''));

            if ($value === '' && $label === '') {
                continue;
            }

            $caseStudy->results()->create([
                'value'          => $value,
                'label'          => $label,
                'position_order' => $order++,
            ]);
        }
    }

    private function handleThumbnail(?UploadedFile $file, ?string $url, ?string $existingPath = null): ?string
    {
        if ($file) {
            $path = $file->store('case-studies', 'public');
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
