<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NisnWhitelist;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Avatar bawaan (preset) dari <x-avatar-picker>. Harus salah satu
            // path valid; bila kosong / JS mati, di-acak server-side di bawah.
            'avatar' => ['nullable', 'string', Rule::in(User::presetAvatars())],
            'nisn' => ['nullable', 'string', 'max:25'],
        ]);

        // Avatar: pakai pilihan user, atau acak bila tidak memilih. Selalu
        // terisi bila preset tersedia, sehingga setiap user punya avatar.
        $avatar = $validated['avatar'] ?? null;
        if (! $avatar) {
            $presets = User::presetAvatars();
            $avatar = $presets ? $presets[array_rand($presets)] : null;
        }

        // Auto-verifikasi jika NISN / NIS siswa cocok dengan whitelist sekolah
        $isVerifiedStudent = false;
        $userNisn = null;
        $userNis = null;
        if (! empty($validated['nisn'])) {
            $cleanNisn = preg_replace('/\D/', '', $validated['nisn']);
            if ($cleanNisn !== '') {
                $whitelist = NisnWhitelist::where('nisn', $cleanNisn)
                    ->orWhere('nis', $cleanNisn)
                    ->orWhere('nisn', 'NIS-'.$cleanNisn)
                    ->first();
                if ($whitelist) {
                    $isVerifiedStudent = true;
                    $userNisn = $whitelist->nisn;
                    $userNis = $whitelist->nis;
                } else {
                    if (strlen($cleanNisn) >= 8) {
                        $userNisn = $cleanNisn;
                    } else {
                        $userNis = $cleanNisn;
                    }
                }
            }
        }

        // `avatar` sengaja di-assign eksplisit (bukan mass-assign via fillable)
        // agar tak bentrok dengan field upload `avatar` di ProfileUpdateRequest.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nisn' => $userNisn,
            'nis' => $userNis,
            'password' => Hash::make($validated['password']),
            'is_verified_student' => $isVerifiedStudent,
        ]);
        $user->avatar = $avatar;
        $user->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('beranda');
    }
}
