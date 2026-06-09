<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $member = Auth::guard('member')->user();

        $certificates = Certificate::where('member_id', $member->id)
            ->where('type', 'academy')
            ->orderBy('issued_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $requests = CertificateRequest::where('member_id', $member->id)
            ->whereIn('status', ['pending', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        $hasPending = CertificateRequest::where('member_id', $member->id)
            ->where('status', 'pending')
            ->exists();

        $hasActive = Certificate::where('member_id', $member->id)
            ->where('type', 'academy')
            ->where('status', 'active')
            ->exists();

        return view('academy.certificates.index', compact(
            'certificates', 'requests', 'hasPending', 'hasActive'
        ));
    }

    public function requestStore(Request $request)
    {
        $member = Auth::guard('member')->user();

        $hasPending = CertificateRequest::where('member_id', $member->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return redirect()
                ->route('academy.certificates.index')
                ->with('error', 'You already have a pending certificate request.');
        }

        $hasActive = Certificate::where('member_id', $member->id)
            ->where('type', 'academy')
            ->where('status', 'active')
            ->exists();

        if ($hasActive) {
            return redirect()
                ->route('academy.certificates.index')
                ->with('error', 'You already have an active Academy certificate.');
        }

        CertificateRequest::create([
            'member_id' => $member->id,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('academy.certificates.index')
            ->with('success', 'Your certificate request has been submitted. Admin will review it shortly.');
    }

    public function download(Certificate $certificate): Response
    {
        $member = Auth::guard('member')->user();

        abort_if($certificate->member_id !== $member->id, 403);
        abort_if($certificate->isRevoked(), 403, 'This certificate has been revoked.');

        $verifyUrl = url('/certificate/verify/' . $certificate->certificate_number);

        $safe = preg_replace('/[^A-Za-z0-9_.-]/', '-', $certificate->certificate_number);
        $filename = 'Certificate-' . $safe . '.pdf';

        return Pdf::loadView('admin.academy.certificates.pdf', [
            'certificate' => $certificate,
            'verifyUrl' => $verifyUrl,
        ])->setPaper('a4', 'landscape')->download($filename);
    }
}
