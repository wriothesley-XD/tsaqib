<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePendaftaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Open Recruitment sekarang publik (guest boleh daftar tanpa akun) —
        // sesuai keputusan rapat granular access. Dulu wajib login, sekarang tidak.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:100'],
            'class' => ['required', 'string', 'max:100'],
            'username_ig' => ['required', 'string', 'max:100'],
            'reason' => ['required', 'string', 'max:2000'],
        ];
    }
}
