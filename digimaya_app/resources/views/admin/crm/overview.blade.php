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

                {{-- ARPA --}}
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="text-sm font-medium text-gray-500">
                        <x-col-tip label="ARPA" align="left">ARPA (Average Revenue Per Account) = MRR ÷ jumlah client aktif. Rata-rata pendapatan bulanan dari tiap client aktif. Makin tinggi, makin besar nilai rata-rata per client.</x-col-tip>
                    </div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">Rp{{ number_format($arpa, 0, ',', '.') }}</div>
                    <div class="mt-1 text-xs text-gray-500">Rata-rata MRR per client aktif</div>
                </div>

                {{-- Active Clients --}}
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="text-sm font-medium text-gray-500">
                        <x-col-tip label="Active Clients" align="left">Jumlah client yang sedang aktif (berlangganan). Persentase dihitung dari total client riil (aktif + inactive + churned), tanpa menghitung prospek.</x-col-tip>
                    </div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($activeClients) }}</div>
                    <div class="mt-1 text-xs text-gray-500">
                        {{ $activeClients }} dari {{ $realClients }} client
                        @if($realClients > 0)
                            ({{ round(($activeClients / $realClients) * 100) }}%)
                        @endif
                        <span class="text-gray-400">· tanpa prospek</span>
                    </div>
                </div>

                {{-- MRR --}}
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="text-sm font-medium text-gray-500">
                        <x-col-tip label="MRR (Active)" align="left">MRR (Monthly Recurring Revenue) = total retainer bulanan dari semua client aktif. Pendapatan berulang yang masuk tiap bulan.</x-col-tip>
                    </div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">
                        Rp{{ number_format($mrr, 0, ',', '.') }}
                    </div>
                    @if($activeNoRetainer > 0)
                        <div class="mt-1 text-xs text-yellow-700">
                            <span class="font-medium">Angka ini terlalu rendah.</span>
                            {{ $activeNoRetainer }} client aktif belum diisi retainer-nya.
                        </div>
                    @else
                        <div class="mt-1 text-xs text-gray-500">Total retainer bulanan client aktif</div>
                    @endif
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
                        {{ $freshProspects }} dalam 30 hari · {{ $agedProspects }} lebih lama
                        @if($agedProspectsNeedFu > 0)
                            · <span class="text-yellow-700 font-medium">{{ $agedProspectsNeedFu }} perlu FU</span>
                        @endif
                    </div>
                </a>

            </div>

            {{-- ====================== Row 2: Monthly performance table (12 months) ====================== --}}
            <div class="bg-white rounded-lg shadow p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">
                        Monthly Performance —
                        {{ count($monthlyPerformance) >= 12 ? 'Last 12 Months' : 'Sejak ' . $historyFrom->format('M Y') }}
                    </h3>
                    @if(count($monthlyPerformance) < 12)
                        <span class="text-xs text-gray-400">
                            {{ count($monthlyPerformance) }} bulan riwayat tersedia
                        </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-xs uppercase tracking-wide text-gray-500 border-b border-gray-200">
                                <th class="text-left font-semibold py-2 pr-4">Month</th>
                                <th class="text-right font-semibold py-2 px-3">
                                    <x-col-tip label="New Active" align="left">Semua client yang menjadi aktif bulan ini — dari sumber mana pun: prospek yang closing, reaktivasi, dan win-back client lama.</x-col-tip>
                                </th>
                                <th class="text-right font-semibold py-2 px-3">
                                    <x-col-tip label="Churned" align="left">Client aktif yang berhenti bulan ini (menjadi inactive atau churned).</x-col-tip>
                                </th>
                                <th class="text-right font-semibold py-2 px-3">
                                    <x-col-tip label="Net" align="left">New Active dikurangi Churned — pertumbuhan bersih jumlah client aktif.</x-col-tip>
                                </th>
                                <th class="text-right font-semibold py-2 px-3">
                                    <x-col-tip label="Won">Prospek yang berhasil menjadi client aktif (prospect → active). Bagian dari New Active.</x-col-tip>
                                </th>
                                <th class="text-right font-semibold py-2 px-3">
                                    <x-col-tip label="Lost">Prospek yang gagal closing (prospect → lost). Berbeda dengan churn client.</x-col-tip>
                                </th>
                                <th class="text-right font-semibold py-2 px-3">
                                    <x-col-tip label="Win %">Won ÷ (Won + Lost) yang diputuskan bulan ini — tingkat kemenangan prospek. Tampil “—” bila tidak ada prospek yang diputuskan.</x-col-tip>
                                </th>
                                <th class="text-right font-semibold py-2 px-3">
                                    <x-col-tip label="Churn %">Churned ÷ jumlah client aktif di awal bulan — tingkat kehilangan client bulanan. Tampil “—” bila tidak ada client aktif.</x-col-tip>
                                </th>
                                <th class="text-right font-semibold py-2 px-3" style="border-left: 1px solid #f3f4f6;">
                                    <x-col-tip label="New MRR">Total retainer bulanan dari client yang menjadi aktif bulan ini.</x-col-tip>
                                </th>
                                <th class="text-right font-semibold py-2 px-3">
                                    <x-col-tip label="Churned MRR">Total retainer bulanan yang hilang dari client yang churn bulan ini.</x-col-tip>
                                </th>
                                <th class="text-right font-semibold py-2 pl-3">
                                    <x-col-tip label="Net MRR">New MRR dikurangi Churned MRR — perubahan bersih pendapatan bulanan berulang (MRR).</x-col-tip>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($monthlyPerformance as $row)
                                <tr class="text-gray-700 hover:bg-gray-50 {{ $row['isCurrent'] ? 'bg-gray-50' : '' }}">
                                    <td class="py-2 pr-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $row['label'] }}
                                        @if($row['isCurrent'])
                                            <span class="font-normal text-gray-400" style="font-size: 10px;">(so far)</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 text-right tabular-nums">{{ $row['newActive'] }}</td>
                                    <td class="py-2 px-3 text-right tabular-nums">{{ $row['churned'] }}</td>
                                    <td class="py-2 px-3 text-right tabular-nums font-medium
                                        @if($row['net'] > 0) text-green-600
                                        @elseif($row['net'] < 0) text-red-600
                                        @else text-gray-400 @endif">
                                        {{ $row['net'] > 0 ? '+' : '' }}{{ $row['net'] }}
                                    </td>
                                    <td class="py-2 px-3 text-right tabular-nums">{{ $row['won'] }}</td>
                                    <td class="py-2 px-3 text-right tabular-nums">{{ $row['lostProsp'] }}</td>
                                    <td class="py-2 px-3 text-right tabular-nums">
                                        @if(is_null($row['winRate']))
                                            <span class="text-gray-300">—</span>
                                        @else
                                            {{ $row['winRate'] }}%
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 text-right tabular-nums">
                                        @if(is_null($row['churnRate']))
                                            <span class="text-gray-300">—</span>
                                        @else
                                            <span class="{{ $row['churnRate'] > 0 ? 'text-red-600' : 'text-gray-500' }}">{{ rtrim(rtrim(number_format($row['churnRate'], 1), '0'), '.') }}%</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 text-right tabular-nums whitespace-nowrap" style="border-left: 1px solid #f3f4f6;">
                                        @if(is_null($row['newMrrFmt']))
                                            <span class="text-gray-300">—</span>
                                        @else
                                            {{ $row['newMrrFmt'] }}
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 text-right tabular-nums whitespace-nowrap">
                                        @if(is_null($row['churnedMrrFmt']))
                                            <span class="text-gray-300">—</span>
                                        @else
                                            {{ $row['churnedMrrFmt'] }}
                                        @endif
                                    </td>
                                    <td class="py-2 pl-3 text-right tabular-nums whitespace-nowrap font-medium">
                                        @if(is_null($row['netMrrFmt']))
                                            <span class="text-gray-300">—</span>
                                        @else
                                            <span class="{{ $row['netMrr'] > 0 ? 'text-green-600' : ($row['netMrr'] < 0 ? 'text-red-600' : 'text-gray-400') }}">{{ $row['netMrrFmt'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-xs text-gray-400">
                    Arahkan kursor atau ketuk judul kolom untuk melihat penjelasannya.
                    @if(count($monthlyPerformance) < 12)
                        Bulan sebelum {{ $historyFrom->format('M Y') }} tidak ditampilkan: riwayatnya tidak bisa direkonstruksi
                        karena data client baru dimasukkan ke CRM saat itu, bukan karena tidak ada aktivitas.
                    @endif
                </p>
            </div>

            {{-- ====================== Row 3: New & Lost Clients (with month filter) ====================== --}}
            <div class="bg-white rounded-lg shadow p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">New &amp; Churned Clients</h3>
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
                                Churned ({{ $lostClientsList->count() }})
                            </h4>
                        </div>
                        @if($lostClientsList->isEmpty())
                            <p class="text-xs text-gray-400">No churned clients in this month.</p>
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

            {{-- ====================== Row 4: Recent Activity ====================== --}}
            <div class="bg-white rounded-lg shadow p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Recent Activity</h3>
                @if($recentActivity->isEmpty())
                    <p class="text-xs text-gray-400">No recent transitions yet. Status changes will appear here.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($recentActivity as $activity)
                            @php
                                // Creation first: a row with no previous status is a new record,
                                // not a transition — otherwise it renders as "NULL → something".
                                if ($activity->status_from === null && $activity->status_to !== 'active') {
                                    $dotColor = 'bg-gray-400';
                                    $summary = 'Ditambahkan sebagai ' . str_replace('_', ' ', $activity->status_to);
                                } elseif ($activity->status_to === 'active' && $activity->status_from !== 'active') {
                                    $dotColor = 'bg-green-500';
                                    $summary = 'Activated';
                                } elseif ($activity->status_from === 'active' && in_array($activity->status_to, ['inactive', 'churned'], true)) {
                                    $dotColor = 'bg-red-500';
                                    $summary = 'Churned';
                                } elseif ($activity->stage_from !== $activity->stage_to) {
                                    $dotColor = 'bg-blue-500';
                                    $summary = 'Stage: '
                                        . str_replace('_', ' ', $activity->stage_from ?? '—')
                                        . ' → '
                                        . str_replace('_', ' ', $activity->stage_to ?? '—');
                                } elseif ($activity->status_from === null) {
                                    // No previous status = the client record was just created.
                                    $dotColor = 'bg-gray-400';
                                    $summary = 'Ditambahkan sebagai ' . str_replace('_', ' ', $activity->status_to);
                                } else {
                                    $dotColor = 'bg-gray-400';
                                    $summary = 'Status: '
                                        . str_replace('_', ' ', $activity->status_from)
                                        . ' → '
                                        . str_replace('_', ' ', $activity->status_to ?? '?');
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
</x-app-layout>