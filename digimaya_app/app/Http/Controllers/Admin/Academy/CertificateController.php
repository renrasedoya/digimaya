<?php

namespace App\Http\Controllers\Admin\Academy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academy\StoreCertificateRequest;
use App\Http\Requests\Admin\Academy\UpdateCertificateRequest;
use App\Models\Certificate;
use App\Models\CertificateRequest;
use App\Models\Member;
use App\Services\CertificateNumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $type = $request->input('type', 'all');
        $search = trim($request->input('search', ''));

        $query = Certificate::with(['member', 'issuer'])
            ->orderBy('issued_date', 'desc')
            ->orderBy('id', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('program_name', 'like', "%{$search}%");
            });
        }

        $certificates = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Certificate::count(),
            'active' => Certificate::where('status', 'active')->count(),
            'revoked' => Certificate::where('status', 'revoked')->count(),
            'academy' => Certificate::where('type', 'academy')->count(),
            'external' => Certificate::where('type', 'external')->count(),
        ];

        return view('admin.academy.certificates.index', compact('certificates', 'status', 'type', 'search', 'counts'));
    }

    public function create(Request $request)
    {
        $fromRequest = null;
        $prefilledMemberId = null;

        if ($request->filled('from_request')) {
            $fromRequest = CertificateRequest::with('member')->find($request->input('from_request'));
            if ($fromRequest && $fromRequest->isPending()) {
                $prefilledMemberId = $fromRequest->member_id;
            } else {
                $fromRequest = null;
            }
        }

        $academyProgramName = DB::table('settings')
            ->where('key', 'academy_program_name')
            ->value('value') ?? 'Google Ads Academy by Digimaya';

        $prefilledMember = null;
        if ($prefilledMemberId) {
            $member = Member::find($prefilledMemberId);
            if ($member) {
                $prefilledMember = [
                    'value' => (string) $member->id,
                    'text' => $member->name . ' (' . $member->email . ')',
                    'name' => $member->name,
                    'email' => $member->email,
                ];
            }
        }

        $today = now()->format('Y-m-d');

        return view('admin.academy.certificates.create', compact(
            'fromRequest', 'prefilledMemberId', 'prefilledMember', 'academyProgramName', 'today'
        ));
    }

    public function store(StoreCertificateRequest $request)
    {
        $validated = $request->validated();
        $type = $validated['type'];

        if ($type === 'academy') {
            $member = Member::find($validated['member_id']);
            $recipientName = $member->name;
            $memberId = $member->id;
        } else {
            $recipientName = $validated['custom_recipient_name'];
            $memberId = null;
        }

        $certificate = DB::transaction(function () use ($validated, $type, $memberId, $recipientName, $request) {
            $cert = Certificate::create([
                'certificate_number' => CertificateNumberGenerator::next(),
                'type' => $type,
                'member_id' => $memberId,
                'recipient_name' => $recipientName,
                'program_name' => $validated['program_name'],
                'program_description' => $validated['program_description'] ?? null,
                'completion_date' => $validated['completion_date'],
                'issued_date' => $validated['issued_date'],
                'issued_by' => auth()->id(),
                'status' => 'active',
            ]);

            if ($request->filled('from_request')) {
                $req = CertificateRequest::find($request->input('from_request'));
                if ($req && $req->isPending() && $type === 'academy' && $req->member_id == $memberId) {
                    $req->update([
                        'status' => 'approved',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                        'certificate_id' => $cert->id,
                    ]);
                }
            }

            return $cert;
        });

        return redirect()
            ->route('admin.academy.certificates.show', $certificate)
            ->with('success', "Certificate {$certificate->certificate_number} issued successfully.");
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['member', 'issuer', 'revoker']);
        return view('admin.academy.certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.academy.certificates.edit', compact('certificate'));
    }

    public function update(UpdateCertificateRequest $request, Certificate $certificate)
    {
        $certificate->update([
            'program_description' => $request->input('program_description'),
        ]);

        return redirect()
            ->route('admin.academy.certificates.show', $certificate)
            ->with('success', 'Program description updated.');
    }

    public function revoke(Request $request, Certificate $certificate)
    {
        abort_if($certificate->isRevoked(), 422, 'Certificate is already revoked.');

        $validated = $request->validate([
            'revoked_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $certificate->update([
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_by' => auth()->id(),
            'revoked_reason' => $validated['revoked_reason'] ?? null,
        ]);

        return redirect()
            ->route('admin.academy.certificates.show', $certificate)
            ->with('success', 'Certificate revoked.');
    }

    public function downloadPdf(Certificate $certificate): Response
    {
        abort_if($certificate->isRevoked(), 403, 'Cannot download a revoked certificate.');

        return $this->buildPdf($certificate)->download($this->pdfFilename($certificate));
    }

    public function previewPdf(Certificate $certificate): Response
    {
        abort_if($certificate->isRevoked(), 403, 'Cannot preview a revoked certificate.');

        return $this->buildPdf($certificate)->stream($this->pdfFilename($certificate));
    }

    private function buildPdf(Certificate $certificate)
    {
        $verifyUrl = url('/certificate/verify/' . $certificate->certificate_number);

        return Pdf::loadView('admin.academy.certificates.pdf', [
            'certificate' => $certificate,
            'verifyUrl' => $verifyUrl,
        ])->setPaper('a4', 'landscape');
    }

    private function pdfFilename(Certificate $certificate): string
    {
        $safe = preg_replace('/[^A-Za-z0-9_.-]/', '-', $certificate->certificate_number);
        return 'Certificate-' . $safe . '.pdf';
    }
}
