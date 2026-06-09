<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $member->name }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Academy'],
                        ['label' => 'Members', 'url' => route('admin.academy.members.index')],
                        ['label' => $member->name]
                    ]" />
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.academy.members.edit', $member) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

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

            {{-- Setup Link Panel: hanya tampil kalau setup_token valid --}}
            @if($setupUrl)
                <div class="mb-6 bg-white border border-blue-200 rounded-lg p-6" x-data="{ copied: false }">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wider">Setup Link Active</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Member belum set password. Share link ini ke member kalau email tidak diterima.
                            </p>
                        </div>
                        <span class="text-xs text-gray-500 whitespace-nowrap ml-4">
                            Expire {{ $member->setup_token_expires_at->diffForHumans() }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <input type="text" readonly
                               value="{{ $setupUrl }}"
                               x-ref="setupLink"
                               @click="$refs.setupLink.select()"
                               class="flex-1 bg-gray-50 border border-gray-300 rounded-md px-3 py-2 text-xs text-gray-700">
                        <button type="button"
                                @click="navigator.clipboard.writeText($refs.setupLink.value); copied = true; setTimeout(() => copied = false, 2000)"
                                class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm rounded-md whitespace-nowrap">
                            <span x-show="!copied">Copy Link</span>
                            <span x-show="copied" x-cloak>Copied</span>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Member Info --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Informasi Member</h3>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm">
                                @if($member->is_active)
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Tier</dt>
                            <dd class="mt-1 text-sm">
                                @if($member->isPaid())
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800">Paid</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Free</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Password</dt>
                            <dd class="mt-1 text-sm">
                                @if($member->password)
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Set</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800">Pending Setup</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Enrolled By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->enroller?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Enrolled At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->created_at->format('d M Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Last Login</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $member->last_login_at ? $member->last_login_at->format('d M Y H:i') . ' (' . $member->last_login_at->diffForHumans() . ')' : 'Belum pernah login' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Member ID</dt>
                            <dd class="mt-1 text-sm text-gray-500">#{{ $member->id }}</dd>
                        </div>
                    </dl>

                    @if($member->notes)
                        <div class="mt-6 pt-4 border-t">
                            <dt class="text-xs uppercase text-gray-500">Internal Notes</dt>
                            <dd class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">{{ $member->notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions Panel --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Actions</h3>

                    <div class="space-y-3">
                        @if(!$member->password)
                            <div class="flex items-center justify-between py-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Resend Welcome Email</p>
                                    <p class="text-xs text-gray-500">Generate setup link baru + kirim email lagi.</p>
                                </div>
                                <form method="POST" action="{{ route('admin.academy.members.resend-setup', $member) }}" onsubmit="return confirm('Kirim ulang welcome email ke {{ $member->email }}?')">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm rounded-md">
                                        Resend Email
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="flex items-center justify-between py-2 {{ !$member->password ? 'border-t pt-3' : '' }}">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Regenerate Setup Token</p>
                                <p class="text-xs text-gray-500">Force reset password. Setup link baru di-generate + email reset dikirim.</p>
                            </div>
                            <form method="POST" action="{{ route('admin.academy.members.regenerate-token', $member) }}" onsubmit="return confirm('Regenerate setup token? Member harus set password ulang lewat link baru.')">
                                @csrf
                                <button type="submit" class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm rounded-md">
                                    Regenerate
                                </button>
                            </form>
                        </div>

                        <div class="flex items-center justify-between py-2 border-t pt-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $member->is_active ? 'Deactivate Member' : 'Activate Member' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    @if($member->is_active)
                                        Member tidak bisa login. Session aktif akan kick out di request berikutnya.
                                    @else
                                        Member bisa login lagi.
                                    @endif
                                </p>
                            </div>
                            <form method="POST" action="{{ route('admin.academy.members.toggle-active', $member) }}" onsubmit="return confirm('{{ $member->is_active ? 'Deactivate' : 'Activate' }} this member?')">
                                @csrf
                                <button type="submit" class="px-4 py-2 border {{ $member->is_active ? 'border-red-300 text-red-700 hover:bg-red-50' : 'border-green-300 text-green-700 hover:bg-green-50' }} text-sm rounded-md">
                                    {{ $member->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>

                        <div class="flex items-center justify-between py-2 border-t pt-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Delete Member</p>
                                <p class="text-xs text-gray-500">Soft delete. Data progress akan terhapus.</p>
                            </div>
                            <form method="POST" action="{{ route('admin.academy.members.destroy', $member) }}" onsubmit="return confirm('Delete this member? Progress data will also be removed.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 border border-red-300 text-red-700 hover:bg-red-50 text-sm rounded-md">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
