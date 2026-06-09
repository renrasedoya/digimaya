<x-academy-auth-layout>
    @section('title', 'Lupa Password')

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Lupa Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Masukkan email kamu, link reset akan dikirim ke inbox.
            </p>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('member.password.email') }}">
                @csrf

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required autofocus
                           value="{{ old('email') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full py-2 px-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-md transition">
                    Kirim Link Reset
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                <a href="{{ route('member.login') }}" class="hover:underline">&larr; Kembali ke login</a>
            </p>
        </div>
    </div>
</x-academy-auth-layout>
