<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                {{ __('CRM Overview') }}
            </h2>
            <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'CRM Overview']]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ====================== Row 1: Lifecycle metrics (4 cards) ====================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Total Clients --}}
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="text-sm font-medium text-gray-500">Total Clients</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalClients) }}</div>
                    <div class="mt-1 text-xs text-gray-500">All clients in system</div>
                </div>

                {{-- Active Clients --}}
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="text-sm font-medium text-gray-500">Active Clients</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($activeClients) }}</div>
                    <div class="mt-1 text-xs text-gray-500">
                        {{ $activeClients }} of {{ $totalClients }} total
                        @if($totalClients > 0)
                            ({{ round(($activeClients / $totalClients) * 100) }}%)
                        @endif
                    </div>
                </div>

                {{-- MRR --}}
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="text-sm font-medium text-gray-500">MRR (Active)</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">
                        Rp{{ number_format($mrr, 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">Sum of monthly retainers</div>
                </div>

                {{-- Prospects (action card) --}}
                <a href="{{ route('admin.clients.index', ['status' => 'prospect']) }}"
                   class="block bg-white rounded-lg shadow p-5 transition hover:shadow-md {{ $agedProspectsNeedFu > 0 ? 'ring-1 ring-yellow-300' : '' }}">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-yellow-500"></span>
                        <div class="text-sm font-medium text-gray-500">Total Prospects</div>
                    </div>
                    <div class="mt-2 text-3xl font-bold {{ $agedProspectsNeedFu > 0 ? 'text-yellow-700' : 'text-gray-900' }}">
                        {{ number_format($totalProspects) }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        {{ $freshProspects }} in last 30d · {{ $agedProspects }} older
                        @if($agedProspectsNeedFu > 0)
                            · <span class="text-yellow-700 font-medium">{{ $agedProspectsNeedFu }} need FU</span>
                        @endif
                    </div>
                </a>

            </div>

            {{-- ====================== Row 2: This-month flow metrics (2 cards) ====================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- New Active This Month --}}
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                        <div class="text-sm font-medium text-gray-500">New Active This Month</div>
                    </div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($newActiveThisMonth) }}</div>
                    <div class="mt-1 text-xs flex items-center gap-1
                        @if($newActiveDelta > 0) text-green-600
                        @elseif($newActiveDelta < 0) text-red-600
                        @else text-gray-500
                        @endif">
                        @if($newActiveDelta > 0)
                            <span>&#9650;</span>
                            <span>+{{ $newActiveDelta }} vs last month</span>
                        @elseif($newActiveDelta < 0)
                            <span>&#9660;</span>
                            <span>{{ $newActiveDelta }} vs last month</span>
                        @else
                            <span>No change vs last month</span>
                        @endif
                    </div>
                </div>

                {{-- Lost This Month --}}
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-red-500"></span>
                        <div class="text-sm font-medium text-gray-500">Lost This Month</div>
                    </div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($lostThisMonth) }}</div>
                    <div class="mt-1 text-xs flex items-center gap-1
                        @if($lostDelta > 0) text-red-600
                        @elseif($lostDelta < 0) text-green-600
                        @else text-gray-500
                        @endif">
                        @if($lostDelta > 0)
                            <span>&#9650;</span>
                            <span>+{{ $lostDelta }} vs last month</span>
                        @elseif($lostDelta < 0)
                            <span>&#9660;</span>
                            <span>{{ $lostDelta }} vs last month</span>
                        @else
                            <span>No change vs last month</span>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ====================== Row 3: Trend chart (full width) ====================== --}}
            <div class="bg-white rounded-lg shadow p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Activation vs Lost — Last 12 Months</h3>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <span class="inline-block w-3 h-3 rounded-sm bg-green-500"></span> New Active
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="inline-block w-3 h-3 rounded-sm bg-red-500"></span> Lost
                        </span>
                    </div>
                </div>
                <div style="position: relative; height: 280px;">
                    <canvas id="crmTrendChart"></canvas>
                </div>
            </div>

            {{-- ====================== Row 4: New & Lost Clients (with month filter) ====================== --}}
            <div class="bg-white rounded-lg shadow p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">New &amp; Lost Clients</h3>
                    <form method="GET" action="{{ route('admin.crm.overview') }}" class="flex items-center gap-2">
                        <label for="month-filter" class="text-xs text-gray-500">Month:</label>
                        <select
                            name="month"
                            id="month-filter"
                            onchange="this.form.submit()"
                            class="text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            @foreach($monthOptions as $opt)
                                <option value="{{ $opt['value'] }}" @selected($opt['value'] === $selectedMonth)>
                                    {{ $opt['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- New Active --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                            <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                New Active ({{ $newClientsList->count() }})
                            </h4>
                        </div>
                        @if($newClientsList->isEmpty())
                            <p class="text-xs text-gray-400">No new active clients in this month.</p>
                        @else
                            <ul class="space-y-2">
                                @foreach($newClientsList as $entry)
                                    <li class="flex items-start gap-2 text-sm text-gray-700">
                                        <span class="text-gray-300 mt-0.5">•</span>
                                        <span class="truncate">{{ $entry->client->business_name ?? 'Unknown Client' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- Lost --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-block w-2 h-2 rounded-full bg-red-500"></span>
                            <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                Lost ({{ $lostClientsList->count() }})
                            </h4>
                        </div>
                        @if($lostClientsList->isEmpty())
                            <p class="text-xs text-gray-400">No lost clients in this month.</p>
                        @else
                            <ul class="space-y-2">
                                @foreach($lostClientsList as $entry)
                                    <li class="flex items-start gap-2 text-sm text-gray-700">
                                        <span class="text-gray-300 mt-0.5">•</span>
                                        <span class="truncate">{{ $entry->client->business_name ?? 'Unknown Client' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                </div>
            </div>

            {{-- ====================== Row 5: Recent Activity ====================== --}}
            <div class="bg-white rounded-lg shadow p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Recent Activity</h3>
                @if($recentActivity->isEmpty())
                    <p class="text-xs text-gray-400">No recent transitions yet. Status changes will appear here.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($recentActivity as $activity)
                            @php
                                if ($activity->status_to === 'active' && $activity->status_from !== 'active') {
                                    $dotColor = 'bg-green-500';
                                    $summary = 'Activated';
                                } elseif ($activity->status_to === 'inactive' && $activity->status_from === 'active') {
                                    $dotColor = 'bg-red-500';
                                    $summary = 'Lost';
                                } elseif ($activity->stage_from !== $activity->stage_to) {
                                    $dotColor = 'bg-blue-500';
                                    $summary = 'Stage: '
                                        . str_replace('_', ' ', $activity->stage_from ?? 'NULL')
                                        . ' → '
                                        . str_replace('_', ' ', $activity->stage_to ?? 'NULL');
                                } else {
                                    $dotColor = 'bg-gray-400';
                                    $summary = 'Status: '
                                        . str_replace('_', ' ', $activity->status_from ?? 'NULL')
                                        . ' → '
                                        . str_replace('_', ' ', $activity->status_to ?? 'NULL');
                                }
                            @endphp
                            <li class="flex items-start gap-3">
                                <span class="inline-block w-2 h-2 rounded-full {{ $dotColor }} mt-1.5 flex-shrink-0"></span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">
                                        {{ $activity->client->business_name ?? 'Unknown Client' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <span class="capitalize">{{ $summary }}</span>
                                        <span class="mx-1">·</span>
                                        <span>{{ $activity->changed_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('crmTrendChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($trendLabels),
                    datasets: [
                        {
                            label: 'New Active',
                            data: @json($trendActivations),
                            backgroundColor: '#22c55e',
                            borderRadius: 4,
                        },
                        {
                            label: 'Lost',
                            data: @json($trendLosses),
                            backgroundColor: '#ef4444',
                            borderRadius: 4,
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    const label = ctx.dataset.label;
                                    const v = ctx.parsed.y;
                                    return label + ': ' + v + (v !== 1 ? ' clients' : ' client');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { color: '#f3f4f6' },
                        },
                        x: {
                            grid: { display: false },
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>