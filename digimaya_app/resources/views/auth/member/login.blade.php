<x-academy-auth-layout>
    @section('title', 'Login Member')

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Login Member</h2>
            <p class="mt-2 text-sm text-gray-600">Akses materi Digimaya Academy.</p>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('member.login.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required autofocus
                           value="{{ old('email') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300">
                        <span class="ml-2">Remember me</span>
                    </label>
                    <a href="{{ route('member.password.request') }}" class="text-sm text-gray-700 hover:underline">
                        Lupa password?
                    </a>
                </div>

                <button type="submit"
                        class="w-full py-2 px-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-md transition">
                    Login
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-600 mt-6">
            Belum jadi member? Hubungi tim Digimaya untuk informasi.
        </p>
    </div>
</x-academy-auth-layout>
