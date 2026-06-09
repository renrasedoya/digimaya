<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Certificates') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Academy'],
                        ['label' => 'Certificates']
                    ]" />
                </div>
            </div>
            <a href="{{ route('admin.academy.certificates.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + New Certificate
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

            @if (session('info'))
                <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    {{ session('info') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="GET" action="{{ route('admin.academy.certificates.index') }}" class="mb-6 flex flex-wrap gap-2">
                        <select name="status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses ({{ $counts['all'] }})</option>
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active ({{ $counts['active'] }})</option>
                            <option value="revoked" {{ $status === 'revoked' ? 'selected' : '' }}>Revoked ({{ $counts['revoked'] }})</option>
                        </select>

                        <select name="type" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="academy" {{ $type === 'academy' ? 'selected' : '' }}>Academy ({{ $counts['academy'] }})</option>
                            <option value="external" {{ $type === 'external' ? 'selected' : '' }}>External ({{ $counts['external'] }})</option>
                        </select>

                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search by number, recipient, or program..."
                               class="flex-1 min-w-[200px] border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">

                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.academy.certificates.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    @if($certificates->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            No certificates found.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Number</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Recipient</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Issued</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($certificates as $cert)
                                        <tr>
                                            <td class="px-3 py-2 font-medium text-sm">
                                                <a href="{{ route('admin.academy.certificates.show', $cert) }}" class="text-indigo-600 hover:text-indigo-900">{{ $cert->certificate_number }}</a>
                                            </td>
                                            <td class="px-3 py-2 text-sm">
                                                <div class="text-gray-900">{{ $cert->recipient_name }}</div>
                                                @if($cert->member)
                                                    <div class="text-xs text-gray-500">{{ $cert->member->email }}</div>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">{{ $cert->program_name }}</td>
                                            <td class="px-3 py-2 text-sm">
                                                @if($cert->isAcademy())
                                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Academy</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">External</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">{{ $cert->issued_date->format('d M Y') }}</td>
                                            <td class="px-3 py-2 text-sm">
                                                @if($cert->isActive())
                                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Revoked</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                                <a href="{{ route('admin.academy.certificates.show', $cert) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                                <a href="{{ route('admin.academy.certificates.edit', $cert) }}" class="ml-2 text-gray-600 hover:text-gray-900 text-sm">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-12">{{ $certificates->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
