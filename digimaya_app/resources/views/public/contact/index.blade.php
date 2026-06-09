@extends('layouts.public')

@section('meta_title', 'Konsultasi Gratis Google Ads | Digimaya')
@section('meta_description', 'Konsultasi gratis 30 menit dengan strategist Digimaya. Audit campaign Google Ads kamu, dapatkan rekomendasi konkret, tanpa komitmen.')

@section('content')

<section class="relative overflow-hidden bg-gradient-to-b from-brand-50/40 to-white">

    {{-- Decorative gradient mesh blobs --}}
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[350px] h-[350px] bg-brand-50/50 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

            {{-- ============== LEFT: Hero Content (50%) ============== --}}
            <div>

                {{-- Headline with gradient accent --}}
                <h1 class="heading-hero mb-6">
                    Ngobrol soal Google Ads,
                    <span class="bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                        gratis.
                    </span>
                </h1>

                {{-- Subhead --}}
                <p class="body-lead mb-10 max-w-xl">
                    Tim Digimaya siap bantu Anda mengevaluasi campaign, menemukan apa yang menghambat performa, dan memberikan rekomendasi yang bisa langsung diterapkan.
                </p>

                {{-- Benefits --}}
                <div class="space-y-6 mb-10">

                    {{-- Bullet 1 --}}
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 pt-1">
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Konsultasi Langsung</h3>
                            <p class="body-card">Diskusi langsung dengan Google Ads specialist Digimaya selama ±30 menit. Gratis dan tanpa kewajiban menggunakan layanan kami.</p>
                        </div>
                    </div>

                    {{-- Bullet 2 --}}
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div class="flex-1 pt-1">
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Audit Campaign</h3>
                            <p class="body-card">Kami bantu review campaign, keyword, ad copy, landing page, hingga tracking untuk menemukan peluang optimasi terbaik.</p>
                        </div>
                    </div>

                    {{-- Bullet 3 --}}
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 pt-1">
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Insight yang Actionable</h3>
                            <p class="body-card">Dapatkan rekomendasi yang realistis, relevan, dan bisa langsung diterapkan sesuai kondisi bisnis Anda.</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ============== RIGHT: Form Card (50%) ============== --}}
            <div>
                <div class="bg-gray-50/80 border border-gray-100 rounded-3xl p-6 sm:p-8 lg:p-10 backdrop-blur-sm">

                    <h2 class="text-xl font-bold text-gray-900 mb-2">
                        Mulai Konsultasi Gratis
                    </h2>
                    <p class="text-sm text-gray-500 mb-6">
                        Isi form berikut dan tim kami akan menghubungi Anda melalui WhatsApp maksimal dalam 1×24 jam kerja.
                    </p>

                    <form method="POST"
                          action="{{ route('public.contact.store') }}"
                          x-data="contactForm()"
                          @submit="onSubmit($event)"
                          class="space-y-5">
                        @csrf

                        {{-- Hidden UTM fields (auto-filled by JS) --}}
                        <input type="hidden" name="utm_source"   x-model="utm.source">
                        <input type="hidden" name="utm_medium"   x-model="utm.medium">
                        <input type="hidden" name="utm_campaign" x-model="utm.campaign">
                        <input type="hidden" name="referrer_url" x-model="utm.referrer">

                        {{-- Honeypot (hidden via inline CSS) --}}
                        <div style="position: absolute; left: -9999px; top: -9999px; width: 1px; height: 1px; overflow: hidden;" aria-hidden="true">
                            <label>
                                Website (leave empty)
                                <input type="text" name="website_hp" tabindex="-1" autocomplete="off">
                            </label>
                        </div>

                        {{-- Nama --}}
                        <div>
                            <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="contact_name"
                                   name="contact_name"
                                   value="{{ old('contact_name') }}"
                                   required
                                   maxlength="120"
                                   placeholder="Nama Anda"
                                   class="w-full px-4 py-3 text-sm bg-gray-50 border @error('contact_name') border-red-400 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                            @error('contact_name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Email + WhatsApp (2 cols on desktop) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                       id="contact_email"
                                       name="contact_email"
                                       value="{{ old('contact_email') }}"
                                       required
                                       maxlength="160"
                                       placeholder="email@bisnis.com"
                                       class="w-full px-4 py-3 text-sm bg-gray-50 border @error('contact_email') border-red-400 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                                @error('contact_email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="tel"
                                       id="contact_phone"
                                       name="contact_phone"
                                       value="{{ old('contact_phone') }}"
                                       required
                                       maxlength="30"
                                       placeholder="08xxxxxxxxxx"
                                       class="w-full px-4 py-3 text-sm bg-gray-50 border @error('contact_phone') border-red-400 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                                @error('contact_phone')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Bisnis + Website (2 cols on desktop) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Bisnis
                                </label>
                                <input type="text"
                                       id="business_name"
                                       name="business_name"
                                       value="{{ old('business_name') }}"
                                       maxlength="160"
                                       placeholder="PT / Brand Anda"
                                       class="w-full px-4 py-3 text-sm bg-gray-50 border @error('business_name') border-red-400 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                                @error('business_name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="website_url" class="block text-sm font-medium text-gray-700 mb-2">
                                    Website
                                </label>
                                <input type="text" inputmode="url"
                                       id="website_url"
                                       name="website_url"
                                       value="{{ old('website_url') }}"
                                       maxlength="255"
                                       placeholder="domain.com"
                                       class="w-full px-4 py-3 text-sm bg-gray-50 border @error('website_url') border-red-400 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                                @error('website_url')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Budget --}}
                        <div>
                            <label for="monthly_ad_budget" class="block text-sm font-medium text-gray-700 mb-2">
                                Estimasi Budget Iklan / Bulan
                            </label>
                            <select id="monthly_ad_budget"
                                    name="monthly_ad_budget"
                                    class="w-full px-4 py-3 text-sm bg-gray-50 border @error('monthly_ad_budget') border-red-400 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                                <option value="">Pilih budget (opsional)</option>
                                @foreach ($budgets as $key => $label)
                                    <option value="{{ $key }}" {{ old('monthly_ad_budget') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('monthly_ad_budget')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Tertarik dengan layanan --}}
                        <div x-data="{
                                interest: '{{ old('interested_in', '') }}',
                                otherText: '{{ old('interested_in_other', '') }}',
                                init() {
                                    this.$watch('interest', (val) => {
                                        if (val !== 'others') this.otherText = '';
                                    });
                                }
                             }">
                            <div>
                                <label for="interested_in" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tertarik dengan layanan apa? <span class="text-red-500">*</span>
                                </label>
                                <select id="interested_in"
                                        name="interested_in"
                                        x-model="interest"
                                        required
                                        class="w-full px-4 py-3 text-sm bg-gray-50 border @error('interested_in') border-red-400 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                                    <option value="">Pilih layanan</option>
                                    <option value="agency" {{ old('interested_in') === 'agency' ? 'selected' : '' }}>Agency — Kelola Google Ads untuk bisnis Anda</option>
                                    <option value="academy" {{ old('interested_in') === 'academy' ? 'selected' : '' }}>Academy — Belajar Google Ads</option>
                                    <option value="partnership" {{ old('interested_in') === 'partnership' ? 'selected' : '' }}>Partnership / Kerja sama</option>
                                    <option value="others" {{ old('interested_in') === 'others' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('interested_in')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div x-show="interest === 'others'" x-cloak x-transition class="mt-3">
                                <label for="interested_in_other" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sebutkan
                                </label>
                                <input type="text"
                                       id="interested_in_other"
                                       name="interested_in_other"
                                       x-model="otherText"
                                       maxlength="255"
                                       placeholder="Contoh: konsultasi singkat, audit account, dll"
                                       class="w-full px-4 py-3 text-sm bg-gray-50 border @error('interested_in_other') border-red-400 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                                @error('interested_in_other')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Pesan --}}
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Pesan
                            </label>
                            <textarea id="message"
                                      name="message"
                                      rows="3"
                                      maxlength="2000"
                                      placeholder="Ceritakan singkat goal & tantangan campaign Anda..."
                                      class="w-full px-4 py-3 text-sm bg-gray-50 border @error('message') border-red-400 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition resize-none">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                :disabled="submitting"
                                :class="submitting && 'opacity-60 cursor-not-allowed'"
                                class="btn-primary w-full">
                            <span x-show="!submitting" class="inline-flex items-center gap-2">
                                Kirim Konsultasi
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </span>
                            <span x-show="submitting" x-cloak>Mengirim...</span>
                        </button>

                        <p class="text-xs text-gray-500 text-center leading-relaxed">
                            Gratis, tanpa komitmen. Dengan mengirim form ini, Anda menyetujui
                            <a href="{{ route('privacy') }}" class="text-brand hover:underline">Privacy Policy</a> kami.
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@push('scripts')
<script>
function contactForm() {
    return {
        submitting: false,
        utm: { source: '', medium: '', campaign: '', referrer: '' },

        init() { this.captureUtm(); },

        captureUtm() {
            const params = new URLSearchParams(window.location.search);
            const STORAGE_KEY = 'digimaya_utm';

            const urlUtm = {
                source:   params.get('utm_source')   || '',
                medium:   params.get('utm_medium')   || '',
                campaign: params.get('utm_campaign') || '',
            };

            if (urlUtm.source || urlUtm.medium || urlUtm.campaign) {
                try { localStorage.setItem(STORAGE_KEY, JSON.stringify(urlUtm)); } catch (e) {}
                this.utm.source   = urlUtm.source;
                this.utm.medium   = urlUtm.medium;
                this.utm.campaign = urlUtm.campaign;
            } else {
                try {
                    const stored = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
                    this.utm.source   = stored.source   || '';
                    this.utm.medium   = stored.medium   || '';
                    this.utm.campaign = stored.campaign || '';
                } catch (e) {}
            }

            this.utm.referrer = document.referrer || '';
        },

        onSubmit(e) { this.submitting = true; },
    };
}
</script>
@endpush

@endsection