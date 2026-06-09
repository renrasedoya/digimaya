<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Clients</h2>
        <div class="mt-2">
            <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Operations', 'url' => route('admin.operations.overview')], ['label' => 'My Clients']]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Filter bar --}}
                    <form method="GET" action="{{ route('admin.operations.clients.index') }}" class="mb-6 flex flex-wrap gap-2">
                        <select name="status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Statuses ({{ $statusCounts['total'] }})</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active ({{ $statusCounts['active'] }})</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive ({{ $statusCounts['inactive'] }})</option>
                            <option value="churned" {{ request('status') === 'churned' ? 'selected' : '' }}>Churned ({{ $statusCounts['churned'] }})</option>
                        </select>

                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari business name, contact, atau email"
                               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm flex-1 min-w-[240px]">

                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.operations.clients.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Reset</a>
                    </form>

                    {{-- Table --}}
                    @if(request('filter') === 'no_project')
                    <div class="mb-4 flex items-center justify-between bg-indigo-50 border border-indigo-200 rounded px-4 py-2">
                        <span class="text-sm text-indigo-700">Showing clients without any project yet</span>
                        <a href="{{ route('admin.operations.clients.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Clear</a>
                    </div>
                    @endif

                    @if($clients->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            <p class="text-sm">Belum ada client yang di-assign ke kamu.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Industry</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Since</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($clients as $client)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $client->business_name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $client->industry ?? '—' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $client->contact_name ?? '—' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $client->contact_email ?? '—' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $client->contact_phone ?? '—' }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                @php
                                                    $badgeClass = match($client->status) {
                                                        'active'   => 'bg-green-100 text-green-800',
                                                        'inactive' => 'bg-gray-100 text-gray-800',
                                                        'churned'  => 'bg-red-100 text-red-800',
                                                        default    => 'bg-gray-100 text-gray-600',
                                                    };
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">
                                                    {{ ucfirst($client->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $client->client_since ? $client->client_since->format('d M Y') : '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-12">{{ $clients->links() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
