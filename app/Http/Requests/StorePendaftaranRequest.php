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
        // Harus login untuk mendaftar (route ini sudah di dalam middleware('auth')).
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nickname' => ['required', 'string', 'max:100'],
            'class' => ['required', 'string', 'max:100'],
            'username_ig' => ['required', 'string', 'max:100'],
            'reason' => ['required', 'string', 'max:2000'],
        ];
    }
}
