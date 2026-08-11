{{-- Community picker — grid komunitas yang bisa diklik.
    Dipakai di: modal Edit Profil (pre-select komunitas saat ini via $selected).

    Sumber data: Config::get('komunitas.daftar') — SAMA dengan yang dipakai
    /komunitas/{slug} (PageController::komunitasIndex) & select-role. Jangan
    mendefinisikan daftar komunitas baru di sini.

    Props:
      - name      : nama <input type="hidden"> yang menyimpan slug terpilih.
      - selected  : slug komunitas yang terpilih saat ini (atau null).

    Dispatch event 'community:selected' (bubbles) saat pengguna memilih, dengan
    detail { value, nama } — paritas dengan <x-avatar-picker>.
--}}

@props([
    'name' => 'community_slug',
    'selected' => null,
])

@php
    $komunitas = \Illuminate\Support\Facades\Config::get('komunitas.daftar', []);
    $slugs = array_column($komunitas, 'slug');
    $initial = (is_string($selected) && in_array($selected, $slugs, true)) ? $selected : null;
@endphp

<div class="cm-picker" data-community-picker>
    <div class="cm-grid">
        @foreach($komunitas as $k)
            <button type="button"
                    class="cm-pick{{ $initial === $k['slug'] ? ' is-active' : '' }}"
                    data-value="{{ $k['slug'] }}"
                    aria-label="Pilih komunitas {{ $k['nama'] }}">
                <img src="{{ asset($k['image']) }}" alt="" loading="lazy" draggable="false" onerror="this.remove()">
                <span class="cm-name">{{ $k['nama'] }}</span>
            </button>
        @endforeach
    </div>

    <input type="hidden" name="{{ $name }}" value="{{ $initial ?? '' }}" data-community-input>
</div>

<style>
/* Scoped via .cm-picker agar tak bentrok dengan style halaman. */
.cm-picker .cm-grid{ display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:.5rem; }
@media (min-width:480px){ .cm-picker .cm-grid{ grid-template-columns:repeat(3,minmax(0,1fr)); } }
.cm-picker .cm-pick{
    position:relative; display:flex; flex-direction:column; align-items:center;
    gap:.35rem; padding:.55rem .4rem .5rem; cursor:pointer;
    border-radius:.85rem; background:rgba(255,255,255,.04);
    border:2px solid rgba(247,245,239,.10);
    transition:transform .15s ease, border-color .15s ease, box-shadow .15s ease, background .15s ease;
}
.cm-picker .cm-pick img{
    width:2.6rem; height:2.6rem; border-radius:.7rem; object-fit:cover; display:block; background:rgba(255,255,255,.05);
}
.cm-picker .cm-name{
    font-size:.7rem; font-weight:700; color:rgba(247,245,239,.82); text-align:center; line-height:1.15;
    overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
}
.cm-picker .cm-pick:hover{ transform:translateY(-2px); border-color:rgba(29,158,117,.55); background:rgba(255,255,255,.06); }
.cm-picker .cm-pick.is-active{
    border-color:#1D9E75; box-shadow:0 0 0 3px rgba(29,158,117,.22); background:rgba(29,158,117,.10);
}
.cm-picker .cm-pick.is-active .cm-name{ color:#fff; }
/* Tanda centang pojok kanan-atas saat terpilih (FontAwesome 6). */
.cm-picker .cm-pick.is-active::after{
    content:"\f00c"; font-family:"Font Awesome 6 Free"; font-weight:900;
    position:absolute; right:-5px; top:-5px;
    width:1.1rem; height:1.1rem; border-radius:9999px;
    display:flex; align-items:center; justify-content:center;
    background:#1D9E75; color:#fff; font-size:.58rem;
    box-shadow:0 2px 6px rgba(0,0,0,.5);
}
@media (prefers-reduced-motion: reduce){
    .cm-picker .cm-pick{ transition:none; }
    .cm-picker .cm-pick:hover{ transform:none; }
}
</style>

<script>
(function () {
    function init(root) {
        if (!root || root.__cmInit) return;
        root.__cmInit = true;

        var input = root.querySelector('[data-community-input]');
        var picks = root.querySelectorAll('.cm-pick');
        if (!input || !picks.length) return;

        picks.forEach(function (btn) {
            btn.addEventListener('click', function () {
                picks.forEach(function (x) { x.classList.remove('is-active'); });
                btn.classList.add('is-active');
                input.value = btn.dataset.value;
                root.dispatchEvent(new CustomEvent('community:selected', {
                    bubbles: true,
                    detail: { value: btn.dataset.value, nama: (btn.querySelector('.cm-name') || {}).textContent || '' }
                }));
            });
        });
    }
    document.querySelectorAll('[data-community-picker]').forEach(init);
})();
</script>
