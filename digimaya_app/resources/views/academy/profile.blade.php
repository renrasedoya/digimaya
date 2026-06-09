<x-academy-layout>
    @section('title', 'Profile')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Read-only Info --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900">Informasi Akun</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Nama dan email tidak bisa diubah sendiri</p>
                    </div>
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
                            <dt class="text-xs uppercase text-gray-500">Bergabung</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->created_at->format('d M Y') }}</dd>
                        </div>
                        @if($member->last_login_at)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Last Login</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $member->last_login_at->format('d M Y H:i') }}
                                    <span class="text-gray-500">({{ $member->last_login_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                        @endif
                    </dl>
                    <p class="text-xs text-gray-500 mt-4">
                        Hubungi admin Digimaya untuk ubah nama atau email.
                    </p>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900">Ganti Password</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Update password kapan saja untuk keamanan akun</p>
                    </div>

                    <form method="POST" action="{{ route('academy.profile.password.update') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                            <input id="current_password" name="current_password" type="password" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input id="password" name="password" type="password" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 karakter, berbeda dari password saat ini.</p>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                        </div>

                        <button type="submit"
                                class="px-4 py-2 bg-brand hover:bg-brand-700 text-white text-sm font-semibold rounded-md transition">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-academy-layout>
