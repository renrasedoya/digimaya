<?php

namespace App\Http\Requests\Admin;

use App\Models\Balance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBalanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->isSuperAdmin() || $user->isAdmin());
    }

    public function rules(): array
    {
        $currentYear = (int) now()->year;

        return [
            'bank_account_id' => [
                'required',
                'integer',
                Rule::exists('bank_accounts', 'id')->where('is_active', true),
            ],
            'year' => [
                'required',
                'integer',
                'between:' . ($currentYear - 5) . ',' . $currentYear,
            ],
            'month' => ['required', 'integer', 'between:1,12'],
            'balance_amount' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if ($v->errors()->isNotEmpty()) {
                return;
            }
            $exists = Balance::query()
                ->where('bank_account_id', $this->input('bank_account_id'))
                ->where('year', $this->input('year'))
                ->where('month', $this->input('month'))
                ->exists();
            if ($exists) {
                $v->errors()->add(
                    'bank_account_id',
                    'Sudah ada laporan balance untuk rekening + periode ini. Edit yang sudah ada.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'bank_account_id.required' => 'Pilih rekening dulu.',
            'bank_account_id.exists' => 'Rekening tidak valid atau sudah tidak aktif.',
            'year.required' => 'Tahun wajib diisi.',
            'month.required' => 'Bulan wajib diisi.',
            'balance_amount.required' => 'Saldo wajib diisi.',
            'balance_amount.numeric' => 'Saldo harus berupa angka.',
            'balance_amount.min' => 'Saldo tidak boleh negatif.',
        ];
    }
}
