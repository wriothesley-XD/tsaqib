<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-white">Daftar Akun TSAQIB Baru</h2>
        <p class="text-xs text-[#8A9B7A] mt-1">Buat akun untuk bergabung dengan ekosistem FSI SMAN 1 Bukittinggi</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <div class="relative mt-1">
                <i class="fa-solid fa-user pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[#8A9B7A]" aria-hidden="true"></i>
                <x-text-input id="name" class="block w-full text-xs pl-10" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-1">
                <i class="fa-solid fa-envelope pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[#8A9B7A]" aria-hidden="true"></i>
                <x-text-input id="email" class="block w-full text-xs pl-10" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password + Konfirmasi: stacked on mobile, side-by-side on ≥sm --}}
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative mt-1">
                    <i class="fa-solid fa-lock pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[#8A9B7A]" aria-hidden="true"></i>
                    <x-text-input id="password" class="block w-full text-xs pl-10 pw-field" type="password" name="password" required autocomplete="new-password" />
                    <button type="button" class="pw-toggle absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center h-7 w-7 rounded-md text-xs text-[#8A9B7A] hover:text-[#F7F5EF] hover:bg-white/5 transition-colors" data-toggle="password" aria-label="Tampilkan password" aria-pressed="false">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                {{-- Strength meter: 3 bars + label below --}}
                <div class="mt-2" id="pw-strength">
                    <div class="flex gap-1">
                        <span class="pw-seg h-1 flex-1 rounded-full" style="background-color:rgba(247,245,239,.10)"></span>
                        <span class="pw-seg h-1 flex-1 rounded-full" style="background-color:rgba(247,245,239,.10)"></span>
                        <span class="pw-seg h-1 flex-1 rounded-full" style="background-color:rgba(247,245,239,.10)"></span>
                    </div>
                    <span class="block mt-1 text-[10px] font-semibold" id="pw-strength-label" style="color:#8A9B7A"> </span>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                <div class="relative mt-1">
                    <i class="fa-solid fa-shield-halved pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[#8A9B7A]" aria-hidden="true"></i>
                    <x-text-input id="password_confirmation" class="block w-full text-xs pl-10 pw-field" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <button type="button" class="pw-toggle absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center h-7 w-7 rounded-md text-xs text-[#8A9B7A] hover:text-[#F7F5EF] hover:bg-white/5 transition-colors" data-toggle="password_confirmation" aria-label="Tampilkan password" aria-pressed="false">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <p id="pw-match" class="mt-2 text-[10px] font-semibold" style="color:#8A9B7A">&nbsp;</p>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-6 gap-3">
            <div class="text-xs text-[#8A9B7A]">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-[#D9A441] hover:underline">
                    Login disini &rarr;
                </a>
            </div>

            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Daftar Akun') }}
            </x-primary-button>
        </div>
    </form>

    {{-- Password strength + confirm-match (vanilla JS, scoped to this page) --}}
    <script>
    (function () {
        const pw = document.getElementById('password');
        if (!pw) return;
        const meter = document.getElementById('pw-strength');
        const label = document.getElementById('pw-strength-label');
        const segs  = meter ? meter.querySelectorAll('.pw-seg') : [];
        const confirm = document.getElementById('password_confirmation');
        const matchEl  = document.getElementById('pw-match');

        const colors = ['#ef4444', '#D9A441', '#1D9E75']; // Lemah / Sedang / Kuat
        const labels = ['Lemah', 'Sedang', 'Kuat'];
        const dim = 'rgba(247,245,239,.10)';

        function score(v) {
            if (!v) return -1;
            let s = 0;
            if (v.length >= 8) s++;
            if (v.length >= 12) s++;
            if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
            if (/\d/.test(v)) s++;
            if (/[^A-Za-z0-9]/.test(v)) s++;
            if (v.length < 8) return 0;     // terlalu pendek → Lemah
            if (s <= 2) return 0;           // Lemah
            if (s === 3) return 1;          // Sedang
            return 2;                       // Kuat
        }
        function render() {
            const v = pw.value;
            const t = score(v);
            segs.forEach(function (seg, i) {
                seg.style.backgroundColor = (t >= 0 && i <= t) ? colors[t] : dim;
            });
            if (label) {
                label.textContent = t >= 0 ? labels[t] : ' ';
                label.style.color = t >= 0 ? colors[t] : '#8A9B7A';
            }
            if (matchEl && confirm) {
                const c = confirm.value;
                if (!c) { matchEl.textContent = ' '; matchEl.style.color = '#8A9B7A'; }
                else if (c === v) { matchEl.textContent = '✓ Cocok'; matchEl.style.color = '#1D9E75'; }
                else { matchEl.textContent = '✗ Tidak cocok'; matchEl.style.color = '#ef4444'; }
            }
        }
        pw.addEventListener('input', render);
        if (confirm) confirm.addEventListener('input', render);
        render();
    })();
    </script>
</x-guest-layout>
