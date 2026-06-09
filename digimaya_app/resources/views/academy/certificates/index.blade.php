<x-academy-layout>
    <div class="max-w-5xl mx-auto px-4 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">My Certificates</h1>
                <p class="text-sm text-gray-600 mt-1">View, download, and request your completion certificate.</p>
            </div>

            @if($hasActive)
                <span class="text-xs text-gray-500 italic">You already have an active certificate</span>
            @elseif($hasPending)
                <span class="text-xs text-gray-500 italic">Your request is being reviewed</span>
            @else
                <form method="POST" action="{{ route('academy.certificates.request.store') }}"
                      x-data="{ submitting: false }"
                      @submit="if (!confirm('Submit a certificate request? Admin will review and issue your certificate.')) { $event.preventDefault(); return; } submitting = true">
                    @csrf
                    <button type="submit"
                            :disabled="submitting"
                            class="border border-brand text-brand hover:bg-brand hover:text-white text-sm rounded-md px-4 py-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!submitting">Request Certificate</span>
                        <span x-show="submitting" x-cloak>Submitting...</span>
                    </button>
                </form>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-md px-4 py-3 mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-md px-4 py-3 mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <section class="mb-10">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Issued Certificates</h2>

            @if($certificates->isEmpty())
                <div class="bg-white border border-gray-200 rounded-lg px-6 py-10 text-center">
                    <p class="text-sm text-gray-600">You don't have any certificates yet.</p>
                    <p class="text-sm text-gray-500 mt-1">Submit a request and admin will issue your certificate.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($certificates as $cert)
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-semibold text-gray-900">{{ $cert->program_name }}</h3>
                                    @if($cert->isRevoked())
                                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded">Revoked</span>
                                    @else
                                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">Active</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600">Recipient: {{ $cert->recipient_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    No. {{ $cert->certificate_number }}
                                    &middot; Completed {{ $cert->completion_date->format('d M Y') }}
                                    &middot; Issued {{ $cert->issued_date->format('d M Y') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($cert->isActive())
                                    <a href="{{ route('academy.certificates.download', $cert) }}"
                                       class="border border-brand text-brand hover:bg-brand hover:text-white text-sm rounded-md px-4 py-2 transition">
                                        Download PDF
                                    </a>
                                @else
                                    <span class="text-xs text-gray-500">Download disabled</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Pending & Rejected Requests</h2>

            @if($requests->isEmpty())
                <div class="bg-white border border-gray-200 rounded-lg px-6 py-6 text-center">
                    <p class="text-sm text-gray-600">No pending or rejected requests.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($requests as $req)
                        <div class="bg-white border border-gray-200 rounded-lg p-5">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-semibold text-gray-900">Certificate Request</h3>
                                @if($req->isPending())
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Pending</span>
                                @elseif($req->isRejected())
                                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded">Rejected</span>
                                @endif
                            </div>
                            @if($req->isRejected() && $req->rejection_reason)
                                <div class="mt-3 bg-red-50 border border-red-200 rounded p-3">
                                    <p class="text-xs font-medium text-red-800 mb-1">Rejection reason:</p>
                                    <p class="text-sm text-red-700">{{ $req->rejection_reason }}</p>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500 mt-3">
                                Submitted {{ $req->created_at->format('d M Y, H:i') }}
                                @if($req->reviewed_at)
                                    &middot; Reviewed {{ $req->reviewed_at->format('d M Y, H:i') }}
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>
</x-academy-layout>
