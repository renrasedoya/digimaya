<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Activity Log
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Activity Log']
            ]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('admin.activity-log.index') }}" class="flex flex-wrap items-center gap-2">
                    <select name="date_preset" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        @foreach($datePresets as $key => $label)
                            <option value="{{ $key }}" @selected($datePreset === $key)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <select name="user_id" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <option value="">All users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected($userId == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>

                    <select name="log_name" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <option value="">All modules</option>
                        @foreach($logNames as $key => $label)
                            <option value="{{ $key }}" @selected($logName === $key)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <select name="event" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <option value="">All actions</option>
                        @foreach($events as $key => $label)
                            <option value="{{ $key }}" @selected($event === $key)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                    <a href="{{ route('admin.activity-log.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>

                    <span class="ms-auto text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}
                        @if($dateFrom !== $dateTo)
                            — {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                        @endif
                    </span>
                </form>
            </div>

            {{-- Activity table --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Activities</h3>
                    <span class="text-xs text-gray-500">
                        {{ $activities->total() }} {{ Str::plural('activity', $activities->total()) }} found
                    </span>
                </div>

                @if($activities->isEmpty())
                    <div class="p-6 text-sm text-gray-500 italic">
                        No activities match your filters.
                    </div>
                @else
                    <table class="w-full text-sm" x-data="{ expanded: null }">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-6 py-3 text-left">When</th>
                                <th class="px-6 py-3 text-left">User</th>
                                <th class="px-6 py-3 text-left">Action</th>
                                <th class="px-6 py-3 text-left">Subject</th>
                                <th class="px-6 py-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($activities as $activity)
                                @php
                                    $diff = \App\Helpers\ActivityFormatter::diffSummary($activity);
                                    $hasDetail = !empty($diff);
                                @endphp
                                <tr class="hover:bg-gray-50 cursor-pointer"
                                    @if($hasDetail) @click="expanded = (expanded === {{ $activity->id }} ? null : {{ $activity->id }})" @endif>
                                    <td class="px-6 py-3 text-gray-500 whitespace-nowrap">
                                        {{ $activity->created_at->format('d M Y H:i') }}
                                        <div class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-gray-700 whitespace-nowrap">
                                        {{ $activity->causer?->name ?? 'System' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            @switch($activity->event)
                                                @case('created') bg-green-100 text-green-800 @break
                                                @case('updated') bg-blue-100 text-blue-800 @break
                                                @case('deleted') bg-red-100 text-red-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            {{ \App\Helpers\ActivityFormatter::actionVerb($activity) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-gray-800">
                                        {{ \App\Helpers\ActivityFormatter::subjectLabel($activity) }}
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        @if($hasDetail)
                                            <span class="text-gray-400 text-xs"
                                                  x-show="expanded !== {{ $activity->id }}">▼</span>
                                            <span class="text-gray-400 text-xs"
                                                  x-show="expanded === {{ $activity->id }}"
                                                  x-cloak>▲</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($hasDetail)
                                    <tr x-show="expanded === {{ $activity->id }}" x-cloak>
                                        <td colspan="5" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                                            <div class="text-xs font-semibold text-gray-700 mb-2">Field Changes</div>
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="text-gray-500 border-b border-gray-200">
                                                        <th class="text-left py-1.5 pr-4">Field</th>
                                                        <th class="text-left py-1.5 pr-4">Before</th>
                                                        <th class="text-left py-1.5">After</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($diff as $row)
                                                        <tr class="border-b border-gray-100 last:border-b-0">
                                                            <td class="py-1.5 pr-4 font-medium text-gray-700">{{ $row['field'] }}</td>
                                                            <td class="py-1.5 pr-4 text-red-600 line-through">{{ $row['old'] }}</td>
                                                            <td class="py-1.5 text-green-700">{{ $row['new'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-12 border-t border-gray-200 px-6 py-6">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
