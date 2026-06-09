<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Leads') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Leads']]" />
                </div>
            </div>
            <a href="{{ route('admin.leads.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Add Lead
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
                                            <a href="{{ $fu->lead ? route('admin.leads.show', $fu->lead) : '#' }}" class="block p-2 text-sm border border-red-200 bg-red-50 rounded hover:bg-red-100">
                                                <span class="font-medium text-gray-900">{{ $fu->lead->business_name ?? $fu->lead->contact_name ?? 'Lead removed' }}</span>
                                                <span class="text-red-700 ml-2">— {{ $fu->scheduled_at->diffForHumans() }}</span>
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
                                            <a href="{{ $fu->lead ? route('admin.leads.show', $fu->lead) : '#' }}" class="block p-2 text-sm border border-yellow-200 bg-yellow-50 rounded hover:bg-yellow-100">
                                                <span class="font-medium text-gray-900">{{ $fu->lead->business_name ?? $fu->lead->contact_name ?? 'Lead removed' }}</span>
                                                <span class="text-yellow-700 ml-2">— {{ $fu->scheduled_at->format('H:i') }}</span>
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
                                            <a href="{{ $fu->lead ? route('admin.leads.show', $fu->lead) : '#' }}" class="block p-2 text-sm border border-blue-200 bg-blue-50 rounded hover:bg-blue-100">
                                                <span class="font-medium text-gray-900">{{ $fu->lead->business_name ?? $fu->lead->contact_name ?? 'Lead removed' }}</span>
                                                <span class="text-blue-700 ml-2">— {{ $fu->scheduled_at->diffForHumans() }}</span>
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

                    <form method="GET" action="{{ route('admin.leads.index') }}" class="mb-6 flex flex-wrap gap-2">
                        <select name="status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="" {{ !request('status') ? 'selected' : '' }}>All Statuses ({{ $statusCounts['total'] }})</option>
                            @foreach(\App\Models\Lead::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }} ({{ $statusCounts[$key] ?? 0 }})</option>
                            @endforeach
                        </select>
                        <select name="month" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="0" {{ ($month ?? 0) == 0 ? 'selected' : '' }}>All Months</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ ($month ?? 0) == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ ($year ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by business, contact name, email, or whatsapp..."
                               class="flex-1 min-w-[200px] border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <select name="source" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Sources</option>
                            @foreach(\App\Models\Lead::SOURCES as $key => $label)
                                <option value="{{ $key }}" {{ request('source') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <select name="interest" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Interests</option>
                            @foreach(\App\Models\Lead::INTERESTED_IN_OPTIONS as $key => $label)
                                <option value="{{ $key }}" {{ request('interest') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.leads.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    @if($leads->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            No leads found.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Contact Name</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">WhatsApp</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Budget</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assigned</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $statusColors = [
                                            'new'          => 'bg-blue-100 text-blue-800',
                                            'contacted'    => 'bg-yellow-100 text-yellow-800',
                                            'screened'     => 'bg-purple-100 text-purple-800',
                                            'promoted'     => 'bg-green-100 text-green-800',
                                            'disqualified' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    @foreach($leads as $lead)
                                        <tr>
                                            <td class="px-3 py-2 font-medium">
                                                <a href="{{ route('admin.leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-900">{{ $lead->business_name ?: '-' }}</a>
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $statusColors[$lead->status] ?? 'bg-gray-100 text-gray-800' }}">{{ \App\Models\Lead::STATUSES[$lead->status] ?? ucfirst($lead->status) }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">
                                                {{ $lead->contact_name }}
                                                @if($lead->contact_email)
                                                    <div class="text-xs text-gray-500">{{ $lead->contact_email }}</div>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">{{ $lead->contact_phone ?: '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">{{ $lead->monthly_ad_budget ? \App\Models\Lead::BUDGETS[$lead->monthly_ad_budget] ?? $lead->monthly_ad_budget : '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">{{ $lead->assignedUser->name ?? '-' }}</td>
                                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                                <a href="{{ route('admin.leads.edit', $lead) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" class="inline" onsubmit="return confirm('Delete this lead?')">
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

                        <div class="mt-12">{{ $leads->links() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>