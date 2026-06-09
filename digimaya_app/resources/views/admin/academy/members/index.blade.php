<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Members') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Academy'],
                        ['label' => 'Members']
                    ]" />
                </div>
            </div>
            <a href="{{ route('admin.academy.members.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Enroll Member
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-4 bg-amber-100 border border-amber-400 text-amber-700 px-4 py-3 rounded">
                    {{ session('warning') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="GET" action="{{ route('admin.academy.members.index') }}" class="mb-6 flex flex-wrap gap-2">
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
                        <select name="tier" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Tiers</option>
                            <option value="{{ \App\Models\Member::TIER_FREE }}" {{ request('tier') === \App\Models\Member::TIER_FREE ? 'selected' : '' }}>Free</option>
                            <option value="{{ \App\Models\Member::TIER_PAID }}" {{ request('tier') === \App\Models\Member::TIER_PAID ? 'selected' : '' }}>Paid</option>
                        </select>
                        <select name="status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @if($enrollers->isNotEmpty())
                            <select name="enrolled_by" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                <option value="">All Enrollers</option>
                                @foreach($enrollers as $enroller)
                                    <option value="{{ $enroller->id }}" {{ (string) request('enrolled_by') === (string) $enroller->id ? 'selected' : '' }}>
                                        {{ $enroller->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
                               class="flex-1 min-w-[200px] border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.academy.members.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Password</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tier</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Enrolled By</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Last Login</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($members as $member)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <a href="{{ route('admin.academy.members.show', $member) }}" class="hover:underline">
                                                {{ $member->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $member->email }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($member->password)
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Set</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800">Pending Setup</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($member->is_active)
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($member->isPaid())
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800">Paid</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Free</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $member->enroller?->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $member->last_login_at ? $member->last_login_at->diffForHumans() : '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-right whitespace-nowrap">
                                            <a href="{{ route('admin.academy.members.show', $member) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                            <a href="{{ route('admin.academy.members.edit', $member) }}" class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                            <form method="POST" action="{{ route('admin.academy.members.destroy', $member) }}" class="inline" onsubmit="return confirm('Delete this member? Progress data will also be removed.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada member.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $members->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
