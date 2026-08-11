{{-- Kartu Postingan — dipakai di tab (hal. 1) maupun endpoint AJAX (hal. N).
     Markup tiap kartu di-delegasikan ke _post_item.blade.php supaya identik
     dengan render per-item saat mode "Lihat Semua" dikelompokkan per kategori. --}}
@forelse($posts as $post)
    @include('admin._post_item', ['post' => $post])
@empty
    <div class="text-center text-xs text-white/40 py-4">Belum ada postingan komunitas.</div>
@endforelse
