<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Certificate {{ $certificate->certificate_number }}
            </h2>
            <div class="mt-2">
                <x-breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['label' => 'Academy'],
                    ['label' => 'Certificates', 'url' => route('admin.academy.certificates.index')],
                    ['label' => $certificate->certificate_number, 'url' => route('admin.academy.certificates.show', $certificate)],
                    ['label' => 'Edit']
                ]" />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
                <strong>Edit policy</strong>: Once issued, only Program Description is editable. To change recipient name, program name, or dates, revoke this certificate and issue a new one.
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.academy.certificates.update', $certificate) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Locked fields (display only) --}}
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Recipient Name</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-700">{{ $certificate->recipient_name }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Type</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-700">{{ ucfirst($certificate->type) }}</div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Program Name</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-700">{{ $certificate->program_name }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Completion Date</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-700">{{ $certificate->completion_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Issued Date</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-gray-700">{{ $certificate->issued_date->format('d M Y') }}</div>
                        </div>
                    </div>

                    {{-- Editable field --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Program Description <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea name="program_description" rows="3" maxlength="2000"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">{{ old('program_description', $certificate->program_description) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Sub-text printed below program name on certificate PDF.</p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.academy.certificates.show', $certificate) }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Update Description
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
