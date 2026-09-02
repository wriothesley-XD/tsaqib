{{-- resources/views/komunitas/post.blade.php — detail satu postingan + komentar --}}
@php($pageTitle = 'Detail Postingan - Komunitas TSAQIB')

@push('styles')
<style>
    /* Vote buttons (thumbs pill) */
    .vote-btn{ background:rgba(247,245,239,.05); color:rgba(247,245,239,.6); transition:background .15s ease,color .15s ease; }
    .vote-btn:hover{ background:rgba(247,245,239,.10); color:var(--cream); }
    .vote-btn.is-up{ background:var(--green); color:#fff; }
    .vote-btn.is-down{ background:rgba(239,68,68,.20); color:#fca5a5; }
    .vote-btn:disabled{ opacity:.6; cursor:default; }

    /* Action chips */
    .action-chip{ background:rgba(247,245,239,.05); color:rgba(247,245,239,.6); transition:background .15s ease,color .15s ease; cursor:pointer; }
    .action-chip:hover{ background:rgba(247,245,239,.10); color:var(--cream); }
    .save-btn.is-active{ background:var(--gold); color:#10140F; }
    .save-btn.is-active:hover{ background:var(--green-dark); color:#fff; }

    /* Media grid */
    .media-grid{ display:grid; gap:.25rem; border-radius:.75rem; overflow:hidden; }
    .media-grid.cols-1{ grid-template-columns:1fr; }
    .media-grid.cols-2{ grid-template-columns:1fr 1fr; }
    .media-grid.cols-3{ grid-template-columns:1fr 1fr; }
    @media(min-width:640px){ .media-grid.cols-3{ grid-template-columns:1fr 1fr 1fr; } }
    .media-grid.cols-4{ grid-template-columns:1fr 1fr; }
    .media-tile{ position:relative; aspect-ratio:1/1; background:rgba(247,245,239,.05); cursor:zoom-in; overflow:hidden; }
    .media-tile img,.media-tile video{ width:100%; height:100%; object-fit:cover; display:block; }
    /* Foto tunggal: tanpa box 16:10 paksa & tanpa latar blur — tinggi mengikuti
       rasio asli foto (max-height wajar), foto contain + center horizontal. */
    .media-tile.single{ aspect-ratio:auto; max-height:32rem; display:flex; align-items:center; justify-content:center; background:rgba(16,20,15,.4); }
    .media-tile.single > img{ width:auto; height:auto; max-width:100%; max-height:32rem; object-fit:contain; margin:0 auto; }
    .media-grid.cols-1 .media-tile > video{ object-fit:contain; object-position:center center; background:#000; }
    /* Overlay "+N": menempel penuh di ATAS tile foto terakhir (.media-tile sudah
       position:relative). inset:0 = tutup seluruh foto; 55% gelap -> foto tetap
       samar terlihat; teks center horizontal+vertikal; z-index di atas img. */
    .media-more{ position:absolute; inset:0; z-index:2; display:flex; align-items:center; justify-content:center; background:rgba(16,20,15,.55); color:var(--cream); font-weight:800; font-size:1.4rem; }
    .media-play{ position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(16,20,15,.35); pointer-events:none; }
    .media-play i{ color:#fff; font-size:1.6rem; filter:drop-shadow(0 2px 6px rgba(0,0,0,.6)); }

    /* Lightbox */
    #lightbox{ position:fixed; inset:0; z-index:60; background:rgba(0,0,0,.92); display:flex; align-items:center; justify-content:center; padding:1rem; opacity:0; visibility:hidden; transition:opacity .25s ease, visibility .25s; }
    #lightbox.open{ opacity:1; visibility:visible; }
    #lb-item{ transition:opacity .18s ease, transform .18s ease; }
    #lb-item.fade{ opacity:0; }
    #lb-item img,#lb-item video{ max-width:100%; max-height:80vh; border-radius:.5rem; display:block; box-shadow:0 20px 60px rgba(0,0,0,.6); }
    .lb-stage{ position:relative; max-width:100%; max-height:100%; display:flex; align-items:center; justify-content:center; }
    .lb-btn{ position:absolute; width:42px; height:42px; border-radius:999px; background:rgba(247,245,239,.1); color:rgba(247,245,239,.85); display:flex; align-items:center; justify-content:center; font-size:1rem; transition:background .15s, color .15s; }
    .lb-btn:hover{ background:rgba(247,245,239,.2); color:var(--gold); }
    .lb-prev{ left:.5rem; top:50%; transform:translateY(-50%); }
    .lb-next{ right:.5rem; top:50%; transform:translateY(-50%); }
    .lb-close{ top:1rem; right:1rem; }
    .lb-counter{ position:absolute; top:1rem; left:50%; transform:translateX(-50%); background:rgba(16,20,15,.7); color:var(--cream); font-size:11px; font-weight:700; padding:4px 12px; border-radius:999px; }
    @media(max-width:640px){
        #lightbox{ align-items:flex-end; padding:0; }
        .lb-stage{ width:100%; border-radius:1rem 1rem 0 0; background:#000; padding:.5rem .5rem 1.25rem; }
        #lb-item img,#lb-item video{ max-height:72vh; }
    }
</style>
@endpush

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    @include('partials.navbar')

    <main class="flex-1 max-w-2xl mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-6 w-full">

        <a href="{{ route('komunitas', 'semua') }}" class="inline-flex items-center gap-2 text-xs text-white/55 hover:text-[var(--gold)] font-semibold">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Feed
        </a>

        {{-- Post card (tidak clickable di halaman detail) --}}
        <article class="tsaqib-card p-6">
            @include('komunitas._post-card', ['post' => $post, 'showManage' => false])
        </article>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/30 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- KOMENTAR --}}
        <section class="tsaqib-card p-6">
            <h2 class="font-display font-bold text-sm text-[var(--cream)] mb-4 flex items-center gap-2">
                <i class="fa-regular fa-comments text-[var(--gold)]"></i>
                Komentar <span class="text-white/40">({{ $post->comments_count }})</span>
            </h2>

            @auth
                <form id="comment-form" data-post-id="{{ $post->id }}" class="mb-4">
                    @csrf
                    <textarea name="body" rows="3" required maxlength="2000"
                              placeholder="Tulis komentar..."
                              class="tsaqib-input w-full px-3 py-2 text-xs"></textarea>
                    <div class="flex justify-end mt-2">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white text-xs font-semibold">
                            <i class="fa-solid fa-paper-plane mr-1.5"></i>Kirim
                        </button>
                    </div>
                </form>
            @else
                <div class="mb-4 p-3 rounded-xl bg-white/5 text-center text-xs text-white/50">
                    <a href="{{ route('login') }}" class="text-[var(--gold)] font-semibold">Masuk</a> untuk berkomentar.
                </div>
            @endauth

            <div id="comment-list">
                @forelse($post->comments as $c)
                    @include('komunitas._comment', ['c' => $c])
                @empty
                    <p class="text-center text-white/40 text-xs py-6">Belum ada komentar. Jadilah yang pertama!</p>
                @endforelse
            </div>
        </section>

    </main>

    @include('partials.site-footer')

    {{-- Lightbox --}}
    <div id="lightbox">
        <div class="lb-stage">
            <div id="lb-item"></div>
            <div class="lb-counter" id="lb-counter"></div>
            <button type="button" class="lb-btn lb-close" data-action="lb-close" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            <button type="button" class="lb-btn lb-prev" data-action="lb-nav" data-dir="-1" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
            <button type="button" class="lb-btn lb-next" data-action="lb-nav" data-dir="1" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] hidden bg-[#01795F] text-white text-xs font-semibold px-4 py-2.5 rounded-full shadow-lg"></div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const loginUrl = '{{ route("login") }}';

        /* ===== Toast ===== */
        let toastTimer;
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.remove('hidden');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => t.classList.add('hidden'), 2000);
        }

        /* ===== Lightbox ===== */
        let lbMedia = [], lbIndex = 0;
        function openLightbox(tile) {
            const grid = tile.closest('.media-grid');
            if (! grid) return;
            try { lbMedia = JSON.parse(grid.dataset.media); } catch (e) { return; }
            lbIndex = parseInt(tile.dataset.index || '0', 10) || 0;
            document.getElementById('lightbox').classList.add('open');
            document.body.classList.add('overflow-hidden');
            lbRender(0);
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('open');
            document.body.classList.remove('overflow-hidden');
            const v = document.querySelector('#lb-item video');
            if (v) v.pause();
        }
        function lbNav(dir) {
            if (! lbMedia.length) return;
            lbIndex = (lbIndex + dir + lbMedia.length) % lbMedia.length;
            lbRender(dir);
        }
        function lbRender(dir) {
            const item = document.getElementById('lb-item');
            const counter = document.getElementById('lb-counter');
            if (! lbMedia.length) return;
            counter.textContent = (lbIndex + 1) + ' / ' + lbMedia.length;
            const m = lbMedia[lbIndex];
            item.classList.add('fade');
            item.style.transform = dir === -1 ? 'translateX(-12px)' : (dir === 1 ? 'translateX(12px)' : '');
            setTimeout(() => {
                item.innerHTML = m.type === 'video'
                    ? `<video src="${m.url}" controls autoplay playsinline></video>`
                    : `<img src="${m.url}" alt="">`;
                item.classList.remove('fade');
                item.style.transform = '';
                [lbIndex + 1, lbIndex - 1].forEach(i => {
                    if (i >= 0 && i < lbMedia.length && lbMedia[i].type === 'image') { const im = new Image(); im.src = lbMedia[i].url; }
                });
            }, 180);
        }
        document.addEventListener('keydown', (e) => {
            if (! document.getElementById('lightbox').classList.contains('open')) return;
            if (e.key === 'Escape') closeLightbox();
            else if (e.key === 'ArrowLeft') lbNav(-1);
            else if (e.key === 'ArrowRight') lbNav(1);
        });

        /* ===== AKSI UI via DELEGASI (pengganti inline onclick — aman CSP) ===== */
        document.addEventListener('click', (e) => {
            const el = e.target.closest('[data-action]');
            if (! el) return;
            const action = el.dataset.action;
            if (action === 'open-lightbox') { e.preventDefault(); openLightbox(el); }
            else if (action === 'lb-close') { e.preventDefault(); closeLightbox(); }
            else if (action === 'lb-nav') { e.preventDefault(); lbNav(parseInt(el.dataset.dir, 10) || 0); }
        });

        /* Backdrop lightbox: klik di luar .lb-stage -> tutup */
        document.getElementById('lightbox')?.addEventListener('click', (e) => {
            if (! e.target.closest('.lb-stage')) closeLightbox();
        });

        /* Konfirmasi hapus via delegasi (pengganti onsubmit inline) */
        document.addEventListener('submit', (e) => {
            if (e.target.matches('[data-confirm]') && ! confirm(e.target.dataset.confirm)) e.preventDefault();
        });

        async function postJSON(url, body) {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(body),
            });
            if (res.status === 401) { window.location.href = loginUrl; throw new Error('auth'); }
            if (! res.ok) throw new Error('request failed');
            return res.json();
        }

        /* ===== Vote (thumbs) ===== */
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.vote-btn');
            if (! btn) return;
            e.preventDefault();
            @guest
            window.location.href = loginUrl;
            return;
            @endguest
            const type = btn.dataset.type;
            btn.disabled = true;
            try {
                const data = await postJSON(`/posts/${btn.dataset.postId}/vote`, { type });
                btn.closest('article').querySelectorAll('.vote-btn').forEach((b) => {
                    const t = b.dataset.type;
                    const c = b.querySelector('[data-count]');
                    if (c) c.textContent = data[t === 'up' ? 'upvotes' : 'downvotes'];
                    b.classList.toggle('is-up', data.my_vote === 'up' && t === 'up');
                    b.classList.toggle('is-down', data.my_vote === 'down' && t === 'down');
                });
            } catch (err) { if (err.message !== 'auth') console.error(err); }
            finally { btn.disabled = false; }
        });

        /* ===== Simpan / batal simpan (Tersimpan) — pivot post_user ===== */
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.save-btn');
            if (! btn) return;
            e.preventDefault();
            @guest
            window.location.href = loginUrl;
            return;
            @endguest
            btn.disabled = true;
            try {
                const data = await postJSON(`/posts/${btn.dataset.postId}/save`, {});
                btn.classList.toggle('is-active', data.saved);
                btn.title = data.saved ? 'Hapus dari Tersimpan' : 'Simpan ke Tersimpan';
                const label = btn.querySelector('span:not([data-count])');
                if (label) label.textContent = data.saved ? 'Tersimpan' : 'Simpan';
                showToast(data.saved ? 'Disimpan ke Tersimpan.' : 'Dihapus dari Tersimpan.');
            } catch (err) { if (err.message !== 'auth') console.error(err); }
            finally { btn.disabled = false; }
        });

        /* ===== Share (copy link + toast) ===== */
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.share-btn');
            if (! btn) return;
            e.preventDefault();
            const url = btn.dataset.url;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => showToast('Link tersalin!')).catch(() => showToast('Link: ' + url));
            } else {
                showToast('Link: ' + url);
            }
        });

        /* ===== Comment submit (AJAX, prepend) ===== */
        const commentForm = document.getElementById('comment-form');
        if (commentForm) {
            commentForm.addEventListener('submit', async (ev) => {
                ev.preventDefault();
                const body = commentForm.querySelector('[name="body"]').value.trim();
                if (! body) return;
                const submitBtn = commentForm.querySelector('[type="submit"]');
                submitBtn.disabled = true;
                try {
                    const data = await postJSON(`/posts/${commentForm.dataset.postId}/comments`, { body });
                    const list = document.getElementById('comment-list');
                    list.insertAdjacentHTML('afterbegin', data.html);
                    commentForm.reset();
                    // hapus pesan empty-state jika ada
                    const empty = list.querySelector('.text-center');
                    if (empty && empty.textContent.includes('Belum ada komentar')) empty.remove();
                } catch (err) { if (err.message !== 'auth') console.error(err); }
                finally { submitBtn.disabled = false; }
            });
        }

        /* ===== Comment delete (AJAX) ===== */
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.comment-delete');
            if (! btn) return;
            e.preventDefault();
            if (! confirm(btn.dataset.confirm || 'Hapus komentar ini?')) return;
            const row = btn.closest('.comment-row');
            try {
                const res = await fetch(`/comments/${btn.dataset.commentId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (res.ok) row?.remove();
            } catch (err) { console.error(err); }
        });

        /* ===== Lapor konten (post/comment) -> POST /reports ===== */
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.report-btn');
            if (! btn) return;
            e.preventDefault();
            const reason = prompt('Alasan laporan (singkat):', 'Spam / penyalahgunaan');
            if (! reason) return;
            try {
                const data = await postJSON('/reports', { reportable_type: btn.dataset.rt, reportable_id: btn.dataset.rid, reason });
                showToast(data.already ? (data.message || 'Sudah dilaporkan') : 'Terlapor, terima kasih.');
            } catch (err) { if (err.message !== 'auth') { console.error(err); showToast('Gagal melaporkan.'); } }
        });
    </script>

</body>
</html>
