<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Balance') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Balance']]" />
                </div>
            </div>
            <a href="{{ route('admin.balances.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Add Balance
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @php
                // Filter scope label
                if ($isSpecificPeriod) {
                    $filterScopeLabel = \Carbon\Carbon::create($year, $month)->format('F Y');
                } elseif (!$hasMonthFilter && $hasYearFilter) {
                    $filterScopeLabel = 'Semua bulan di ' . $year;
                } elseif ($hasMonthFilter && !$hasYearFilter) {
                    $filterScopeLabel = 'Semua tahun di ' . \Carbon\Carbon::create()->month($month)->format('F');
                } else {
                    $filterScopeLabel = 'All Time';
                }

                // Latest snapshot label (only when data exists)
                $hasSnapshot = $latestPeriodYear !== null && $latestPeriodMonth !== null;
                if ($hasSnapshot) {
                    $snapshotShort = \Carbon\Carbon::create($latestPeriodYear, $latestPeriodMonth)->format('M Y');
                    $snapshotLong = \Carbon\Carbon::create($latestPeriodYear, $latestPeriodMonth)->format('F Y');
                } else {
                    $snapshotShort = 'N/A';
                    $snapshotLong = 'Belum ada data';
                }

                // Total Balance card subtext
                if (!$hasSnapshot) {
                    $totalSubtext = 'Belum ada laporan balance';
                } elseif ($isSpecificPeriod) {
                    $totalSubtext = 'Per akhir ' . $snapshotLong;
                } else {
                    $totalSubtext = 'Snapshot terkini · per akhir ' . $snapshotLong;
                }

                // Total Balance card header
                if (!$hasSnapshot) {
                    $totalHeader = 'Total Balance';
                } elseif ($isSpecificPeriod) {
                    $totalHeader = 'Total Balance (' . $snapshotShort . ')';
                } else {
                    $totalHeader = 'Total Balance (Latest: ' . $snapshotShort . ')';
                }
            @endphp

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">{{ $totalHeader }}</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">IDR {{ number_format($totalBalance, 0, '.', ',') }}</div>
                    <div class="mt-1 text-xs text-gray-500">{{ $totalSubtext }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">{{ $isSpecificPeriod ? 'Coverage' : 'Records' }}</div>
                    @if($isSpecificPeriod)
                        <div class="mt-1 text-2xl font-bold {{ $accountsReported < $activeAccounts ? 'text-yellow-700' : 'text-green-700' }}">
                            {{ $accountsReported }} / {{ $activeAccounts }}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            @if($accountsReported < $activeAccounts)
                                {{ $activeAccounts - $accountsReported }} rekening belum dilaporkan
                            @else
                                Semua rekening aktif sudah dilaporkan
                            @endif
                        </div>
                    @else
                        <div class="mt-1 text-2xl font-bold text-gray-900">{{ $accountsReported }}</div>
                        <div class="mt-1 text-xs text-gray-500">Jumlah laporan balance di scope filter</div>
                    @endif
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">MoM Change</div>
                    @php
                        if (!$hasSnapshot) {
                            $momState = 'no_data';
                        } elseif (!$momHasPrior) {
                            $momState = 'no_prior';
                        } elseif ($momDelta === 0.0 || $momDelta === 0) {
                            $momState = 'stable';
                        } elseif ($momDelta > 0) {
                            $momState = 'up';
                        } else {
                            $momState = 'down';
                        }

                        $colorClass = match($momState) {
                            'up' => 'text-green-700',
                            'down' => 'text-red-700',
                            'stable' => 'text-gray-600',
                            default => 'text-gray-500',
                        };
                        $arrow = match($momState) {
                            'up' => '↑ ',
                            'down' => '↓ ',
                            default => '',
                        };
                        $prevMonthLabel = $momPreviousMonth ? \Carbon\Carbon::create($momPreviousYear, $momPreviousMonth)->format('M Y') : null;
                    @endphp

                    @if($momState === 'no_data')
                        <div class="mt-1 text-2xl font-bold text-gray-400">—</div>
                        <div class="mt-1 text-xs text-gray-500">No data</div>
                    @elseif($momState === 'no_prior')
                        <div class="mt-1 text-2xl font-bold text-gray-400">—</div>
                        <div class="mt-1 text-xs text-gray-500">No prior period</div>
                    @else
                        <div class="mt-1 text-2xl font-bold {{ $colorClass }}">
                            {!! $arrow !!}{{ $momDelta >= 0 ? '+' : '' }}{{ number_format($momDelta, 0, '.', ',') }}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            @if($momPercent !== null)
                                {{ $momPercent >= 0 ? '+' : '' }}{{ number_format($momPercent, 1, '.', ',') }}% from {{ $prevMonthLabel }}
                            @else
                                vs {{ $prevMonthLabel }} (no prior baseline)
                            @endif
                            @if($momCoverageMatch === false)
                                <div class="mt-1 text-yellow-700">Coverage: {{ $momAnchorCount }} vs {{ $momPreviousCount }} accounts</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Filters --}}
                    <form method="GET" action="{{ route('admin.balances.index') }}" class="mb-6 flex flex-wrap gap-2">
                        <select name="month" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="0" {{ $month == 0 ? 'selected' : '' }}>All Months</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="0" {{ $year == 0 ? 'selected' : '' }}>All Years</option>
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.balances.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    {{-- Table --}}
                    @if($balances->isEmpty())
                        <p class="text-gray-500 text-center py-8">Belum ada laporan balance untuk {{ $filterScopeLabel }}.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bank Account</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Balance</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reported By</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($balances as $balance)
                                    <tr>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            {{ \Carbon\Carbon::create($balance->year, $balance->month)->format('F Y') }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <div class="font-medium">{{ $balance->bankAccount?->bank_name ?: 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $balance->bankAccount?->account_number ?: '' }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900 text-right font-semibold">
                                            IDR {{ number_format($balance->balance_amount, 0, '.', ',') }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-600">
                                            {{ $balance->notes ? \Illuminate\Support\Str::limit($balance->notes, 60) : '-' }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-600">
                                            <div>{{ $balance->creator?->name ?: 'Unknown' }}</div>
                                            <div class="text-xs text-gray-400">{{ $balance->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-right">
                                            <a href="{{ route('admin.balances.edit', $balance) }}" class="text-indigo-600 hover:text-indigo-800 mr-3">Edit</a>
                                            <form method="POST" action="{{ route('admin.balances.destroy', $balance) }}" class="inline" onsubmit="return confirm('Delete balance report for {{ $balance->bankAccount?->bank_name }} ({{ \Carbon\Carbon::create($balance->year, $balance->month)->format('F Y') }})?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-12">{{ $balances->links() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
