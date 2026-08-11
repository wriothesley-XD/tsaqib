<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'bio' => ['nullable', 'string', 'max:160'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // Avatar BAWAAN (preset) dari <x-avatar-picker>. Harus salah satu
            // path di assets/images/avatar/* (User::presetAvatars()) — cegah
            // pemalsuan path acak. Bukan kolom mass-assignable; ditangani manual
            // di ProfileController@update agar tak tertukar dengan upload foto.
            'preset_avatar' => ['nullable', 'string', Rule::in(User::presetAvatars())],
            // Komunitas dari <x-community-picker>. Harus salah satu slug di
            // config('komunitas.daftar') — SAMA daftar yang dipakai
            // /komunitas/{slug} & select-role. Bukan mass-assignable via fill()
            // karena kolomnya `selected_community`, dipetakan manual di
            // ProfileController@update.
            'community_slug' => ['nullable', 'string', Rule::in(array_column(config('komunitas.daftar', []), 'slug'))],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
