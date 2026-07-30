<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Buku — Pustaka FSI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 rounded-md bg-green-50 text-green-700 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Daftar Buku</h3>
                        <a href="{{ route('admin.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                            {{ __('Tambah Buku') }}
                        </a>
                    </div>

                    @if ($books->isEmpty())
                        <p class="text-gray-500">Belum ada buku. Klik "Tambah Buku" untuk mulai.</p>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-2 pr-4">Judul</th>
                                    <th class="py-2 pr-4">Penulis</th>
                                    <th class="py-2 pr-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($books as $book)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-4">{{ $book->title }}</td>
                                        <td class="py-3 pr-4">{{ $book->author }}</td>
                                        <td class="py-3 pr-4 text-right space-x-3">
                                            <a href="{{ route('admin.edit', $book->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                {{ __('Edit') }}
                                            </a>
                                            <form method="POST" action="{{ route('admin.destroy', $book->id) }}"
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin hapus buku ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                                    {{ __('Hapus') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
