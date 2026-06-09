<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Certificate Requests') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Academy'],
                        ['label' => 'Certificate Requests']
                    ]" />
                </div>
            </div>
            <a href="{{ route('admin.academy.certificates.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Issue Manually
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Filter --}}
                    <form method="GET" action="{{ route('admin.academy.certificate-requests.index') }}" class="mb-6 flex flex-wrap gap-2">
                        <select name="status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses ({{ $counts['all'] }})</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending ({{ $counts['pending'] }})</option>
                            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved ({{ $counts['approved'] }})</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected ({{ $counts['rejected'] }})</option>
                        </select>

                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search by member name or email..."
                               class="flex-1 min-w-[200px] border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">

                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.academy.certificate-requests.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    @if($requests->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            No certificate requests found.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reviewed</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($requests as $req)
                                        <tr>
                                            <td class="px-3 py-2 text-sm">
                                                <div class="font-medium text-gray-900">{{ $req->member->name ?? 'Member removed' }}</div>
                                                <div class="text-xs text-gray-500">{{ $req->member->email ?? '' }}</div>
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">
                                                {{ $req->created_at->format('d M Y') }}
                                                <div class="text-xs text-gray-500">{{ $req->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-3 py-2 text-sm">
                                                @if($req->isPending())
                                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                @elseif($req->isApproved())
                                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                                                @elseif($req->isRejected())
                                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">
                                                @if($req->reviewed_at)
                                                    <div class="text-xs">{{ $req->reviewed_at->format('d M Y H:i') }}</div>
                                                    <div class="text-xs text-gray-500">by {{ $req->reviewer->name ?? '-' }}</div>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                                @if($req->isPending())
                                                    <div x-data="{ openReject: false }" class="inline-block">
                                                        <form method="POST" action="{{ route('admin.academy.certificate-requests.approve', $req) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-900 text-sm">Approve</button>
                                                        </form>
                                                        <button type="button"
                                                                @click="openReject = true"
                                                                class="ml-2 text-red-600 hover:text-red-900 text-sm">
                                                            Reject
                                                        </button>

                                                        {{-- Reject Modal --}}
                                                        <div x-show="openReject"
                                                             x-cloak
                                                             @keydown.escape.window="openReject = false"
                                                             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                                             @click.self="openReject = false">
                                                            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                                                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Request</h3>
                                                                <form method="POST" action="{{ route('admin.academy.certificate-requests.reject', $req) }}">
                                                                    @csrf
                                                                    <div class="mb-4 text-left">
                                                                        <p class="text-sm text-gray-700 mb-2">
                                                                            Rejecting request from <strong>{{ $req->member->name ?? 'Unknown' }}</strong>.
                                                                        </p>
                                                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                            Reason <span class="text-red-500">*</span>
                                                                        </label>
                                                                        <textarea name="rejection_reason"
                                                                                  rows="3"
                                                                                  required
                                                                                  maxlength="1000"
                                                                                  placeholder="Explain why this request is being rejected. The member will see this."
                                                                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"></textarea>
                                                                    </div>
                                                                    <div class="flex justify-end gap-2">
                                                                        <button type="button"
                                                                                @click="openReject = false"
                                                                                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                                                                            Cancel
                                                                        </button>
                                                                        <button type="submit"
                                                                                class="px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">
                                                                            Confirm Reject
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($req->isApproved() && $req->certificate_id)
                                                    <a href="{{ route('admin.academy.certificates.show', $req->certificate_id) }}"
                                                       class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                        View Certificate
                                                    </a>
                                                @elseif($req->isRejected())
                                                    <span x-data="{ open: false }" class="inline-block relative">
                                                        <button type="button"
                                                                @click="open = !open"
                                                                class="text-gray-600 hover:text-gray-900 text-sm">
                                                            View Reason
                                                        </button>
                                                        <div x-show="open"
                                                             x-cloak
                                                             @click.outside="open = false"
                                                             class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-md shadow-lg p-3 text-left z-10">
                                                            <p class="text-xs font-medium text-gray-700 mb-1">Rejection reason:</p>
                                                            <p class="text-xs text-gray-600">{{ $req->rejection_reason }}</p>
                                                        </div>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-12">{{ $requests->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
