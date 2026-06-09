<?php

namespace App\Http\Controllers\Admin\Academy;

use App\Http\Controllers\Controller;
use App\Models\CertificateRequest;
use Illuminate\Http\Request;

class CertificateRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        $search = trim($request->input('search', ''));

        $query = CertificateRequest::with(['member', 'reviewer'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => CertificateRequest::count(),
            'pending' => CertificateRequest::where('status', 'pending')->count(),
            'approved' => CertificateRequest::where('status', 'approved')->count(),
            'rejected' => CertificateRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.academy.certificate-requests.index', compact('requests', 'status', 'search', 'counts'));
    }

    public function approve(CertificateRequest $certificateRequest)
    {
        abort_if(!$certificateRequest->isPending(), 422, 'Only pending requests can be approved.');

        // Redirect to certificate create form pre-filled with request data
        return redirect()->route('admin.academy.certificates.create', [
            'from_request' => $certificateRequest->id,
        ]);
    }

    public function reject(Request $request, CertificateRequest $certificateRequest)
    {
        abort_if(!$certificateRequest->isPending(), 422, 'Only pending requests can be rejected.');

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $certificateRequest->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()
            ->route('admin.academy.certificate-requests.index')
            ->with('success', 'Request rejected.');
    }
}
