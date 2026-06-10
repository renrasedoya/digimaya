<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Template Proposal') }}</h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Template Proposal']]" />
                </div>
            </div>
            <a href="{{ route('admin.proposal-templates.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Buat Template Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">{{ session('error') }}</div>
                    @endif

                    <p class="text-sm text-gray-500 mb-4">Template mengisi otomatis section saat "Buat Proposal". Edit di sini memakai builder yang sama persis seperti proposal.</p>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Template</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Key</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Block</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($templates as $template)
                                <tr>
                                    <td class="px-3 py-2 font-medium">{{ $template->name }}</td>
                                    <td class="px-3 py-2 text-gray-600 text-sm font-mono">{{ $template->key }}</td>
                                    <td class="px-3 py-2 text-gray-600 text-sm">{{ is_array($template->content_blocks) ? count($template->content_blocks) : 0 }} block</td>
                                    <td class="px-3 py-2 text-right whitespace-nowrap">
                                        <a href="{{ route('admin.proposal-templates.edit', $template) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                        @if($templates->count() > 1)
                                            <form method="POST" action="{{ route('admin.proposal-templates.destroy', $template) }}" class="inline" onsubmit="return confirm('Hapus template {{ $template->name }}? Proposal yang sudah dibuat tidak terpengaruh.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500 text-sm">Belum ada template.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
