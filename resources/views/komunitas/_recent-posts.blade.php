{{--
    resources/views/komunitas/_recent-posts.blade.php
    Widget sidebar "Postingan Terbaru" — 5 post terbaru lintas komunitas.

    Komponen mandiri (markup + style + script scoped; pola components/community-picker):
    hanya MEMBACA $recentPosts yang SUDAH dikirim PageController::komunitasIndex
    (tidak ada query/fetch/route baru). Dipakai di sidebar kiri feed, di bawah
    daftar komunitas.

    Klik item: kalau post sedang termuat di feed, scroll halus ke kartunya;
    kalau tidak (halaman feed lain), biarkan <a> membuka halaman detail post.
    Style/script inline (bukan @push): @stack('styles') di <head> sudah
    ter-echo sebelum bagian <body> dirender, jadi @push dari sini tidak sampai.
--}}
@php($recentPosts = $recentPosts ?? collect())

@if ($recentPosts->isNotEmpty())
<div class="tsaqib-card p-3 rp-widget">
    <p class="px-2 mb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">Postingan Terbaru</p>
    <div class="rp-list">
        @foreach ($recentPosts as $rp)
            @php($rpKomunitas = $rp->community_slug ? collect(config('komunitas.daftar'))->firstWhere('slug', $rp->community_slug) : null)
            <a href="{{ route('komunitas.post.show', $rp->id) }}" data-rp
               data-rp-url="{{ route('komunitas.post.show', $rp->id) }}"
               class="rp-item">
                @if ($rpKomunitas)
                    <img src="{{ asset($rpKomunitas['image']) }}" alt="" loading="lazy" onerror="this.remove()" class="rp-avatar">
                @else
                    <span class="rp-avatar rp-avatar-fallback"><i class="fa-solid fa-users"></i></span>
                @endif
                <span class="rp-body">
                    <span class="rp-title">{{ $rp->title }}</span>
                    <span class="rp-meta">{{ $rpKomunitas['nama'] ?? 'umum' }} &middot; {{ $rp->created_at->diffForHumans() }}</span>
                </span>
            </a>
        @endforeach
    </div>
</div>

<style>
/* Scoped via .rp-widget agar tak bentrok dengan style halaman. */
.rp-widget .rp-list{ display:flex; flex-direction:column; gap:.15rem; }
.rp-widget .rp-item{ display:flex; align-items:center; gap:.6rem; padding:.45rem .5rem;
    border-radius:.6rem; transition:background .15s ease; }
.rp-widget .rp-item:hover{ background:rgba(247,245,239,.05); }
.rp-widget .rp-avatar{ width:1.75rem; height:1.75rem; border-radius:999px; object-fit:cover;
    flex-shrink:0; background:rgba(247,245,239,.05); }
.rp-widget .rp-avatar-fallback{ display:flex; align-items:center; justify-content:center;
    border:1px solid rgba(247,245,239,.1); color:rgba(247,245,239,.4); font-size:.6rem; }
.rp-widget .rp-body{ min-width:0; display:flex; flex-direction:column; gap:.1rem; }
.rp-widget .rp-title{ font-size:.75rem; font-weight:600; color:rgba(247,245,239,.85); line-height:1.3;
    overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
.rp-widget .rp-meta{ font-size:10px; color:rgba(247,245,239,.4); white-space:nowrap;
    overflow:hidden; text-overflow:ellipsis; }
</style>

<script>
(function () {
    /* Klik item widget: post ada di feed (article[data-post-url]) -> scroll halus
       dengan offset navbar sticky; tidak ada -> navigasi normal via href. */
    document.addEventListener('click', function (e) {
        const a = e.target.closest('[data-rp]');
        if (! a) return;
        const url = a.dataset.rpUrl || '';
        const sel = 'article[data-post-url="' + (window.CSS && CSS.escape ? CSS.escape(url) : url) + '"]';
        const art = document.querySelector(sel);
        if (! art) return;
        e.preventDefault();
        const y = art.getBoundingClientRect().top + window.scrollY - 88;
        window.scrollTo({ top: y, behavior: 'smooth' });
    });
})();
</script>
@endif
