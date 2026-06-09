@extends('layouts.public')

@section('meta_title', 'Terima Kasih — Digimaya')
@section('meta_description', 'Konsultasi kamu sudah terkirim. Tim Digimaya akan menghubungi dalam 1×24 jam kerja.')

@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 text-center">

    {{-- Check icon --}}
    <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-50 rounded-full mb-6">
        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>

    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
        Terima Kasih!
    </h1>

    <p class="body-text max-w-md mx-auto mb-8">
        Konsultasi kamu sudah masuk ke tim kami. Kami akan menghubungi via WhatsApp atau email dalam <strong>1×24 jam kerja</strong>.
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('home') }}"
           class="btn-primary btn-sm">
            Kembali ke Home
        </a>
        <a href="{{ route('public.blog.index') }}"
           class="btn-outline btn-sm">
            Baca Blog
        </a>
    </div>
</section>

{{-- GTM/GA conversion tracking marker (optional, for later) --}}
@push('scripts')
<script>
    // Hook untuk GTM dataLayer push (saat GTM dipasang nanti)
    if (typeof window.dataLayer !== 'undefined') {
        window.dataLayer.push({
            event: 'lead_form_submitted',
            form_name: 'public_contact',
        });
    }
</script>
@endpush

@endsection