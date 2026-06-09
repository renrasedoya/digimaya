<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Certificate {{ $certificate->certificate_number }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Academy'],
                        ['label' => 'Certificates', 'url' => route('admin.academy.certificates.index')],
                        ['label' => $certificate->certificate_number]
                    ]" />
                </div>
            </div>
            <div class="flex gap-2">
                @if($certificate->isActive())
                    <a href="{{ route('admin.academy.certificates.preview-pdf', $certificate) }}" target="_blank"
                       class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-xs text-gray-700 hover:bg-gray-200">
                        Preview PDF
                    </a>
                    <a href="{{ route('admin.academy.certificates.download-pdf', $certificate) }}"
                       class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md text-xs text-white hover:bg-indigo-700">
                        Download PDF
                    </a>
                @endif
                <a href="{{ route('admin.academy.certificates.edit', $certificate) }}"
                   class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-xs text-gray-700 hover:bg-gray-200">
                    Edit Description
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-8">

                {{-- Status banner --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500">Status</span>
                        @if($certificate->isActive())
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">Revoked</span>
                        @endif

                        @if($certificate->isAcademy())
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">Academy</span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">External</span>
                        @endif
                    </div>

                    @if($certificate->isActive())
                        <div x-data="{ openRevoke: false }" class="inline-block">
                            <button type="button" @click="openRevoke = true" class="text-sm text-red-600 hover:text-red-900">
                                Revoke
                            </button>

                            {{-- Revoke Modal --}}
                            <div x-show="openRevoke"
                                 x-cloak
                                 @keydown.escape.window="openRevoke = false"
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                 @click.self="openRevoke = false">
                                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Revoke Certificate</h3>
                                    <p class="text-sm text-gray-600 mb-4">
                                        This will mark certificate <strong>{{ $certificate->certificate_number }}</strong> as revoked.
                                        The PDF will no longer be downloadable, and the public verify page will show "Revoked" status.
                                        This action cannot be undone.
                                    </p>
                                    <form method="POST" action="{{ route('admin.academy.certificates.revoke', $certificate) }}">
                                        @csrf
                                        <div class="mb-4 text-left">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Reason <span class="text-gray-400 font-normal">(optional, internal note only)</span>
                                            </label>
                                            <textarea name="revoked_reason"
                                                      rows="3"
                                                      maxlength="1000"
                                                      placeholder="Internal reason for audit trail. Not shown to public."
                                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button"
                                                    @click="openRevoke = false"
                                                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                    class="px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Confirm Revoke
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Certificate details --}}
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 text-sm mt-6">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Certificate Number</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $certificate->certificate_number }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Recipient Name</dt>
                        <dd class="mt-1 text-gray-900">{{ $certificate->recipient_name }}</dd>
                    </div>

                    <div class="md:col-span-2">
                        <dt class="text-xs font-medium text-gray-500 uppercase">Program Name</dt>
                        <dd class="mt-1 text-gray-900">{{ $certificate->program_name }}</dd>
                    </div>

                    @if($certificate->program_description)
                        <div class="md:col-span-2">
                            <dt class="text-xs font-medium text-gray-500 uppercase">Program Description</dt>
                            <dd class="mt-1 text-gray-700">{{ $certificate->program_description }}</dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Completion Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $certificate->completion_date->format('d M Y') }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Issued Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $certificate->issued_date->format('d M Y') }}</dd>
                    </div>

                    @if($certificate->member)
                        <div class="md:col-span-2">
                            <dt class="text-xs font-medium text-gray-500 uppercase">Linked Member</dt>
                            <dd class="mt-1 text-gray-700">
                                {{ $certificate->member->name }}
                                <span class="text-gray-500">({{ $certificate->member->email }})</span>
                            </dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Issued By</dt>
                        <dd class="mt-1 text-gray-700">{{ $certificate->issuer->name ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Created</dt>
                        <dd class="mt-1 text-gray-700">{{ $certificate->created_at->format('d M Y, H:i') }}</dd>
                    </div>

                    @if($certificate->isRevoked())
                        <div class="md:col-span-2 mt-4 p-4 bg-red-50 border border-red-200 rounded">
                            <dt class="text-xs font-medium text-red-700 uppercase mb-2">Revocation Info</dt>
                            <dd class="text-sm text-red-900">
                                <div>Revoked at: {{ $certificate->revoked_at?->format('d M Y, H:i') ?? '-' }}</div>
                                <div>Revoked by: {{ $certificate->revoker->name ?? '-' }}</div>
                                @if($certificate->revoked_reason)
                                    <div class="mt-2">Reason: {{ $certificate->revoked_reason }}</div>
                                @endif
                            </dd>
                        </div>
                    @endif
                </dl>

                {{-- Public Verification --}}
                <div class="pt-6">
                    <dt class="text-xs font-medium text-gray-500 uppercase mb-2">Public Verification URL</dt>
                    <dd class="text-sm">
                        <a href="{{ url('/certificate/verify/' . $certificate->certificate_number) }}"
                           target="_blank"
                           class="text-indigo-600 hover:text-indigo-900 break-all">
                            {{ url('/certificate/verify/' . $certificate->certificate_number) }}
                        </a>
                    </dd>
                    <p class="text-xs text-gray-500 mt-1">Public can verify this certificate's authenticity at the URL above.</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
