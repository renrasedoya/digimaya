@props([
    'name',
    'id' => null,
    'value' => null,
    'required' => false,
    'min' => 0,
    'placeholder' => '',
    'prefix' => 'IDR',
])

@php
    $inputId = $id ?? $name;
    $rawValue = old($name, $value);
    $formattedValue = $rawValue !== null && $rawValue !== '' ? number_format((float) $rawValue, 0, '.', ',') : '';
@endphp

<div x-data="{
    raw: '{{ $rawValue ? (int) $rawValue : '' }}',
    formatted: '{{ $formattedValue }}',
    parseInput(val) {
        if (val === '' || val === null) return '';
        const str = String(val).trim();
        // Detect format and extract integer part only
        // Cases:
        //   '1,500,000.50' (English with decimal) -> '1500000'
        //   '1.500.000,50' (Indonesian with decimal) -> '1500000'
        //   '1,500,000' (English plain) -> '1500000'
        //   '1.500.000' (Indonesian plain) -> '1500000'
        //   '1500000' (raw digits) -> '1500000'
        let cleaned = str;
        // Strategy: detect decimal separator by checking last separator position
        const lastDot = str.lastIndexOf('.');
        const lastComma = str.lastIndexOf(',');
        const decimalIdx = Math.max(lastDot, lastComma);
        // If has decimal separator AND it's at position N-3 (e.g. '.50' or ',50') -> treat as decimal cents
        // We drop decimal cents entirely (IDR typical)
        if (decimalIdx > -1 && (str.length - decimalIdx) <= 3) {
            // Has decimal cents - keep only integer part
            const sepChar = decimalIdx === lastDot ? '.' : ',';
            // Check if there are OTHER separators of different type before this -> it IS decimal
            const otherChar = sepChar === '.' ? ',' : '.';
            const hasOther = str.indexOf(otherChar) !== -1;
            // Also: decimal cents are 1-2 digits typically
            const cents = str.substring(decimalIdx + 1);
            const isDecimal = /^[0-9]{1,2}$/.test(cents) && (hasOther || decimalIdx > 0);
            if (isDecimal) {
                cleaned = str.substring(0, decimalIdx);
            }
        }
        // Strip all non-digit chars
        const num = cleaned.replace(/[^0-9]/g, '');
        return num;
    },
    formatNumber(val) {
        if (val === '' || val === null) return '';
        if (val === '0') return '0';
        return parseInt(val, 10).toLocaleString('en-US');
    },
    onInput(event) {
        const cleaned = this.parseInput(event.target.value);
        this.raw = cleaned;
        this.formatted = this.formatNumber(cleaned);
        event.target.value = this.formatted;
    },
    onBlur(event) {
        if (this.raw !== '') {
            event.target.value = this.formatNumber(this.raw);
        }
    },
    onPaste(event) {
        event.preventDefault();
        const pasted = (event.clipboardData || window.clipboardData).getData('text');
        const cleaned = this.parseInput(pasted);
        this.raw = cleaned;
        this.formatted = this.formatNumber(cleaned);
        event.target.value = this.formatted;
    }
}" class="relative">
    <div class="flex">
        <span class="inline-flex items-center px-3 mt-1 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
            {{ $prefix }}
        </span>
        <input type="text"
               x-bind:value="formatted"
               x-on:input="onInput($event)"
               x-on:blur="onBlur($event)"
               x-on:paste="onPaste($event)"
               inputmode="numeric"
               id="{{ $inputId }}_display"
               placeholder="{{ $placeholder }}"
               @if($required) required @endif
               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-r-md shadow-sm px-3 py-2 mt-1 block w-full">
        <input type="hidden" name="{{ $name }}" id="{{ $inputId }}" x-bind:value="raw" min="{{ $min }}">
    </div>
</div>
