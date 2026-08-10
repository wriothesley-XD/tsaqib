<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-white">Masuk Akun TSAQIB</h2>
        <p class="text-xs text-[#8A9B7A] mt-1">Silakan masuk menggunakan akun Forum Studi Islam Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-1">
                <i class="fa-solid fa-envelope pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[#8A9B7A]" aria-hidden="true"></i>
                <x-text-input id="email" class="block w-full text-xs pl-10" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative mt-1">
                <i class="fa-solid fa-lock pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[#8A9B7A]" aria-hidden="true"></i>
                <x-text-input id="password" class="block w-full text-xs pl-10 pw-field" type="password" name="password" required autocomplete="current-password" />
                <button type="button" class="pw-toggle absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center h-7 w-7 rounded-md text-xs text-[#8A9B7A] hover:text-[#F7F5EF] hover:bg-white/5 transition-colors" data-toggle="password" aria-label="Tampilkan password" aria-pressed="false">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#01795F] shadow-sm focus:ring-[#01795F]" name="remember">
                <span class="ms-2 text-xs text-[#8A9B7A]">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-6 gap-3">
            <div class="text-xs text-[#8A9B7A]">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-[#D9A441] hover:underline">
                    Daftar disini &rarr;
                </a>
            </div>

            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
