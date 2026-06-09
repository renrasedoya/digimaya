<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize input before validation runs.
     *
     * - contact_name, business_name: Title Case (mb-aware)
     * - contact_email: lowercase + trim
     * - contact_phone: normalize ke format 08xxx (handle +62, 62, 8, 08, dll)
     * - message: Sentence case (kapital huruf pertama tiap kalimat)
     * - website_url: auto-prepend https:// kalau user input tanpa scheme
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'contact_name'  => $this->normalizeName($this->input('contact_name')),
            'business_name' => $this->normalizeName($this->input('business_name')),
            'contact_email' => $this->normalizeEmail($this->input('contact_email')),
            'contact_phone' => $this->normalizePhone($this->input('contact_phone')),
            'message'       => $this->normalizeMessage($this->input('message')),
            'website_url'   => $this->normalizeWebsite($this->input('website_url')),
        ]);
    }

    /**
     * Title Case, multi-byte safe. Nullable input.
     * Contoh: "john doe" -> "John Doe", "PT MAJU JAYA" -> "Pt Maju Jaya"
     */
    private function normalizeName($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        // Collapse double spaces
        $value = preg_replace('/\s+/', ' ', $value);
        // mb-aware title case
        return mb_convert_case(mb_strtolower($value, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

    private function normalizeEmail($value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : mb_strtolower($value, 'UTF-8');
    }

    /**
     * Normalize Indonesian phone number ke format 08xxx.
     *
     * Handle:
     *   "+62 812 3456 7890" -> "081234567890"
     *   "62812-3456-7890"   -> "081234567890"
     *   "8123456789"        -> "08123456789"
     *   "08123456789"       -> "08123456789" (unchanged)
     *   "021-7301234"       -> "0217301234" (landline tetap, cuma strip non-digit)
     */
    private function normalizePhone($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        // Strip semua non-digit (spasi, tanda hubung, plus, kurung)
        $digits = preg_replace('/\D+/', '', $value);

        if ($digits === '') {
            return null;
        }

        // Kalau diawali "62" (international Indonesia), ganti jadi "0"
        if (str_starts_with($digits, '62')) {
            $digits = '0' . substr($digits, 2);
        }
        // Kalau diawali "8" (mobile tanpa 0), tambahin "0"
        elseif (str_starts_with($digits, '8')) {
            $digits = '0' . $digits;
        }

        return $digits;
    }

    /**
     * Sentence case: kapital huruf pertama tiap kalimat (setelah . ! ?).
     * Tetep preserve newlines & format user.
     */
    private function normalizeMessage($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        // Lowercase semua dulu (mb-aware)
        $lower = mb_strtolower($value, 'UTF-8');

        // Kapital huruf pertama tiap kalimat
        // Pattern: awal string ATAU setelah . ! ? diikuti spasi/newline
        $result = preg_replace_callback(
            '/(?:^|(?<=[.!?]\s)|(?<=[.!?]
))(\p{L})/u',
            fn($m) => mb_strtoupper($m[1], 'UTF-8'),
            $lower
        );

        return $result;
    }

    private function normalizeWebsite($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (! preg_match('~^https?://~i', $value)) {
            $value = 'https://' . ltrim($value, '/');
        }

        return $value;
    }

    /**
     * Defensive cleanup after validation passes.
     * Clear interested_in_other if interested_in is not 'others' (stale data prevention).
     */
    protected function passedValidation(): void
    {
        $interestedIn = $this->input('interested_in');

        if ($interestedIn !== 'others') {
            $this->merge(['interested_in_other' => null]);
            return;
        }

        // Treat whitespace-only as null
        $other = $this->input('interested_in_other');
        if (is_string($other) && trim($other) === '') {
            $this->merge(['interested_in_other' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'website_hp' => ['nullable', 'size:0'],
            'contact_name'  => ['required', 'string', 'max:120'],
            'contact_email' => ['required', 'email:rfc', 'max:160'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'business_name' => ['nullable', 'string', 'max:160'],
            'website_url'   => ['nullable', 'url', 'max:255'],
            'monthly_ad_budget' => ['nullable', Rule::in(array_keys(Lead::BUDGETS))],
            'interested_in'       => ['required', Rule::in(array_keys(Lead::INTERESTED_IN_OPTIONS))],
            'interested_in_other' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $this->input('interested_in') === 'others'),
            ],
            'message'           => ['nullable', 'string', 'max:2000'],
            'utm_source'   => ['nullable', 'string', 'max:120'],
            'utm_medium'   => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
            'referrer_url' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_name.required'  => 'Nama wajib diisi.',
            'contact_email.required' => 'Email wajib diisi.',
            'contact_email.email'    => 'Format email tidak valid.',
            'contact_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'website_url.url'        => 'Format URL website tidak valid (contoh: https://domain.com).',
            'monthly_ad_budget.in'   => 'Budget tidak valid.',
            'interested_in.required'       => 'Pilih salah satu layanan yang Anda minati.',
            'interested_in.in'             => 'Pilihan layanan tidak valid.',
            'interested_in_other.required' => 'Sebutkan layanan yang Anda minati.',
            'interested_in_other.max'      => 'Keterangan layanan maksimal 255 karakter.',
            'message.max'            => 'Pesan maksimal 2000 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'contact_name'      => 'nama',
            'contact_email'     => 'email',
            'contact_phone'     => 'WhatsApp',
            'business_name'     => 'nama bisnis',
            'website_url'       => 'website',
            'monthly_ad_budget' => 'budget',
            'interested_in'       => 'layanan yang diminati',
            'interested_in_other' => 'keterangan layanan',
            'message'           => 'pesan',
        ];
    }
}