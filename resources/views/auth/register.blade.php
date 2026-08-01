<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900">Daftar Akun TSAQIB Baru</h2>
        <p class="text-xs text-slate-500 mt-1">Buat akun untuk bergabung dengan ekosistem FSI SMAN 1 Bukittinggi</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full text-xs" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full text-xs" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full text-xs"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full text-xs"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-6 gap-3">
            <div class="text-xs text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-[#01795F] hover:underline">
                    Login disini &rarr;
                </a>
            </div>

            <x-primary-button class="bg-[#01795F] hover:bg-[#3F704D]">
                {{ __('Daftar Akun') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
