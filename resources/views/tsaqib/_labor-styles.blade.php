{{-- Gaya bersama sub-halaman Laboratorium PAI (profil/modul/tugas).
     Vokabularian visual dicopy PERSIS dari landing.blade.php:
     font-display uppercase, tab underline — bukan pill generik. --}}
<style>
    /* Label kecil di atas grup filter ("FILTER KELAS", "FILTER KATEGORI") */
    .grp-label{
        font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:10px;
        letter-spacing:.14em;text-transform:uppercase;color:rgba(245,241,232,.35);
    }

    /* Chip kategori — border tipis; hanya aktif yang ber-fill emas */
    .labor-chip{
        padding:.32rem .85rem;border-radius:999px;cursor:pointer;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:.7rem;font-weight:600;
        border:1px solid rgba(245,241,232,.16);
        color:rgba(245,241,232,.6);
        background:transparent;
        transition:color .18s ease,border-color .18s ease,background .18s ease;
    }
    .labor-chip:hover{ border-color:rgba(201,166,107,.5); color:var(--cream); }
    .labor-chip.is-active{
        background:rgba(201,166,107,.16);
        border-color:var(--gold);
        color:var(--gold);
    }

    /* Kartu modul — aksen garis emas kiri (bukan kartu kotak simetris) */
    .labor-modul-card{
        border-left:3px solid rgba(201,166,107,.55);
        transition:transform .22s ease,border-left-color .22s ease;
    }
    .labor-modul-card:hover{ transform:translateY(-3px); border-left-color:var(--gold); }

    /* Garis aksen emas kiri untuk list editorial */
    .labor-editorial{ border-left:3px solid var(--gold); }

    /* Fade saat item muncul kembali setelah filter/search berganti */
    @keyframes laborFade{ from{ opacity:0; transform:translateY(6px); } }
    .labor-fade{ animation:laborFade .22s ease; }

    /* ===== Scroll-reveal + stagger (pola landing & /info) =====
       Hidden-state HANYA saat <html> ber-class .js-reveal → tanpa JS tetap terlihat. */
    .js-reveal .reveal{
        opacity:0; transform:translateY(14px);
        transition:opacity .5s ease,transform .5s ease;
        transition-delay:calc(var(--reveal-i,0) * 90ms);
    }
    .js-reveal .reveal.is-visible{ opacity:1; transform:none; }

    @media (prefers-reduced-motion: reduce){
        .js-reveal .reveal{ opacity:1; transform:none; transition:none; }
        .labor-fade{ animation:none; }
    }
</style>
