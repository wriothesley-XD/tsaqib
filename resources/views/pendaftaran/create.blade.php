<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Open Recruitment FSI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 rounded-md bg-green-50 text-green-700 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('daftar.store') }}" class="space-y-6">
                        @csrf

                        {{-- Nama Lengkap: otomatis dari akun, read-only, tidak dikirim sebagai input --}}
                        <div>
                            <x-input-label for="full_name" value="Nama Lengkap" />
                            <x-text-input id="full_name" type="text" class="mt-1 block w-full bg-gray-100"
                                value="{{ auth()->user()->name }}" disabled />
                            <p class="text-sm text-gray-500 mt-1">Diambil otomatis dari akunmu.</p>
                        </div>

                        {{-- Nama Panggilan --}}
                        <div>
                            <x-input-label for="nickname" value="Nama Panggilan" />
                            <x-text-input id="nickname" name="nickname" type="text" class="mt-1 block w-full"
                                value="{{ old('nickname') }}" required autofocus />
                            <x-input-error :messages="$errors->get('nickname')" class="mt-2" />
                        </div>

                        {{-- Kelas --}}
                        <div>
                            <x-input-label for="class" value="Kelas" />
                            <x-text-input id="class" name="class" type="text" class="mt-1 block w-full"
                                value="{{ old('class') }}" required placeholder="Contoh: XII IPA 3" />
                            <x-input-error :messages="$errors->get('class')" class="mt-2" />
                        </div>

                        {{-- Username Instagram --}}
                        <div>
                            <x-input-label for="username_ig" value="Username Instagram" />
                            <x-text-input id="username_ig" name="username_ig" type="text" class="mt-1 block w-full"
                                value="{{ old('username_ig') }}" required placeholder="@username" />
                            <x-input-error :messages="$errors->get('username_ig')" class="mt-2" />
                        </div>

                        {{-- Alasan Bergabung --}}
                        <div>
                            <x-input-label for="reason" value="Alasan Bergabung" />
                            <textarea id="reason" name="reason" rows="5"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>{{ old('reason') }}</textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Kirim Pendaftaran') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
