<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Marketing Overview') }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Marketing'], ['label' => 'Overview']]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Row 1: Funnel Metrics --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                {{-- New Leads This Month --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="text-sm text-gray-500 mb-1">New Leads This Month</div>
                    <div class="text-3xl font-semibold text-gray-900">{{ $newLeadsThisMonth }}</div>
                    <div class="text-xs mt-2
                        @if($newLeadsDelta > 0) text-green-600
                        @elseif($newLeadsDelta < 0) text-red-600
                        @else text-gray-400
                        @endif">
                        @if($newLeadsDelta > 0)
                            ↑ +{{ $newLeadsDelta }} vs last month
                        @elseif($newLeadsDelta < 0)
                            ↓ {{ $newLeadsDelta }} vs last month
                        @else
                            No change vs last month
                        @endif
                    </div>
                </div>

                {{-- Conversion Rate --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="text-sm text-gray-500 mb-1">Conversion Rate</div>
                    <div class="text-3xl font-semibold text-gray-900">{{ $conversionRateThisMonth }}%</div>
                    <div class="text-xs mt-2
                        @if($conversionRateDelta > 0) text-green-600
                        @elseif($conversionRateDelta < 0) text-red-600
                        @else text-gray-400
                        @endif">
                        @if($conversionRateDelta > 0)
                            ↑ +{{ $conversionRateDelta }}% vs last month
                        @elseif($conversionRateDelta < 0)
                            ↓ {{ $conversionRateDelta }}% vs last month
                        @else
                            No change vs last month
                        @endif
                    </div>
                </div>

                {{-- Active Leads --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="text-sm text-gray-500 mb-1">Active Leads</div>
                    <div class="text-3xl font-semibold text-gray-900">{{ $activeLeadsCount }}</div>
                    <div class="text-xs mt-2
                        @if($unassignedActiveLeads > 0) text-yellow-600
                        @else text-gray-400
                        @endif">
                        @if($unassignedActiveLeads > 0)
                            {{ $unassignedActiveLeads }} unassigned
                        @else
                            All assigned
                        @endif
                    </div>
                </div>

            </div>

            {{-- Row 2: Status Breakdown + Top Sources --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                {{-- Status Breakdown --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="flex justify-between items-baseline mb-4">
                        <div class="text-sm font-medium text-gray-700">Status Breakdown</div>
                        <div class="text-xs text-gray-400">all-time snapshot</div>
                    </div>
                    @php
                        $statusColors = [
                            'new'          => 'bg-blue-500',
                            'contacted'    => 'bg-yellow-500',
                            'screened'     => 'bg-purple-500',
                            'promoted'     => 'bg-green-500',
                            'disqualified' => 'bg-red-500',
                        ];
                    @endphp
                    <div class="space-y-3">
                        @foreach($statusBreakdown as $status => $count)
                            <a href="{{ route('admin.leads.index', ['status' => $status]) }}" class="flex items-center gap-3 text-sm hover:opacity-80 transition">
                                <span class="w-24 text-gray-600">{{ \App\Models\Lead::STATUSES[$status] ?? ucfirst($status) }}</span>
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $statusColors[$status] ?? 'bg-gray-400' }}" style="width: {{ $count > 0 ? ($count / $statusBreakdownMax * 100) : 0 }}%"></div>
                                </div>
                                <span class="w-8 text-right font-medium text-gray-900">{{ $count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Top Sources --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="flex justify-between items-baseline mb-4">
                        <div class="text-sm font-medium text-gray-700">Top Sources</div>
                        <div class="text-xs text-gray-400">last 30 days</div>
                    </div>
                    @if(empty($sourceLast30Days))
                        <div class="text-center py-8 text-gray-400 text-sm">
                            No leads in the last 30 days
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($sourceLast30Days as $source => $count)
                                <a href="{{ route('admin.leads.index', ['source' => $source]) }}" class="flex items-center gap-3 text-sm hover:opacity-80 transition">
                                    <span class="w-32 text-gray-600 truncate">{{ \App\Models\Lead::SOURCES[$source] ?? ucfirst($source) }}</span>
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500" style="width: {{ $count > 0 ? ($count / $sourceMax * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="w-8 text-right font-medium text-gray-900">{{ $count }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Row 3: Trend Chart --}}
            <div class="bg-white rounded-lg shadow-sm p-5">
                <div class="flex justify-between items-baseline mb-4">
                    <div class="text-sm font-medium text-gray-700">Lead Inflow & Conversion</div>
                    <div class="text-xs text-gray-400">last 12 months</div>
                </div>

                <div class="flex flex-wrap gap-4 mb-4 text-xs text-gray-600">
                    <span class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 bg-blue-500 rounded-sm"></span>
                        Leads Masuk
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 bg-green-500 rounded-sm"></span>
                        Promoted to Client
                    </span>
                </div>

                <div style="position: relative; height: 280px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('trendChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($trendLabels),
                    datasets: [
                        {
                            label: 'Leads Masuk',
                            data: @json($trendInflow),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            borderWidth: 2,
                            fill: false,
                        },
                        {
                            label: 'Promoted to Client',
                            data: @json($trendPromoted),
                            borderColor: '#22C55E',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderDash: [4, 4],
                            tension: 0.3,
                            borderWidth: 2,
                            fill: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0 }
                        },
                        x: {
                            ticks: { autoSkip: false, maxRotation: 45 }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
