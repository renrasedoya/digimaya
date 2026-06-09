<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Clients') }}
            </h2>
            <div class="mt-2">
                <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Clients']]" />
            </div>
        </div>
            <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Add Client
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

            {{-- Followup Card --}}
            @if($myFollowupsCount['overdue'] + $myFollowupsCount['today'] + $myFollowupsCount['upcoming'] > 0)
                <div x-data="{ expanded: false }" class="bg-white shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-4 flex-wrap text-sm">
                                <span class="font-semibold text-gray-700">{{ $myFollowupsTitle }}</span>
                                @if($myFollowupsCount['overdue'] > 0)
                                    <span class="text-red-700">{{ $myFollowupsCount['overdue'] }} overdue</span>
                                @endif
                                @if($myFollowupsCount['today'] > 0)
                                    <span class="text-yellow-700">{{ $myFollowupsCount['today'] }} today</span>
                                @endif
                                @if($myFollowupsCount['upcoming'] > 0)
                                    <span class="text-blue-700">{{ $myFollowupsCount['upcoming'] }} upcoming (3 days)</span>
                                @endif
                            </div>
                            <button type="button" @click="expanded = !expanded" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                                <span x-show="!expanded">Show me ▼</span>
                                <span x-show="expanded" x-cloak>Hide ▲</span>
                            </button>
                        </div>

                        <div x-show="expanded" x-cloak class="mt-4 space-y-4">
                            @if($myFollowupsCount['overdue'] > 0)
                                <div>
                                    <div class="text-xs font-semibold text-red-700 uppercase mb-2">Overdue ({{ $myFollowupsCount['overdue'] }})</div>
                                    <div class="space-y-1">
                                        @foreach($myFollowups['overdue'] as $fu)
                                            <a href="{{ $fu->client ? route('admin.clients.show', $fu->client) : '#' }}" class="block p-2 text-sm border border-red-200 bg-red-50 rounded hover:bg-red-100">
                                                <span class="font-medium text-gray-900">{{ $fu->client->business_name ?? 'Client removed' }}</span>
                                                <span class="text-red-700 ml-2">{{ $fu->scheduled_at->diffForHumans() }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($myFollowupsCount['today'] > 0)
                                <div>
                                    <div class="text-xs font-semibold text-yellow-700 uppercase mb-2">Today ({{ $myFollowupsCount['today'] }})</div>
                                    <div class="space-y-1">
                                        @foreach($myFollowups['today'] as $fu)
                                            <a href="{{ $fu->client ? route('admin.clients.show', $fu->client) : '#' }}" class="block p-2 text-sm border border-yellow-200 bg-yellow-50 rounded hover:bg-yellow-100">
                                                <span class="font-medium text-gray-900">{{ $fu->client->business_name ?? 'Client removed' }}</span>
                                                <span class="text-yellow-700 ml-2">{{ $fu->scheduled_at->format('H:i') }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($myFollowupsCount['upcoming'] > 0)
                                <div>
                                    <div class="text-xs font-semibold text-blue-700 uppercase mb-2">Upcoming ({{ $myFollowupsCount['upcoming'] }})</div>
                                    <div class="space-y-1">
                                        @foreach($myFollowups['upcoming'] as $fu)
                                            <a href="{{ $fu->client ? route('admin.clients.show', $fu->client) : '#' }}" class="block p-2 text-sm border border-blue-200 bg-blue-50 rounded hover:bg-blue-100">
                                                <span class="font-medium text-gray-900">{{ $fu->client->business_name ?? 'Client removed' }}</span>
                                                <span class="text-blue-700 ml-2">{{ $fu->scheduled_at->diffForHumans() }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="GET" action="{{ route('admin.clients.index') }}" class="mb-6 flex flex-wrap gap-2">
                        <select name="status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="" {{ !request('status') ? 'selected' : '' }}>All Statuses ({{ $statusCounts['total'] }})</option>
                            <option value="prospect" {{ request('status') === 'prospect' ? 'selected' : '' }}>Prospect ({{ $statusCounts['prospect'] }})</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active ({{ $statusCounts['active'] }})</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive ({{ $statusCounts['inactive'] }})</option>
                            <option value="churned" {{ request('status') === 'churned' ? 'selected' : '' }}>Churned ({{ $statusCounts['churned'] }})</option>
                            <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost ({{ $statusCounts['lost'] }})</option>
                        </select>
                        <select name="account_manager_id" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Account Managers</option>
                            @foreach($accountManagers as $am)
                                <option value="{{ $am->id }}" {{ request('account_manager_id') == $am->id ? 'selected' : '' }}>{{ $am->name }}</option>
                            @endforeach
                        </select>
                        <select name="interest" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Interests</option>
                            @foreach(\App\Models\Client::INTERESTED_IN_OPTIONS as $key => $label)
                                <option value="{{ $key }}" {{ request('interest') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by business, industry, or email..."
                               class="flex-1 min-w-[200px] border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.clients.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    @if($clients->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            No clients found.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Account Manager</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Contact Name</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">WhatsApp</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Industry</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Website</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $statusColors = [
                                            'prospect' => 'bg-blue-100 text-blue-800',
                                            'active' => 'bg-green-100 text-green-800',
                                            'inactive' => 'bg-gray-100 text-gray-800',
                                            'churned' => 'bg-red-100 text-red-800',
                                            'lost' => 'bg-orange-100 text-orange-800',
                                        ];
                                    @endphp
                                    @foreach($clients as $client)
                                        <tr>
                                            <td class="px-3 py-2 font-medium">
                                                <a href="{{ route('admin.clients.show', $client) }}" class="text-indigo-600 hover:text-indigo-900">{{ $client->business_name }}</a>
                                                @if($client->contact_email)
                                                    <div class="text-xs text-gray-500">{{ $client->contact_email }}</div>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $statusColors[$client->status] ?? 'bg-gray-100 text-gray-800' }}">{{ ucfirst($client->status) }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">
                                                @if($client->accountManager)
                                                    {{ $client->accountManager->name }}
                                                @else
                                                    <span class="text-gray-400 italic">Unassigned</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">{{ $client->contact_name ?: '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">{{ $client->contact_phone ?: '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">{{ $client->industry ?: '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">
                                                @if($client->website_url)
                                                    <a href="{{ $client->website_url }}" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-900">
                                                        {{ \Illuminate\Support\Str::limit(preg_replace('#^https?://(www\\.)?#', '', $client->website_url), 25) }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                                <a href="{{ route('admin.clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" class="inline" onsubmit="return confirm('Delete this client?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                                </form>
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