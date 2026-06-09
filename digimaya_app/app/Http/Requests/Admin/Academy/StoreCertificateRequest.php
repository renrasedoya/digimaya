<?php

namespace App\Http\Requests\Admin\Academy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->isSuperAdmin() || $user->isAdmin());
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['academy', 'external'])],

            // Academy mode fields
            'member_id' => ['nullable', 'integer', 'exists:members,id'],

            // External mode fields
            'custom_recipient_name' => ['nullable', 'string', 'max:255'],

            // Common fields
            'program_name' => ['required', 'string', 'max:255'],
            'program_description' => ['nullable', 'string', 'max:2000'],
            'completion_date' => ['required', 'date', 'before_or_equal:today'],
            'issued_date' => ['required', 'date', 'before_or_equal:today'],

            // Optional link to request (passed via from_request query param when approving)
            'from_request' => ['nullable', 'integer', 'exists:certificate_requests,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($v) {
            $type = $this->input('type');

            if ($type === 'academy') {
                if (!$this->filled('member_id')) {
                    $v->errors()->add('member_id', 'Member is required for Academy certificate.');
                    return;
                }

                // Guard: tidak boleh ada active academy cert untuk member ini
                $exists = \App\Models\Certificate::where('member_id', $this->input('member_id'))
                    ->where('type', 'academy')
                    ->where('status', 'active')
                    ->exists();

                if ($exists) {
                    $v->errors()->add('member_id', 'This member already has an active Academy certificate. Revoke it first if you need to issue a new one.');
                }
            } elseif ($type === 'external') {
                if (!$this->filled('custom_recipient_name')) {
                    $v->errors()->add('custom_recipient_name', 'Recipient name is required for External certificate.');
                }
            }

            // Completion date sanity
            if ($this->filled('completion_date') && $this->filled('issued_date')) {
                if (strtotime($this->input('completion_date')) > strtotime($this->input('issued_date'))) {
                    $v->errors()->add('completion_date', 'Completion date cannot be after issued date.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'program_name.required' => 'Program name is required.',
            'completion_date.required' => 'Completion date is required.',
            'completion_date.before_or_equal' => 'Completion date cannot be in the future.',
            'issued_date.required' => 'Issued date is required.',
            'issued_date.before_or_equal' => 'Issued date cannot be in the future.',
        ];
    }
}
