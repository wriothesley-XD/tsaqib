{{-- resources/views/components/shortcuts-modal.blade.php --}}
<div id="shortcuts-help-modal" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-md items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-label="Bantuan Tombol Pintas Keyboard">
    <div class="tsaqib-card w-full max-w-md overflow-hidden rounded-2xl shadow-2xl border border-[var(--gold)]/30 bg-[#10140F]/95 p-6 space-y-5">
        
        <div class="flex items-center justify-between pb-3 border-b border-white/10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[var(--gold)]/15 text-[var(--gold)] flex items-center justify-center text-sm">
                    <i class="fa-solid fa-keyboard"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-base text-[var(--cream)]">Pintasan Keyboard</h3>
                    <p class="text-white/40 text-[11px]">Navigasi kilat untuk Power Users &amp; Evaluator</p>
                </div>
            </div>
            <button type="button" onclick="toggleShortcutsHelp()" class="w-7 h-7 rounded-lg bg-white/5 hover:bg-white/15 text-white/50 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <div class="space-y-2.5 text-xs">
            <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-white/5">
                <span class="text-white/70">Buka Command Palette</span>
                <div class="flex items-center gap-1">
                    <span class="cmd-kbd">Ctrl</span><span class="text-white/30">+</span><span class="cmd-kbd">K</span>
                    <span class="text-white/30 mx-1">atau</span>
                    <span class="cmd-kbd">/</span>
                </div>
            </div>

            <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-white/5">
                <span class="text-white/70">Ke Halaman Beranda</span>
                <span class="cmd-kbd">H</span>
            </div>

            <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-white/5">
                <span class="text-white/70">Ke Laboratorium PAI</span>
                <span class="cmd-kbd">L</span>
            </div>

            <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-white/5">
                <span class="text-white/70">Ke Perpustakaan Digital</span>
                <span class="cmd-kbd">P</span>
            </div>

            <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-white/5">
                <span class="text-white/70">Ke Feed Komunitas</span>
                <span class="cmd-kbd">K</span>
            </div>

            <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-white/5">
                <span class="text-white/70">Ke Pendaftaran Oprec</span>
                <span class="cmd-kbd">O</span>
            </div>

            <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-white/5">
                <span class="text-white/70">Tutup Modal / Dialog</span>
                <span class="cmd-kbd">ESC</span>
            </div>
        </div>

        <div class="pt-3 border-t border-white/10 text-center">
            <p class="text-[11px] text-white/40">
                Pintasan aktif di semua halaman saat kursor tidak berada di kolom teks.
            </p>
        </div>

    </div>
</div>
