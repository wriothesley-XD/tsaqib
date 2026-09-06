<?php

namespace App\Http\Requests\Auth;

use App\Models\StudentVerification;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = trim((string) $this->input('email'));
        $password = (string) $this->input('password');
        $remember = $this->boolean('remember');

        $credentials = ['email' => $login, 'password' => $password];

        // Jika bukan format email (mis. angka NISN / NIS), cari akun terkait
        if (! filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $cleanDigits = preg_replace('/\D/', '', $login);
            $user = User::where('nisn', $cleanDigits)
                ->orWhere('nis', $cleanDigits)
                ->orWhere('nisn', 'NIS-'.$cleanDigits)
                ->first();

            // Bila tidak ditemukan langsung di users, cari di student_verifications approved
            if (! $user && $cleanDigits !== '') {
                $verif = StudentVerification::where('nisn', $cleanDigits)
                    ->where('status', 'approved')
                    ->first();
                if ($verif) {
                    $user = $verif->user;
                }
            }

            if ($user) {
                $credentials = ['email' => $user->email, 'password' => $password];
            }
        }

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
