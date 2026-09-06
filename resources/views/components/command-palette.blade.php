{{-- resources/views/components/command-palette.blade.php --}}
<div id="command-palette-modal" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-md items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-label="Command Palette Pencarian Cepat">
    <div class="tsaqib-card w-full max-w-xl overflow-hidden rounded-2xl shadow-2xl border border-[var(--gold)]/30 bg-[#10140F]/95">
        
        {{-- Search Input Bar --}}
        <div class="relative flex items-center px-4 py-3.5 border-b border-white/10 input-glow-group">
            <i class="fa-solid fa-magnifying-glass input-icon text-[var(--gold)] text-sm mr-3" aria-hidden="true"></i>
            <input type="text" id="cmd-search-input" autocomplete="off" spellcheck="false"
                   placeholder="Cari fitur, modul, komunitas, atau rute..."
                   class="w-full bg-transparent text-sm text-[var(--cream)] placeholder-white/40 focus:outline-none font-sans">
            <div class="flex items-center gap-1.5 shrink-0 ml-2">
                <span class="cmd-kbd">ESC</span>
                <button type="button" onclick="closeCommandPalette()" class="w-6 h-6 rounded-md hover:bg-white/10 text-white/50 hover:text-white flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        </div>

        {{-- Dynamic Results List --}}
        <div id="cmd-results" class="max-h-80 overflow-y-auto p-2 space-y-1 divide-y divide-white/5">
            <!-- Populated dynamically by app.js -->
        </div>

        {{-- Footer Keyboard Hint Bar --}}
        <div class="px-4 py-2.5 bg-black/40 border-t border-white/5 flex items-center justify-between text-[11px] text-white/40">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1">
                    <span class="cmd-kbd">↑</span><span class="cmd-kbd">↓</span> Navigasi
                </span>
                <span class="inline-flex items-center gap-1">
                    <span class="cmd-kbd">↵</span> Buka
                </span>
            </div>
            <div class="flex items-center gap-1">
                <span class="cmd-kbd">?</span> Bantuan Pintasan
            </div>
        </div>

    </div>
</div>
