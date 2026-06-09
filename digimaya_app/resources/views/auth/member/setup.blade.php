<x-academy-auth-layout>
    @section('title', 'Set Password')

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Set Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Halo {{ $member->name }}, silakan buat password untuk akun kamu.
            </p>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-8">
            <form method="POST" action="{{ route('member.setup.store', $token) }}">
                @csrf

                <div class="mb-4">
                    <label for="email_display" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email_display" type="email" disabled
                           value="{{ $member->email }}"
                           class="mt-1 block w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-600">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required autofocus
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 karakter.</p>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                </div>

                <button type="submit"
                        class="w-full py-2 px-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-md transition">
                    Set Password & Login
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-500 mt-6">
            Link setup ini berlaku 24 jam dari saat dibuat.
        </p>
    </div>
</x-academy-auth-layout>
