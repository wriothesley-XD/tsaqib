{{--
    Partial: Map utama Tsaqib Island (versi pannable / drag-to-scroll)

    Ukuran gambar asli map: 2406 x 2406 px
    Area yang kelihatan (viewport): dibatasi oleh #map-viewport (lihat map.css),
    default 1799x810 untuk desktop, sesuai ukuran frame di Figma.

    User bisa drag (klik-tahan-geser) map-nya untuk explore seluruh pulau,
    mirip Google Maps.

    Cara pakai di view utama:
        @include('partials.map')
--}}

<section id="screen-map" class="screen">

    <div id="map-viewport">
        <div id="map-inner" style="width: 2406px; height: 2406px;">

            <img
                src="{{ asset('images/peta-pulau.webp') }}"
                alt="Peta Tsaqib Island"
                width="2406"
                height="2406"
                class="pointer-events-none select-none"
                draggable="false"
            >

            @foreach (config('komunitas') as $slug => $komunitas)
                <button
                    type="button"
                    class="hotspot {{ !$komunitas['aktif'] ? 'hotspot-nonaktif' : '' }}"
                    style="top: {{ $komunitas['posisi']['y'] }}px; left: {{ $komunitas['posisi']['x'] }}px;"
                    data-komunitas="{{ $slug }}"
                    data-aktif="{{ $komunitas['aktif'] ? '1' : '0' }}"
                    {{ !$komunitas['aktif'] ? 'aria-disabled=true' : '' }}
                >
                    <img
                        src="{{ asset('images/' . $komunitas['icon']) }}"
                        alt="{{ $komunitas['nama'] }}"
                        class="hotspot-icon"
                    >
                    <span class="hotspot-label">{{ $komunitas['nama'] }}</span>

                    @unless ($komunitas['aktif'])
                        <span class="hotspot-badge">Land Belum Tersedia</span>
                    @endunless
                </button>
            @endforeach

        </div>
    </div>

</section>

{{-- Popup komunitas: sama seperti sebelumnya, tidak berubah --}}
<section id="screen-popup" class="screen hidden fixed inset-0 z-40 flex items-center justify-center bg-black/60">
    <div class="bg-white rounded-2xl p-6 max-w-md w-[90%] relative">
        <button type="button" id="popup-close" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700">
            &times;
        </button>

        <img id="popup-icon" src="" alt="" class="w-16 h-16 mb-3">
        <h2 id="popup-nama" class="text-xl font-bold mb-2"></h2>
        <p id="popup-deskripsi" class="text-sm text-gray-600 mb-4"></p>

        <button type="button" id="popup-daftar" class="w-full py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
            Daftar
        </button>
    </div>
</section>
