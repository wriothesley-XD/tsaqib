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
            'komunitas'      => ['required', 'string'],
            'role'           => ['required', 'string'],
            'nama_lengkap'   => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['required', 'string', 'max:100'],
            'instagram'      => ['required', 'string', 'max:100'],
            'alasan'         => ['required', 'string', 'max:2000'],
        ];
    }
}
