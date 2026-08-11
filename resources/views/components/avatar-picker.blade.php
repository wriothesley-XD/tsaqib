{{-- Avatar picker — grid avatar bawaan yang bisa diklik.
    Dipakai di: register (pilih acak bila tak dipilih) & edit profil
    (pre-select avatar saat ini via $selected).

    Props:
      - name      : nama <input type="hidden"> yang menyimpan path terpilih.
      - selected  : path preset yang terpilih saat ini (ataau null).
      - randomize : bila true & tak ada yg terpilih, pilih satu secara acak.

    Mengikuti daftar dari User::presetAvatars() (glob assets/images/avatar/*),
    jadi cukup taruh file di folder itu lalu muncul otomatis di grid.
    Dispatch event 'avatar:selected' (bubbles) saat pengguna memilih, dengan
    detail { value, img } — dipakai modal edit untuk sinkron pratinjau ring.
--}}

@props([
    'name' => 'avatar',
    'selected' => null,
    'randomize' => false,
])

@php
    $avatars = \App\Models\User::presetAvatars();
    $initial = (is_string($selected) && in_array($selected, $avatars, true)) ? $selected : null;
@endphp

<div class="av-picker" data-avatar-picker @if((bool) $randomize) data-randomize @endif>
    <div class="av-grid">
        @foreach($avatars as $path)
            <button type="button"
                    class="av-pick{{ $initial === $path ? ' is-active' : '' }}"
                    data-value="{{ $path }}"
                    aria-label="Pilih avatar">
                <img src="{{ asset($path) }}" alt="" loading="lazy" draggable="false">
            </button>
        @endforeach
    </div>

    <input type="hidden" name="{{ $name }}" value="{{ $initial ?? '' }}" data-avatar-input>
</div>

<style>
/* Scoped via .av-picker agar tak bentrok dengan style halaman. */
.av-picker .av-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:.55rem; }
@media (min-width:480px){ .av-picker .av-grid{ grid-template-columns:repeat(7,1fr); } }
.av-picker .av-pick{
    position:relative; aspect-ratio:1/1; width:100%; padding:0; cursor:pointer;
    border-radius:9999px; background:#161a14; overflow:visible;
    border:2px solid rgba(247,245,239,.14);
    transition:transform .15s ease, border-color .15s ease, box-shadow .15s ease;
}
.av-picker .av-pick img{ width:100%; height:100%; border-radius:9999px; object-fit:cover; display:block; }
.av-picker .av-pick:hover{ transform:translateY(-2px); border-color:rgba(29,158,117,.65); }
.av-picker .av-pick.is-active{
    border-color:#1D9E75; box-shadow:0 0 0 3px rgba(29,158,117,.28);
}
/* Tanda centang pojok kanan-bawah saat terpilih (FontAwesome 6). */
.av-picker .av-pick.is-active::after{
    content:"\f00c"; font-family:"Font Awesome 6 Free"; font-weight:900;
    position:absolute; right:-3px; bottom:-3px;
    width:1.15rem; height:1.15rem; border-radius:9999px;
    display:flex; align-items:center; justify-content:center;
    background:#1D9E75; color:#fff; font-size:.6rem;
    box-shadow:0 2px 6px rgba(0,0,0,.5);
}
@media (prefers-reduced-motion: reduce){
    .av-picker .av-pick{ transition:none; }
    .av-picker .av-pick:hover{ transform:none; }
}
</style>

<script>
(function () {
    function init(root) {
        if (!root || root.__avInit) return;
        root.__avInit = true;

        var input = root.querySelector('[data-avatar-input]');
        var picks = root.querySelectorAll('.av-pick');
        if (!input || !picks.length) return;

        // Tentukan pilihan awal: tombol bertanda is-active (dari $selected),
        // atau — bila randomize & belum ada — satu tombol acak.
        var chosen = root.querySelector('.av-pick.is-active');
        if (!chosen && root.hasAttribute('data-randomize')) {
            chosen = picks[Math.floor(Math.random() * picks.length)];
            chosen.classList.add('is-active');
        }
        if (chosen) input.value = chosen.dataset.value;

        picks.forEach(function (btn) {
            btn.addEventListener('click', function () {
                picks.forEach(function (x) { x.classList.remove('is-active'); });
                btn.classList.add('is-active');
                input.value = btn.dataset.value;
                root.dispatchEvent(new CustomEvent('avatar:selected', {
                    bubbles: true,
                    detail: { value: btn.dataset.value, img: (btn.querySelector('img') || {}).src || '' }
                }));
            });
        });
    }
    document.querySelectorAll('[data-avatar-picker]').forEach(init);
})();
</script>
