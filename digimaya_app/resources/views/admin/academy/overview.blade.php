<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Academy Overview') }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Academy'], ['label' => 'Overview']]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Row 1: KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

                {{-- Total Active Members --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="text-sm text-gray-500 mb-1">Total Active Members</div>
                    <div class="text-3xl font-semibold text-gray-900">{{ $totalActiveMembers }}</div>
                    <div class="text-xs text-gray-400 mt-2">Members with active access</div>
                </div>

                {{-- New Members This Month + delta --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="text-sm text-gray-500 mb-1">New Members This Month</div>
                    <div class="text-3xl font-semibold text-gray-900">{{ $newMembersThisMonth }}</div>
                    <div class="text-xs mt-2
                        @if($newMembersDelta > 0) text-green-600
                        @elseif($newMembersDelta < 0) text-red-600
                        @else text-gray-400
                        @endif">
                        @if($newMembersDelta > 0)
                            ↑ +{{ $newMembersDelta }} vs last month
                        @elseif($newMembersDelta < 0)
                            ↓ {{ $newMembersDelta }} vs last month
                        @else
                            No change vs last month
                        @endif
                    </div>
                </div>

                {{-- Active Certificates --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="text-sm text-gray-500 mb-1">Active Certificates</div>
                    <div class="text-3xl font-semibold text-gray-900">{{ $activeCertificates }}</div>
                    <div class="text-xs text-gray-400 mt-2">Issued and not revoked</div>
                </div>

                {{-- Pending Certificate Requests --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="text-sm text-gray-500 mb-1">Pending Requests</div>
                    <div class="text-3xl font-semibold {{ $pendingRequests > 0 ? 'text-orange-600' : 'text-gray-900' }}">
                        {{ $pendingRequests }}
                    </div>
                    @if($pendingRequests > 0)
                        <div class="text-xs text-orange-600 mt-2">
                            <a href="{{ route('admin.academy.certificate-requests.index') }}" class="hover:underline">
                                Awaiting review →
                            </a>
                        </div>
                    @else
                        <div class="text-xs text-gray-400 mt-2">No pending requests</div>
                    @endif
                </div>

            </div>

            {{-- Row 2: Member Growth Chart --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Member Growth</h3>
                    <span class="text-xs text-gray-500">Last 12 months</span>
                </div>
                <div style="height: 320px;">
                    <canvas id="memberGrowthChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('memberGrowthChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                        label: 'New Members',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(22, 93, 255, 0.6)',
                        borderColor: '#165DFF',
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' new member' + (context.parsed.y !== 1 ? 's' : '');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
