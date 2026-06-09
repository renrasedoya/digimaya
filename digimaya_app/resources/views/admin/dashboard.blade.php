<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[['label' => 'Dashboard']]" />
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Welcome card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">
                        Logged in as <strong>{{ Auth::user()->role_label }}</strong>.
                    </p>
                    @if(Auth::user()->isSuperAdmin())
                        <div class="mt-4">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Manage Users
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Recent Activity card (super_admin only) --}}
            @if(Auth::user()->isSuperAdmin())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                        <a href="{{ route('admin.activity-log.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            View All →
                        </a>
                    </div>

                    @if($recentActivities->isEmpty())
                        <div class="p-6 text-sm text-gray-500 italic">
                            No activity yet.
                        </div>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach($recentActivities as $activity)
                                <li class="px-6 py-3 hover:bg-gray-50">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium text-gray-900">
                                                    {{ $activity->causer?->name ?? 'System' }}
                                                </span>
                                                <span class="text-gray-500">
                                                    {{ \App\Helpers\ActivityFormatter::actionVerb($activity) }}
                                                </span>
                                                <span class="font-medium text-gray-800">
                                                    {{ \App\Helpers\ActivityFormatter::subjectLabel($activity) }}
                                                </span>
                                            </p>
                                        </div>
                                        <span class="text-xs text-gray-400 ms-3 whitespace-nowrap">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
