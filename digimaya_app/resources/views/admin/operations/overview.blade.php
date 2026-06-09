<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Operations Overview') }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Operations Overview']
            ]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $healthColors = [
                    'healthy'         => 'bg-green-100 text-green-800',
                    'needs_attention' => 'bg-yellow-100 text-yellow-800',
                    'critical'        => 'bg-red-100 text-red-800',
                ];
                $statusColors = [
                    'open'        => 'bg-blue-100 text-blue-800',
                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                    'resolved'    => 'bg-green-100 text-green-800',
                ];
            @endphp

            {{-- ============ KPI Cards Row ============ --}}
            <div class="flex flex-wrap md:flex-nowrap gap-4 mb-8">
                @if(auth()->user()->isAdvertiser())
                <a href="{{ route('admin.projects.index', ['filter' => 'no_report']) }}" class="hover:shadow-md transition flex-1 min-w-0 bg-white shadow-sm rounded-lg p-4 {{ $kpis['awaitingFirstReportCount'] > 0 ? 'ring-1 ring-indigo-300' : '' }}">
                    <div class="text-xs uppercase text-gray-500 mb-1">Awaiting First Report</div>
                    <div class="text-2xl font-semibold {{ $kpis['awaitingFirstReportCount'] > 0 ? 'text-indigo-700' : 'text-gray-900' }}">{{ $kpis['awaitingFirstReportCount'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">new from AM</div>
                </a>
                @endif

                @if(auth()->user()->isAccountManager())
                <a href="{{ route('admin.operations.clients.index', ['filter' => 'no_project']) }}" class="hover:shadow-md transition flex-1 min-w-0 bg-white shadow-sm rounded-lg p-4 {{ $kpis['awaitingFirstProjectCount'] > 0 ? 'ring-1 ring-indigo-300' : '' }}">
                    <div class="text-xs uppercase text-gray-500 mb-1">Awaiting First Project</div>
                    <div class="text-2xl font-semibold {{ $kpis['awaitingFirstProjectCount'] > 0 ? 'text-indigo-700' : 'text-gray-900' }}">{{ $kpis['awaitingFirstProjectCount'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">new from admin</div>
                </a>
                @endif

                <a href="{{ route('admin.projects.index', ['status' => 'active']) }}" class="hover:shadow-md transition flex-1 min-w-0 bg-white shadow-sm rounded-lg p-4">
                    <div class="text-xs uppercase text-gray-500 mb-1">Active Projects</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $kpis['activeProjects'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">of {{ $kpis['totalProjects'] }} total</div>
                </a>

                <a href="{{ route('admin.operations.overview', ['month' => now()->month, 'year' => now()->year]) }}" class="hover:shadow-md transition flex-1 min-w-0 bg-white shadow-sm rounded-lg p-4">
                    <div class="text-xs uppercase text-gray-500 mb-1">Reports This Month</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $kpis['reportsThisMonth'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ now()->format('F Y') }}</div>
                </a>

                @if(!auth()->user()->isAdvertiser())
                <a href="{{ route('admin.operations.overview', ['review' => 'pending_review']) }}" class="hover:shadow-md transition flex-1 min-w-0 bg-white shadow-sm rounded-lg p-4 {{ $kpis['unreviewedCount'] > 0 ? 'ring-1 ring-yellow-300' : '' }}">
                    <div class="text-xs uppercase text-gray-500 mb-1">Pending Review</div>
                    <div class="text-2xl font-semibold {{ $kpis['unreviewedCount'] > 0 ? 'text-yellow-700' : 'text-gray-900' }}">{{ $kpis['unreviewedCount'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">AM action needed</div>
                </a>
                @endif

                <a href="{{ route('admin.operations.overview', ['review' => 'pending_ack']) }}" class="hover:shadow-md transition flex-1 min-w-0 bg-white shadow-sm rounded-lg p-4 {{ $kpis['pendingAckCount'] > 0 ? 'ring-1 ring-blue-300' : '' }}">
                    <div class="text-xs uppercase text-gray-500 mb-1">Pending Ack</div>
                    <div class="text-2xl font-semibold {{ $kpis['pendingAckCount'] > 0 ? 'text-blue-700' : 'text-gray-900' }}">{{ $kpis['pendingAckCount'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">waiting advertiser</div>
                </a>

                <a href="{{ route('admin.operations.overview', ['critical_active' => 1]) }}" class="hover:shadow-md transition flex-1 min-w-0 bg-white shadow-sm rounded-lg p-4 {{ $kpis['criticalActive'] > 0 ? 'ring-1 ring-red-300' : '' }}">
                    <div class="text-xs uppercase text-gray-500 mb-1">Critical Active</div>
                    <div class="text-2xl font-semibold {{ $kpis['criticalActive'] > 0 ? 'text-red-700' : 'text-gray-900' }}">{{ $kpis['criticalActive'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">urgent</div>
                </a>
            </div>

            <div>

                {{-- ============ Recent Reports ============ --}}
                <div>
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="font-semibold text-gray-700 mb-4">Recent Reports</h3>

                            {{-- Review Lifecycle Tabs --}}
                            @php
                                $currentReview = request('review') ?: 'all';
                                $reviewTabs = [
                                    'all'             => 'All Reviews (' . $reviewCounts['all'] . ')',
                                    'pending_review'  => 'Pending Review (' . $reviewCounts['pending_review'] . ')',
                                    'pending_ack'     => 'Pending Ack (' . $reviewCounts['pending_ack'] . ')',
                                    'completed'       => 'Acknowledged (' . $reviewCounts['completed'] . ')',
                                ];
                            @endphp
                            <nav class="flex space-x-6 -mb-px overflow-x-auto mb-4">
                                @foreach($reviewTabs as $key => $label)
                                    <a href="{{ route('admin.operations.overview', $key === 'all' ? [] : ['review' => $key]) }}"
                                       class="py-2 px-1 border-b-2 text-sm font-medium whitespace-nowrap {{ $currentReview === $key ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </nav>

                            {{-- Filter Bar --}}
                            <form method="GET" action="{{ route('admin.operations.overview') }}" class="mb-6 flex flex-wrap gap-2">
                                @if(request('review'))
                                    <input type="hidden" name="review" value="{{ request('review') }}">
                                @endif
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
                                <select name="health" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                    <option value="">All Health</option>
                                    @foreach(\App\Models\ProjectReport::HEALTHS as $key => $label)
                                        <option value="{{ $key }}" {{ request('health') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <select name="report_status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                    <option value="">All Statuses</option>
                                    @foreach(\App\Models\ProjectReport::STATUSES as $key => $label)
                                        <option value="{{ $key }}" {{ request('report_status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if(!auth()->user()->isAdvertiser())
                                <select name="advertiser_id" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                    <option value="">All Advertisers</option>
                                    @foreach($advertisers as $adv)
                                        <option value="{{ $adv->id }}" {{ (string) request('advertiser_id') === (string) $adv->id ? 'selected' : '' }}>{{ $adv->name }}</option>
                                    @endforeach
                                </select>
                                @endif

                                @if($accountManagers->count() > 0)
                                <select name="account_manager_id" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                    <option value="">All AMs</option>
                                    @foreach($accountManagers as $am)
                                        <option value="{{ $am->id }}" {{ (string) request('account_manager_id') === (string) $am->id ? 'selected' : '' }}>{{ $am->name }}</option>
                                    @endforeach
                                </select>
                                @endif

                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Cari project atau client"
                                       class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm flex-1 min-w-[200px]">
                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                                <a href="{{ route('admin.operations.overview') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                            </form>

                            @if($recentReports->isEmpty())
                                <div class="text-center py-12 text-gray-500 text-sm">
                                    @if(auth()->user()->isAdvertiser())
                                        No reports submitted yet. Go to a project to submit your first report.
                                    @elseif(auth()->user()->isAccountManager())
                                        No reports from projects you manage yet. Wait for advertisers to submit.
                                    @else
                                        No reports submitted across the agency yet.
                                    @endif
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Health</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                @if(!auth()->user()->isAdvertiser())
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Advertiser</th>
                                                @endif
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Review</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($recentReports as $report)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-3 py-2 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                        <a href="{{ route('admin.projects.show', ['project' => $report->project_id, 'report_id' => $report->id]) }}" class="text-indigo-600 hover:text-indigo-900">{{ $report->period_label }}</a>
                                                    </td>
                                                    <td class="px-3 py-2 text-sm text-gray-700">
                                                        <div class="font-medium">{{ $report->project->name ?? '-' }}</div>
                                                        @if($report->project && $report->project->client)
                                                            <div class="text-xs text-gray-500">{{ $report->project->client->business_name }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full {{ $healthColors[$report->health] ?? 'bg-gray-100 text-gray-800' }}">{{ $report->health_label }}</span>
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800' }}">{{ $report->status_label }}</span>
                                                    </td>
                                                    @if(!auth()->user()->isAdvertiser())
                                                    <td class="px-3 py-2 text-sm text-gray-600">{{ $report->submitter->name ?? '-' }}</td>
                                                    @endif
                                                    <td class="px-3 py-2 text-sm">
                                                        @if($report->isAcknowledged())
                                                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800">Acknowledged</span>
                                                            <div class="text-xs text-gray-500 mt-1">by {{ $report->submitter->name ?? '-' }}</div>
                                                        @elseif($report->reviewer)
                                                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">Pending Ack</span>
                                                            <div class="text-xs text-gray-500 mt-1">reviewed by {{ $report->reviewer->name }}</div>
                                                        @else
                                                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-6">{{ $recentReports->links() }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- ============ Stale Projects Widget (reviewers + advertiser-own) ============ --}}
                @if($canSeeStale)
                    <div class="bg-white shadow-sm rounded-lg mt-6">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-700">Stale Projects <span class="text-xs font-normal text-gray-500">(no report in 7 days)</span></h3>
                                @if($staleProjects->count() > 0)
                                    <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $staleProjects->count() }}</span>
                                @endif
                            </div>

                            @if($staleProjects->isEmpty())
                                <div class="text-center py-8 text-sm text-gray-500">
                                    🎉 All active projects have reported within the last 7 days.
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                                @if(!auth()->user()->isAdvertiser())
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Advertiser</th>
                                                @endif
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Last Report</th>
                                                <th class="px-3 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($staleProjects as $project)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-3 py-2 text-sm font-medium text-gray-900">{{ $project->name }}</td>
                                                    <td class="px-3 py-2 text-sm text-gray-700">{{ $project->client->business_name ?? '-' }}</td>
                                                    @if(!auth()->user()->isAdvertiser())
                                                    <td class="px-3 py-2 text-sm text-gray-600">{{ $project->advertiser->name ?? '-' }}</td>
                                                    @endif
                                                    <td class="px-3 py-2 text-sm">
                                                        @if($project->last_report_at)
                                                            <span class="text-yellow-700">{{ \Carbon\Carbon::parse($project->last_report_at)->diffForHumans() }}</span>
                                                        @else
                                                            <span class="text-red-700 font-medium">Never</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-2 text-right">
                                                        <a href="{{ route('admin.projects.show', $project) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Open Project →</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif



            </div>
        </div>
    </div>
</x-app-layout>