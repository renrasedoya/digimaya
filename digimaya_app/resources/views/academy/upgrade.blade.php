<x-academy-layout>
    @section('title', 'Upgrade ke Paid')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upgrade ke Paid Member
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Akses semua module Academy dan benefit eksklusif untuk Paid member Digimaya.
        </p>
    </x-slot>

    @php
        $waUrl = config('digimaya.contact.whatsapp_wa_url');
        $waText = 'Saya mau upgrade member agar dapat akses ke semua module.';
        $waLink = $waUrl . '?text=' . rawurlencode($waText);
    @endphp

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Already Paid notice --}}
            @if($member->isPaid())
                <div class="mb-6 bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-amber-800">Kamu sudah Paid member.</p>
                        <p class="text-sm text-amber-700 mt-1">Semua module sudah bisa diakses dari dashboard.</p>
                    </div>
                </div>
            @endif

            {{-- Hero card --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900">Apa yang kamu dapat dengan Paid Member?</h3>
                    <p class="mt-2 text-sm text-gray-600">4 benefit eksklusif untuk mempercepat belajar Google Ads kamu.</p>

                    {{-- Benefit list --}}
                    <ul class="mt-6 space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Akses Semua Module (Free + Paid)</p>
                                <p class="text-sm text-gray-600 mt-1">Buka semua module Academy tanpa batas, termasuk materi advanced yang tidak tersedia di Free.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Materi Advanced & Strategi Internal Digimaya</p>
                                <p class="text-sm text-gray-600 mt-1">Belajar langsung dari playbook yang dipakai tim Digimaya untuk client agency real.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Komunitas Private</p>
                                <p class="text-sm text-gray-600 mt-1">Akses grup khusus Paid member untuk diskusi, sharing, dan support langsung dari tim Digimaya.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Sertifikat Completion</p>
                                <p class="text-sm text-gray-600 mt-1">Dapatkan sertifikat resmi setelah menyelesaikan module yang bisa dipakai untuk portfolio atau LinkedIn.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- CTA section --}}
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-4">
                        Tertarik upgrade? Chat langsung ke tim Digimaya via WhatsApp untuk info detail dan proses upgrade.
                    </p>
                    <div class="flex justify-center">
                    <a href="{{ $waLink }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md text-sm shadow-sm transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        @if($member->isPaid())
                            Hubungi via WhatsApp
                        @else
                            Upgrade Sekarang via WhatsApp
                        @endif
                    </a>
                    </div>
                    <p class="mt-3 text-xs text-gray-500 text-center">
                        Pesan akan otomatis terisi. Kamu masih bisa edit sebelum kirim.
                    </p>
                </div>
            </div>

            {{-- Back link --}}
            <div class="mt-6 text-center">
                <a href="{{ route('academy.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>
</x-academy-layout>
