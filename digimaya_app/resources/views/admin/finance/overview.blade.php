<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Finance Overview') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Finance Overview']]" />
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                {{-- Income card --}}
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-500 uppercase font-medium">Income This Month</div>
                        @if($incomeChange !== null)
                            <span class="text-xs font-semibold {{ $incomeChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $incomeChange >= 0 ? '+' : '' }}{{ $incomeChange }}%
                            </span>
                        @endif
                    </div>
                    <div class="mt-2 text-2xl font-bold text-green-600">IDR {{ number_format($incomeThisMonth, 0, '.', ',') }}</div>
                    <div class="mt-1 text-xs text-gray-500">vs last month</div>
                </div>

                {{-- Expense card --}}
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-500 uppercase font-medium">Expense This Month</div>
                        @if($expenseChange !== null)
                            <span class="text-xs font-semibold {{ $expenseChange <= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $expenseChange >= 0 ? '+' : '' }}{{ $expenseChange }}%
                            </span>
                        @endif
                    </div>
                    <div class="mt-2 text-2xl font-bold text-red-600">IDR {{ number_format($expenseThisMonth, 0, '.', ',') }}</div>
                    <div class="mt-1 text-xs text-gray-500">vs last month</div>
                </div>

                {{-- Net Profit card --}}
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-500 uppercase font-medium">Net Profit This Month</div>
                        @if($profitChange !== null)
                            <span class="text-xs font-semibold {{ $profitChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $profitChange >= 0 ? '+' : '' }}{{ $profitChange }}%
                            </span>
                        @endif
                    </div>
                    <div class="mt-2 text-2xl font-bold {{ $profitThisMonth >= 0 ? 'text-indigo-600' : 'text-red-600' }}">IDR {{ number_format($profitThisMonth, 0, '.', ',') }}</div>
                    <div class="mt-1 text-xs text-gray-500">vs last month</div>
                </div>

                {{-- Total Balance card --}}
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-500 uppercase font-medium">Total Balance</div>
                        @if($balanceChange !== null)
                            <span class="text-xs font-semibold {{ $balanceChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $balanceChange >= 0 ? '+' : '' }}{{ $balanceChange }}%
                            </span>
                        @endif
                    </div>
                    @if($hasBalanceData)
                        <div class="mt-2 text-2xl font-bold text-gray-900">IDR {{ number_format($totalBalance, 0, '.', ',') }}</div>
                        <div class="mt-1 text-xs text-gray-500">Latest: {{ $balancePeriodLabel }}</div>
                    @else
                        <div class="mt-2 text-2xl font-bold text-gray-400">—</div>
                        <div class="mt-1 text-xs text-gray-500">
                            <a href="{{ route('admin.balances.create') }}" class="text-indigo-600 hover:text-indigo-800">Belum ada laporan balance</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Income breakdown by category card --}}
            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="text-sm font-medium text-gray-700 mb-3">Income Breakdown This Month</div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-3 rounded-md bg-blue-50 border border-blue-100">
                        <div class="text-xs text-blue-600 uppercase font-medium">Agency</div>
                        <div class="mt-1 text-lg font-bold text-blue-700">IDR {{ number_format($incomeByCategory['agency'], 0, '.', ',') }}</div>
                    </div>
                    <div class="p-3 rounded-md bg-purple-50 border border-purple-100">
                        <div class="text-xs text-purple-600 uppercase font-medium">Academy</div>
                        <div class="mt-1 text-lg font-bold text-purple-700">IDR {{ number_format($incomeByCategory['academy'], 0, '.', ',') }}</div>
                    </div>
                    <div class="p-3 rounded-md bg-gray-50 border border-gray-100">
                        <div class="text-xs text-gray-600 uppercase font-medium">Other</div>
                        <div class="mt-1 text-lg font-bold text-gray-700">IDR {{ number_format($incomeByCategory['other'], 0, '.', ',') }}</div>
                    </div>
                </div>
            </div>

            {{-- Charts row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Chart 1: Income vs Expense --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Income vs Expense</h3>
                    <p class="text-xs text-gray-500 mb-4">Last 6 months comparison</p>
                    <div class="relative h-72">
                        <canvas id="chartIncomeExpense"></canvas>
                    </div>
                </div>

                {{-- Chart 2: Net Profit Trend --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Net Profit Trend</h3>
                    <p class="text-xs text-gray-500 mb-4">Last 6 months</p>
                    <div class="relative h-72">
                        <canvas id="chartProfit"></canvas>
                    </div>
                </div>
            </div>

            {{-- Chart 3: Total Balance Trend (full width, 12 months) --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mt-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-1">Total Balance Trend</h3>
                        <p class="text-xs text-gray-500">Last 12 months · closing balance per month</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="inline-block w-3 h-3 rounded-sm" style="background: #1D9E75;"></span>
                        <span>Total Balance</span>
                    </div>
                </div>
                <div class="relative h-72">
                    <canvas id="chartBalanceTrend"></canvas>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthsData = @json($monthsData);
            const labels = monthsData.map(m => m.label);
            const incomes = monthsData.map(m => m.income);
            const expenses = monthsData.map(m => m.expense);
            const profits = monthsData.map(m => m.profit);

            const formatCurrency = (val) => 'IDR ' + Number(val).toLocaleString('en-US');

            // Chart 1: Income vs Expense (bar)
            new Chart(document.getElementById('chartIncomeExpense'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Income',
                            data: incomes,
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                            borderColor: 'rgb(22, 163, 74)',
                            borderWidth: 1,
                        },
                        {
                            label: 'Expense',
                            data: expenses,
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderColor: 'rgb(220, 38, 38)',
                            borderWidth: 1,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ctx.dataset.label + ': ' + formatCurrency(ctx.parsed.y)
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (val) => 'IDR ' + Number(val).toLocaleString('en-US', { notation: 'compact' })
                            }
                        }
                    }
                }
            });

            // Chart 2: Net Profit Trend (line)
            new Chart(document.getElementById('chartProfit'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Net Profit',
                        data: profits,
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => 'Profit: ' + formatCurrency(ctx.parsed.y)
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: (val) => 'IDR ' + Number(val).toLocaleString('en-US', { notation: 'compact' })
                            }
                        }
                    }
                }
            });

            // Chart 3: Total Balance Trend (line, 12 months)
            const balanceTrend = @json($balanceTrend);
            const balanceLabels = balanceTrend.map(m => m.label);
            const balanceValues = balanceTrend.map(m => m.value);

            new Chart(document.getElementById('chartBalanceTrend'), {
                type: 'line',
                data: {
                    labels: balanceLabels,
                    datasets: [{
                        label: 'Total Balance',
                        data: balanceValues,
                        backgroundColor: 'rgba(29, 158, 117, 0.08)',
                        borderColor: '#1D9E75',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        spanGaps: false,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#1D9E75',
                        pointBorderColor: '#1D9E75',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ctx.parsed.y === null ? 'Belum dilaporkan' : 'Total: ' + formatCurrency(ctx.parsed.y)
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                maxRotation: 45,
                                autoSkip: false,
                                font: { size: 11 }
                            },
                            grid: { display: false }
                        },
                        y: {
                            ticks: {
                                callback: (val) => 'IDR ' + Number(val).toLocaleString('en-US', { notation: 'compact' })
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
