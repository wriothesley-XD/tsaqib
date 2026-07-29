<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Labor PAI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Visi & Misi --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Visi &amp; Misi FSI</h3>

                    <p class="mb-2"><span class="font-medium">Visi:</span> {{ $visiMisi['visi'] }}</p>

                    <p class="font-medium mb-1">Misi:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($visiMisi['misi'] as $misi)
                            <li>{{ $misi }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Struktur Organisasi: Pembina --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Struktur Organisasi — Pembina</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach ($pembina as $orang)
                            <li class="py-2 flex justify-between">
                                <span>{{ $orang['nama'] }}</span>
                                <span class="text-gray-500">{{ $orang['jabatan'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Struktur Organisasi: Pengurus Siswa --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Struktur Organisasi — Pengurus Siswa</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach ($pengurusSiswa as $orang)
                            <li class="py-2 flex justify-between">
                                <span>{{ $orang['nama'] }}</span>
                                <span class="text-gray-500">{{ $orang['jabatan'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
