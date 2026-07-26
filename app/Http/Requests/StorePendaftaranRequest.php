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
            'komunitas'       => ['required', 'string', 'max:100'],
            'role'            => ['required', 'string', 'max:100'],
            'nama_lengkap'    => ['required', 'string', 'max:150'],
            'nama_panggilan'  => ['required', 'string', 'max:50'],
            'instagram'       => ['required', 'string', 'max:100'],
            'alasan'          => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'komunitas.required'      => 'Komunitas wajib dipilih.',
            'role.required'           => 'Role wajib dipilih.',
            'nama_lengkap.required'   => 'Nama lengkap wajib diisi.',
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',
            'instagram.required'      => 'Instagram wajib diisi.',
            'alasan.required'        => 'Alasan wajib diisi.',
        ];
    }
}
