<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-6">{{ __("You're logged in!") }}</p>

                    {{-- Menu TSAQIB — tambah item baru di sini pakai pola yang sama --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('labor') }}"
                           class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition">
                            <span class="font-medium text-gray-900">Labor PAI</span>
                            <p class="text-sm text-gray-500 mt-1">Visi-misi &amp; struktur organisasi FSI</p>
                        </a>

                        <a href="{{ route('role') }}"
                           class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition">
                            <span class="font-medium text-gray-900">Informasi Role</span>
                            <p class="text-sm text-gray-500 mt-1">Detail jabatan &amp; wewenang internal FSI</p>
                        </a>

                        {{-- Placeholder, tinggal isi begitu route-nya jadi:
                        <a href="{{ route('daftar.create') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition">
                            <span class="font-medium text-gray-900">Open Recruitment</span>
                            <p class="text-sm text-gray-500 mt-1">Daftar jadi pengurus FSI</p>
                        </a>
                        --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>