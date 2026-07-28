<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Endpoint publik, tidak perlu auth/login
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:100'],
            'class' => ['required', 'string', 'max:100'],
            'ig_user' => ['required', 'string', 'max:100'],
            'reason' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nickname.required' => 'Nama panggilan wajib diisi.',
            'class.required' => 'Kelas wajib diisi.',
            'ig_user.required' => 'Username Instagram wajib diisi.',
            'reason.required' => 'Alasan wajib diisi.',
        ];
    }
}
