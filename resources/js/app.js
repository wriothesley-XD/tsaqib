import './bootstrap';

/**
 * TSAQIB National Competition Design System & Micro-Interactions Engine
 * Author: TSAQIB Development Team (SMAN 1 Bukittinggi & Liivo)
 */

document.addEventListener('DOMContentLoaded', () => {
    initScrollProgress();
    initAnimatedCounters();
    initCustomToast();
    initMicroAudio();
    initCommandPalette();
    initKeyboardShortcuts();
    initCardSpotlights();
    initInkRipple();
    initImageFallback();
});

/* =========================================================================
   11. IMAGE FALLBACK — gambar rusak diganti placeholder SVG (tanpa ikon broken)
   Delegasi di document (capture: event "error" tidak bubbles) sehingga mencakup
   semua <img>, termasuk yang dirender lewat JS setelah halaman dimuat.
   ========================================================================= */
function initImageFallback() {
    // Placeholder: rounded hijau gelap + ikon foto emas (selaras tema brand).
    const PLACEHOLDER = 'data:image/svg+xml,' + encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300">' +
        '<rect width="400" height="300" rx="16" fill="#0D2818"/>' +
        '<rect x="1" y="1" width="398" height="298" rx="15" fill="none" stroke="rgba(201,166,107,.35)" stroke-width="2"/>' +
        '<g fill="none" stroke="rgba(201,166,107,.7)" stroke-width="8" stroke-linecap="round" stroke-linejoin="round">' +
        '<rect x="150" y="100" width="100" height="80" rx="8"/>' +
        '<circle cx="178" cy="126" r="8" fill="rgba(201,166,107,.7)" stroke="none"/>' +
        '<path d="M150 168 l26 -24 l20 18 l16 -14 l38 20"/></g></svg>'
    );

    document.addEventListener('error', (e) => {
        const img = e.target;
        if (!(img instanceof HTMLImageElement) || img.dataset.fallbackApplied) return;
        img.dataset.fallbackApplied = '1';
        img.src = PLACEHOLDER;
    }, true);
}

/* =========================================================================
   1. SCROLL READING PROGRESS BAR
   ========================================================================= */
function initScrollProgress() {
    let progressBar = document.getElementById('reading-progress');
    if (!progressBar) {
        progressBar = document.createElement('div');
        progressBar.id = 'reading-progress';
        progressBar.className = 'reading-progress-bar';
        document.body.appendChild(progressBar);
    }

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
                if (totalHeight > 0) {
                    const pct = Math.min(100, Math.max(0, (window.scrollY / totalHeight) * 100));
                    progressBar.style.width = pct + '%';
                }
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
}

/* =========================================================================
   4. ANIMATED ODOMETER COUNTERS
   ========================================================================= */
function initAnimatedCounters() {
    const counterElements = document.querySelectorAll('[data-counter]');
    if (!counterElements.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-counter'), 10);
                const suffix = el.getAttribute('data-suffix') || '';
                const prefix = el.getAttribute('data-prefix') || '';
                const duration = 1400; // ms
                const startTime = performance.now();

                function updateCount(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    // Easing: easeOutExpo
                    const ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                    const current = Math.floor(ease * target);

                    el.textContent = `${prefix}${current}${suffix}`;

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        el.textContent = `${prefix}${target}${suffix}`;
                    }
                }

                requestAnimationFrame(updateCount);
                obs.unobserve(el);
            }
        });
    }, { threshold: 0.2 });

    counterElements.forEach(el => observer.observe(el));
}

/* =========================================================================
   5. CUSTOM TOAST NOTIFICATION SYSTEM
   ========================================================================= */
function initCustomToast() {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    window.showToast = function (message, type = 'success', duration = 3200) {
        if (window.playMicroSfx) window.playMicroSfx('pop');

        const toast = document.createElement('div');
        toast.className = 'tsaqib-toast';

        let icon = '<i class="fa-solid fa-circle-check text-[#3fd6b0] text-sm"></i>';
        if (type === 'gold') icon = '<i class="fa-solid fa-sparkles text-[var(--gold)] text-sm"></i>';
        if (type === 'info') icon = '<i class="fa-solid fa-circle-info text-sky-400 text-sm"></i>';
        if (type === 'warning') icon = '<i class="fa-solid fa-triangle-exclamation text-amber-400 text-sm"></i>';

        toast.style.setProperty('--toast-duration', `${duration}ms`);
        toast.innerHTML = `${icon}<span>${message}</span><span class="toast-progress"></span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => toast.remove(), 250);
        }, duration);
    };
}

/* =========================================================================
   6. MICRO-HAPTIC AUDIO UX (WEB AUDIO API)
   ========================================================================= */
function initMicroAudio() {
    let audioCtx = null;
    let sfxEnabled = localStorage.getItem('tsaqib_sfx') === 'true';

    function getAudioContext() {
        if (!audioCtx) {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (AudioContextClass) audioCtx = new AudioContextClass();
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    window.playMicroSfx = function (tone = 'click') {
        if (!sfxEnabled) return;
        try {
            const ctx = getAudioContext();
            if (!ctx) return;

            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);

            const now = ctx.currentTime;
            if (tone === 'click') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(1400, now);
                osc.frequency.exponentialRampToValueAtTime(700, now + 0.025);
                gain.gain.setValueAtTime(0.04, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.025);
                osc.start(now);
                osc.stop(now + 0.025);
            } else if (tone === 'pop') {
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(520, now);
                osc.frequency.exponentialRampToValueAtTime(980, now + 0.04);
                gain.gain.setValueAtTime(0.06, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.04);
                osc.start(now);
                osc.stop(now + 0.04);
            }
        } catch (_) {
            // Audio context unavailable or suppressed
        }
    };

    window.toggleSfx = function () {
        sfxEnabled = !sfxEnabled;
        localStorage.setItem('tsaqib_sfx', sfxEnabled ? 'true' : 'false');
        updateSfxButtons();

        if (sfxEnabled) {
            getAudioContext();
            window.playMicroSfx('pop');
            window.showToast('Efek suara mikro diaktifkan', 'gold', 2000);
        } else {
            window.showToast('Efek suara mikro dinonaktifkan', 'info', 2000);
        }
    };

    function updateSfxButtons() {
        document.querySelectorAll('[data-sfx-toggle]').forEach(btn => {
            const label = btn.querySelector('.sfx-status');
            const icon = btn.querySelector('.sfx-icon');
            if (label) label.textContent = sfxEnabled ? 'SFX Aktif' : 'SFX Hening';
            if (icon) {
                icon.className = sfxEnabled
                    ? 'fa-solid fa-volume-high text-[var(--gold)] sfx-icon'
                    : 'fa-solid fa-volume-xmark text-white/40 sfx-icon';
            }
        });
    }

    updateSfxButtons();
    document.querySelectorAll('[data-sfx-toggle]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            window.toggleSfx();
        });
    });

    // Subtle audio click for interactive buttons
    document.addEventListener('click', (e) => {
        if (e.target.closest('button, .btn-primary, .btn-gold, .btn-outline, .doc-tab, .u-tab')) {
            window.playMicroSfx('click');
        }
    });
}

/* =========================================================================
   7. COMMAND PALETTE (CTRL + K / ⌘K)
   ========================================================================= */
function initCommandPalette() {
    const paletteModal = document.getElementById('command-palette-modal');
    const searchInput = document.getElementById('cmd-search-input');
    const resultsContainer = document.getElementById('cmd-results');
    if (!paletteModal || !searchInput || !resultsContainer) return;

    const quickLinks = [
        { title: 'Beranda (Landing Page)', cat: 'Navigasi', url: '/', icon: 'fa-house', kbd: 'H' },
        { title: 'Laboratorium PAI (Ikhtisar)', cat: 'Laboratorium PAI', url: '/laboratorium-pai', icon: 'fa-flask', kbd: 'L' },
        { title: 'Profil & Dewan Guru PAI', cat: 'Laboratorium PAI', url: '/laboratorium-pai/profil', icon: 'fa-users' },
        { title: 'Modul Pembelajaran PAI (PDF)', cat: 'Laboratorium PAI', url: '/laboratorium-pai/modul', icon: 'fa-book-open' },
        { title: 'Tugas Siswa & Google Classroom', cat: 'Laboratorium PAI', url: '/laboratorium-pai/tugas', icon: 'fa-list-check' },
        { title: 'Perpustakaan Digital TSAQIB', cat: 'Katalog', url: '/perpustakaan', icon: 'fa-book-bookmark', kbd: 'P' },
        { title: 'Warta, Buletin & Dokumentasi', cat: 'Informasi', url: '/info', icon: 'fa-newspaper' },
        { title: 'Komunitas FSI (7 Circle)', cat: 'Komunitas', url: '/komunitas/semua', icon: 'fa-circle-nodes', kbd: 'K' },
        { title: 'Tentang Tsaqib', cat: 'Informasi', url: '/tentang', icon: 'fa-circle-info' },
        { title: 'Panduan TSAQIB', cat: 'Informasi', url: '/panduan', icon: 'fa-circle-question' },
        { title: 'Open Recruitment Kelas X', cat: 'Pendaftaran', url: '/open-recruitment', icon: 'fa-user-plus', kbd: 'O' },
        { title: 'Prototype Desain Figma', cat: 'Eksternal', url: 'https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv', icon: 'fa-figma', external: true },
        { title: 'Salin Tautan Website TSAQIB', cat: 'Aksi Cepat', action: 'copy-url', icon: 'fa-copy' },
    ];

    let activeIndex = 0;
    let filteredItems = [...quickLinks];

    function renderResults() {
        resultsContainer.innerHTML = '';
        if (!filteredItems.length) {
            resultsContainer.innerHTML = `
                <div class="p-8 text-center text-white/40 text-xs">
                    <i class="fa-solid fa-magnifying-glass text-xl mb-2 block text-white/20"></i>
                    Tidak ada hasil yang cocok dengan pencarian Anda.
                </div>
            `;
            return;
        }

        filteredItems.forEach((item, idx) => {
            const isSelected = idx === activeIndex;
            const el = document.createElement('a');
            el.href = item.url || '#';
            if (item.external) el.target = '_blank';
            el.className = `flex items-center justify-between p-3 rounded-xl transition-colors cursor-pointer ${
                isSelected
                    ? 'bg-[var(--gold)]/15 text-[var(--gold)] border border-[var(--gold)]/30'
                    : 'text-white/80 hover:bg-white/5 border border-transparent'
            }`;
            el.setAttribute('data-cmd-idx', idx);

            el.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg ${isSelected ? 'bg-[var(--gold)] text-[#10140F]' : 'bg-white/10 text-white/70'} flex items-center justify-center text-xs shrink-0 transition-colors">
                        <i class="fa-solid ${item.icon}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-semibold truncate ${isSelected ? 'text-[var(--gold)]' : 'text-[var(--cream)]'}">${item.title}</div>
                        <div class="text-[10px] text-white/40">${item.cat}</div>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    ${item.kbd ? `<span class="cmd-kbd">${item.kbd}</span>` : ''}
                    <i class="fa-solid fa-chevron-right text-[10px] text-white/20"></i>
                </div>
            `;

            el.addEventListener('click', (e) => {
                if (item.action === 'copy-url') {
                    e.preventDefault();
                    navigator.clipboard.writeText(window.location.origin);
                    window.showToast('Tautan website TSAQIB disalin!', 'gold');
                    closePalette();
                } else if (!item.external) {
                    closePalette();
                }
            });

            resultsContainer.appendChild(el);
        });
    }

    function openPalette() {
        paletteModal.classList.remove('hidden');
        paletteModal.classList.add('flex');
        searchInput.value = '';
        filteredItems = [...quickLinks];
        activeIndex = 0;
        renderResults();
        setTimeout(() => searchInput.focus(), 50);
        document.body.classList.add('overflow-hidden');
    }

    function closePalette() {
        paletteModal.classList.add('hidden');
        paletteModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    window.openCommandPalette = openPalette;
    window.closeCommandPalette = closePalette;

    searchInput.addEventListener('input', () => {
        const q = searchInput.value.toLowerCase().trim();
        if (!q) {
            filteredItems = [...quickLinks];
        } else {
            filteredItems = quickLinks.filter(item =>
                item.title.toLowerCase().includes(q) || item.cat.toLowerCase().includes(q)
            );
        }
        activeIndex = 0;
        renderResults();
    });

    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (filteredItems.length) {
                activeIndex = (activeIndex + 1) % filteredItems.length;
                renderResults();
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (filteredItems.length) {
                activeIndex = (activeIndex - 1 + filteredItems.length) % filteredItems.length;
                renderResults();
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            const selected = filteredItems[activeIndex];
            if (selected) {
                if (selected.action === 'copy-url') {
                    navigator.clipboard.writeText(window.location.origin);
                    window.showToast('Tautan website TSAQIB disalin!', 'gold');
                    closePalette();
                } else if (selected.external) {
                    window.open(selected.url, '_blank');
                    closePalette();
                } else {
                    window.location.href = selected.url;
                }
            }
        } else if (e.key === 'Escape') {
            closePalette();
        }
    });

    // Triggers
    document.querySelectorAll('[data-open-cmd]').forEach(btn => {
        btn.addEventListener('click', openPalette);
    });

    paletteModal.addEventListener('click', (e) => {
        if (e.target === paletteModal) closePalette();
    });
}

/* =========================================================================
   8. KEYBOARD SHORTCUTS & HELP OVERLAY
   ========================================================================= */
function initKeyboardShortcuts() {
    const shortcutsModal = document.getElementById('shortcuts-help-modal');

    window.toggleShortcutsHelp = function () {
        if (!shortcutsModal) return;
        shortcutsModal.classList.toggle('hidden');
        shortcutsModal.classList.toggle('flex');
    };

    document.addEventListener('keydown', (e) => {
        const isInput = ['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName);

        // Command Palette: Ctrl+K / Cmd+K or Slash (when not in input)
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            window.openCommandPalette();
            return;
        }
        if (e.key === '/' && !isInput) {
            e.preventDefault();
            window.openCommandPalette();
            return;
        }

        // Shortcuts modal: '?'
        if (e.key === '?' && !isInput) {
            e.preventDefault();
            window.toggleShortcutsHelp();
            return;
        }

        // Close on Escape
        if (e.key === 'Escape') {
            if (shortcutsModal && !shortcutsModal.classList.contains('hidden')) {
                shortcutsModal.classList.add('hidden');
                shortcutsModal.classList.remove('flex');
            }
            return;
        }

        // Quick page hotkeys (only when not in input and without modifiers)
        if (!isInput && !e.ctrlKey && !e.metaKey && !e.altKey) {
            const key = e.key.toLowerCase();
            if (key === 'h') window.location.href = '/';
            if (key === 'l') window.location.href = '/laboratorium-pai';
            if (key === 'p') window.location.href = '/perpustakaan';
            if (key === 'k') window.location.href = '/komunitas/semua';
            if (key === 'o') window.location.href = '/open-recruitment';
        }
    });
}

/* =========================================================================
   8. CARD SPOTLIGHT HOVER ENGINE (UIVERSE MOUSE TRACKER)
   ========================================================================= */
function initCardSpotlights() {
    let ticking = false;
    document.addEventListener('mousemove', (e) => {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(() => {
            const card = e.target.closest('.card-spotlight, .tsaqib-card, .tsaqib-card-interactive, .community-card, .landing-photo-card, .card-program');
            if (card) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            }
            ticking = false;
        });
    }, { passive: true });
}

/* =========================================================================
   9. UPVOTE MICRO-ANIMATION & PARTICLE BURST
   ========================================================================= */
window.triggerUpvoteAnimation = function (btn, isUp) {
    if (!btn) return;
    btn.classList.remove('vote-pop');
    void btn.offsetWidth; // trigger DOM reflow for restart
    btn.classList.add('vote-pop');

    if (isUp) {
        for (let i = 0; i < 5; i++) {
            const p = document.createElement('span');
            p.className = 'vote-particle';
            const vx = (Math.random() - 0.5) * 36;
            const vy = -16 - Math.random() * 22;
            p.style.setProperty('--vx', `${vx}px`);
            p.style.setProperty('--vy', `${vy}px`);
            p.style.left = `${btn.offsetWidth / 2 + (Math.random() - 0.5) * 10}px`;
            p.style.top = `${btn.offsetHeight / 2}px`;
            btn.appendChild(p);
            setTimeout(() => p.remove(), 700);
        }
    }
};

/* =========================================================================
   10. INK RIPPLE TOUCH/CLICK ENGINE
   ========================================================================= */
function initInkRipple() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-primary, .cta-primary, .btn-gold, .btn-outline, .btn-gold-outline, .btn-download, [data-ripple]');
        if (!btn) return;

        const rect = btn.getBoundingClientRect();
        const ripple = document.createElement('span');
        ripple.className = 'ripple-ink';
        const diameter = Math.max(rect.width, rect.height);
        const radius = diameter / 2;

        ripple.style.width = ripple.style.height = `${diameter}px`;
        ripple.style.left = `${e.clientX - rect.left - radius}px`;
        ripple.style.top = `${e.clientY - rect.top - radius}px`;

        btn.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
}
