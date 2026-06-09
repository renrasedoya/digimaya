<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Projects') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Projects']]" />
                </div>
            </div>
            @if(in_array(auth()->user()->role, [\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_ACCOUNT_MANAGER]))
                <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    + Add Project
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="GET" action="{{ route('admin.projects.index') }}" class="mb-6 flex flex-wrap gap-2">
                        <select name="status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="" {{ !request('status') ? 'selected' : '' }}>All Statuses ({{ $statusCounts['total'] }})</option>
                            @foreach(\App\Models\Project::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }} ({{ $statusCounts[$key] ?? 0 }})</option>
                            @endforeach
                        </select>

                        @if(in_array(auth()->user()->role, [\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ADMIN]))
                            <select name="account_manager_id" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                <option value="">All Account Managers</option>
                                @foreach($accountManagers as $am)
                                    <option value="{{ $am->id }}" {{ request('account_manager_id') == $am->id ? 'selected' : '' }}>{{ $am->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if($availableAdvertisers->isNotEmpty())
                            <select name="advertiser_id" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                <option value="">All Advertisers</option>
                                @foreach($availableAdvertisers as $advertiser)
                                    <option value="{{ $advertiser->id }}" {{ request('advertiser_id') == $advertiser->id ? 'selected' : '' }}>{{ $advertiser->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by project or client name..."
                               class="flex-1 min-w-[200px] border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    @if(request('filter') === 'no_report')
                    <div class="mb-4 flex items-center justify-between bg-indigo-50 border border-indigo-200 rounded px-4 py-2">
                        <span class="text-sm text-indigo-700">Showing projects without any report yet</span>
                        <a href="{{ route('admin.projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Clear</a>
                    </div>
                    @endif

                    @if($projects->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            No projects found.
                            @if(in_array(auth()->user()->role, [\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_ACCOUNT_MANAGER]))
                                <a href="{{ route('admin.projects.create') }}" class="text-indigo-600 hover:text-indigo-900">Create one</a>.
                            @endif
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Account Manager</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Advertiser</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Started</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $statusColors = [
                                            'active'    => 'bg-green-100 text-green-800',
                                            'paused'    => 'bg-yellow-100 text-yellow-800',
                                            'completed' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    @foreach($projects as $project)
                                        <tr>
                                            <td class="px-3 py-2 font-medium">
                                                <a href="{{ route('admin.projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-900">{{ $project->name }}</a>
                                                @if($project->account_url)
                                                    <div class="text-xs text-gray-500">
                                                        <a href="{{ $project->account_url }}" target="_blank" rel="noopener" class="hover:text-indigo-600">{{ \Illuminate\Support\Str::limit(preg_replace('#^https?://(www\.)?#', '', $project->account_url), 30) }} ↗</a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">
                                                {{ $project->client->business_name ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">
                                                @if($project->client && $project->client->accountManager)
                                                    {{ $project->client->accountManager->name }}
                                                @else
                                                    <span class="text-gray-400 italic">Unassigned</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">
                                                {{ $project->advertiser->name ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-800' }}">{{ $project->status_label }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-sm">
                                                {{ $project->started_at ? $project->started_at->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                                <a href="{{ route('admin.projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                                @if(in_array(auth()->user()->role, [\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ADMIN]) || (auth()->user()->isAccountManager() && ($project->client->account_manager_id ?? null) === auth()->id()))
                                                    <a href="{{ route('admin.projects.edit', $project) }}" class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                                @endif
                                                @if(auth()->user()->role === \App\Models\User::ROLE_SUPER_ADMIN)
                                                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="inline" onsubmit="return confirm('Delete this project? Reports linked to this project will also be lost.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-12">{{ $projects->links() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>