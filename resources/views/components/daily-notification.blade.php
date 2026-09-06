{{--
    resources/views/components/daily-notification.blade.php
    ========================================================
    Floating Daily Hadith & Bukittinggi Prayer Times Card (Uiverse Dock Style):
    Desain bersih, berwibawa, dilengkapi dock pill yang bisa diminimize dan expand mulus.
--}}
<aside id="daily-notification"
       class="is-hidden"
       aria-label="Mutiara Hadits & Waktu Sholat"
       role="complementary">

    {{-- MINI DOCK PILL (Saat diminimize) --}}
    <button type="button"
            id="dn-mini-pill"
            aria-label="Buka Jadwal Sholat & Hadits"
            title="Klik untuk membuka Mutiara Hadits & Jadwal Sholat">
        <span class="w-2 h-2 rounded-full bg-[#3fd6b0] relative flex items-center justify-center">
            <span class="w-2 h-2 rounded-full bg-[#3fd6b0] animate-ping absolute"></span>
        </span>
        <i class="fa-solid fa-mosque text-[var(--gold)] text-xs"></i>
        <span id="dn-mini-prayer-name" class="font-bold text-xs text-[var(--cream)]">Sholat</span>
        <span id="dn-mini-countdown" class="text-[11px] font-mono text-[#3fd6b0] font-semibold">--:--</span>
        <i class="fa-solid fa-chevron-up text-[10px] text-white/50 ml-1"></i>
    </button>

    {{-- FULL EXPANDED CARD --}}
    <div id="dn-full-card" class="p-4 rounded-2xl border border-[var(--gold)]/35 bg-[#0D2617]/95 backdrop-blur-md shadow-2xl text-[var(--cream)] relative w-[320px] sm:w-[360px]">
        {{-- Control buttons: Minimize & Close --}}
        <div class="absolute top-3 right-3 flex items-center gap-1 z-10">
            <button type="button"
                    id="minimize-daily-notification"
                    class="w-6 h-6 rounded-full bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center text-[10px] transition cursor-pointer"
                    aria-label="Kecilkan notifikasi"
                    title="Kecilkan ke Dock Pill">
                <i class="fa-solid fa-minus"></i>
            </button>
            <button type="button"
                    id="close-daily-notification"
                    class="w-6 h-6 rounded-full bg-white/5 hover:bg-white/10 text-white/50 hover:text-white flex items-center justify-center text-xs transition cursor-pointer"
                    aria-label="Tutup notifikasi"
                    title="Tutup notifikasi">
                <i class="fa-solid fa-xmark text-[11px]"></i>
            </button>
        </div>

        {{-- Top Bar: Tab Switcher & Next Prayer Indicator --}}
        <div class="flex items-center gap-1.5 mb-3 pr-14 border-b border-white/10 pb-2.5">
            <button type="button" id="dn-tab-hadith" onclick="switchDailyNotif('hadith')"
                    class="dn-tab px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider transition bg-[var(--gold)]/20 text-[var(--gold)] border border-[var(--gold)]/40">
                <i class="fa-solid fa-quote-left mr-1"></i> Hadits
            </button>
            <button type="button" id="dn-tab-sholat" onclick="switchDailyNotif('sholat')"
                    class="dn-tab px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider transition text-white/60 hover:text-white hover:bg-white/5 border border-transparent">
                <i class="fa-regular fa-clock mr-1"></i> Sholat
            </button>
            <span id="dn-next-prayer-badge" class="ml-auto text-[10px] font-mono text-[#3fd6b0] font-bold truncate">
                <!-- Auto-updated via JS -->
            </span>
        </div>

        {{-- PANE 1: HADITH --}}
        <div id="dn-pane-hadith">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-[var(--gold)]/15 border border-[var(--gold)]/30 text-[var(--gold)] flex items-center justify-center text-xs shrink-0 mt-0.5 shadow-sm">
                    <i class="fa-solid fa-book-quran"></i>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--gold)]">
                            Mutiara Pelajar Rabbani
                        </span>
                        <span id="dn-hadith-source" class="text-[10px] text-white/45 font-mono">HR. Muslim no. 2699</span>
                    </div>

                    <p id="dn-hadith-text" class="text-xs text-[var(--cream)]/90 leading-relaxed italic">
                        &ldquo;Barangsiapa menempuh jalan untuk menuntut ilmu, maka Allah akan memudahkan baginya jalan menuju surga.&rdquo;
                    </p>

                    <div class="mt-3 flex items-center gap-3">
                        <button type="button"
                                id="dn-copy-btn"
                                onclick="copyDailyHadith(this)"
                                data-quote="Barangsiapa menempuh jalan untuk menuntut ilmu, maka Allah akan memudahkan baginya jalan menuju surga. (HR. Muslim no. 2699) — Forum Studi Islam SMAN 1 Bukittinggi"
                                class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-[var(--gold)] hover:text-white transition-colors cursor-pointer">
                            <i class="fa-regular fa-copy text-[10px]"></i>
                            <span>Salin Hadits</span>
                        </button>
                        <button type="button"
                                onclick="nextDailyHadith()"
                                class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-white/50 hover:text-white transition-colors cursor-pointer ml-auto">
                            <i class="fa-solid fa-rotate text-[10px]"></i>
                            <span>Hadits Lain</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- PANE 2: JADWAL SHOLAT BUKITTINGGI --}}
        <div id="dn-pane-sholat" class="hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--gold)]">
                    <i class="fa-solid fa-location-dot mr-1"></i> Bukittinggi &amp; Sekitarnya (WIB)
                </span>
                <span id="dn-live-clock" class="text-[10px] font-mono text-white/50 font-bold"></span>
            </div>

            <div class="grid grid-cols-5 gap-1.5 text-center mt-2.5">
                <div class="p-1.5 rounded-xl bg-white/5 border border-white/10 dn-prayer-box" data-prayer="subuh">
                    <span class="block text-[9px] uppercase tracking-wider text-white/50 font-bold">Subuh</span>
                    <span class="block text-xs font-mono font-bold text-[var(--cream)] mt-0.5">04:55</span>
                </div>
                <div class="p-1.5 rounded-xl bg-white/5 border border-white/10 dn-prayer-box" data-prayer="dzuhur">
                    <span class="block text-[9px] uppercase tracking-wider text-white/50 font-bold">Dzuhur</span>
                    <span class="block text-xs font-mono font-bold text-[var(--cream)] mt-0.5">12:18</span>
                </div>
                <div class="p-1.5 rounded-xl bg-white/5 border border-white/10 dn-prayer-box" data-prayer="ashar">
                    <span class="block text-[9px] uppercase tracking-wider text-white/50 font-bold">Ashar</span>
                    <span class="block text-xs font-mono font-bold text-[var(--cream)] mt-0.5">15:30</span>
                </div>
                <div class="p-1.5 rounded-xl bg-white/5 border border-white/10 dn-prayer-box" data-prayer="maghrib">
                    <span class="block text-[9px] uppercase tracking-wider text-white/50 font-bold">Maghrib</span>
                    <span class="block text-xs font-mono font-bold text-[var(--cream)] mt-0.5">18:22</span>
                </div>
                <div class="p-1.5 rounded-xl bg-white/5 border border-white/10 dn-prayer-box" data-prayer="isya">
                    <span class="block text-[9px] uppercase tracking-wider text-white/50 font-bold">Isya</span>
                    <span class="block text-xs font-mono font-bold text-[var(--cream)] mt-0.5">19:32</span>
                </div>
            </div>

            <div class="mt-3 pt-2 border-t border-white/10 flex items-center justify-between text-[10px] text-white/45">
                <span id="dn-prayer-status-text">Waktu sholat berikutnya...</span>
                <span class="text-[var(--gold)] font-bold"><i class="fa-solid fa-mosque mr-1"></i>FSI SMAN 1</span>
            </div>
        </div>
    </div>
</aside>
