{{-- Kartu Laporan — dipakai di tab (hal. 1) maupun endpoint AJAX (hal. N).
     Markup tiap kartu di-delegasikan ke _laporan_item.blade.php supaya identik
     dengan render per-item saat mode "Lihat Semua" dikelompokkan per kategori. --}}
@forelse($laporan as $r)
    @include('admin._laporan_item', ['r' => $r])
@empty
    <div class="text-center text-xs text-white/40 py-8">Tidak ada laporan pending. Semua aman. 🎉</div>
@endforelse
