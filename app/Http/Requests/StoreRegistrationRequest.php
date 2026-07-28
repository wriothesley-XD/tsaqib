<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    /**
     * User yang sudah login (middleware 'auth') boleh mengisi form ini.
     * Pengecekan "sudah pernah daftar atau belum" ditangani di controller,
     * bukan di sini, supaya request ini fokus hanya pada validasi input.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk setiap field form pendaftaran.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:100'],
            'nickname' => ['required', 'string', 'max:50'],
            'class' => ['required', 'string', 'max:20'],
            'instagram_username' => [
                'required',
                'string',
                'max:50',
                // Username Instagram: huruf, angka, titik, underscore saja, tanpa '@'.
                'regex:/^[a-zA-Z0-9._]+$/',
            ],
            'reason' => ['required', 'string', 'min:20', 'max:1000'],
        ];
    }

    /**
     * Pesan error kustom berbahasa Indonesia, biar lebih ramah untuk user.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama Lengkap wajib diisi.',
            'nickname.required' => 'Nama Panggilan wajib diisi.',
            'class.required' => 'Kelas wajib diisi.',
            'instagram_username.required' => 'Username Instagram wajib diisi.',
            'instagram_username.regex' => 'Username Instagram tidak boleh mengandung "@" atau simbol lain.',
            'reason.required' => 'Alasan Bergabung wajib diisi.',
            'reason.min' => 'Alasan Bergabung minimal :min karakter, ceritakan lebih detail ya.',
        ];
    }
}
