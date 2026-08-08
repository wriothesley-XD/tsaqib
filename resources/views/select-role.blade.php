<!-- resources/views/select-role.blade.php -->
@php
    $pageTitle = 'Pilih Minat Komunitas - TSAQIB SMAN 1 Bukittinggi';
    $daftarKomunitas = [
        [
            'slug' => 'tahfidz',
            'nama' => 'Tahfidz',
            'deskripsi_singkat' => "Program menghafal dan menjaga hafalan al-Qur'an.",
            'peran' => 'Commander, Support',
            'icon' => 'fa-solid fa-book-quran',
            'image' => asset('images/icon/tahfidz.jpg')
        ],
        [
            'slug' => 'young-stars',
            'nama' => 'The Young Stars',
            'deskripsi_singkat' => 'Wadah pengembangan bakat dan prestasi siswa.',
            'peran' => 'Commander, Supporter',
            'icon' => 'fa-solid fa-award',
            'image' => asset('images/icon/young-stars.jpg'),
        ],
        [
            'slug' => 'grow-up',
            'nama' => 'Grow Up',
            'deskripsi_singkat' => 'Berfokus pada pembentukan karakter remaja.',
            'peran' => 'Commander, Support',
            'icon' => 'fa-solid fa-seedling',
            'image' => asset('images/icon/grow-up.jpg'),
        ],
        [
            'slug' => 'blitzsport',
            'nama' => 'Blitzsport',
            'deskripsi_singkat' => 'Wadah aktivitas olahraga Gen Z mencetak fisik sehat.',
            'peran' => 'Commander, Support',
            'icon' => 'fa-solid fa-dumbbell',
            'image' => asset('images/icon/blitzsport.jpg'),
        ],
        [
            'slug' => 'gofam',
            'nama' => 'Gofam',
            'deskripsi_singkat' => 'Tempat pemuda saling belajar dan berbagi wawasan.',
            'peran' => 'Commander, Supporter',
            'icon' => 'fa-solid fa-users',
            'image' => asset('images/icon/gofam.jpg'),
        ],
        [
            'slug' => 'leora',
            'nama' => 'Leora (Game)',
            'deskripsi_singkat' => 'Komunitas gamers menjadikan game media positif.',
            'peran' => 'Commander, Subcomunity, Support',
            'icon' => 'fa-solid fa-gamepad',
            'image' => asset('images/icon/leora.jpg'),
        ],
        [
            'slug' => 'mushou',
            'nama' => 'MuShou (Jejepangan)',
            'deskripsi_singkat' => 'Muslim Shounen, peminat budaya Jejepangan.',
            'peran' => 'Commander, Support',
            'icon' => 'fa-solid fa-yin-yang',
            'image' => asset('images/icon/mushou.jpg'),
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
    <style>
        .role-slide-viewport {
            overflow: hidden;
            width: 100%;
        }
        .role-slide-track {
            display: flex;
            transition: transform 500ms cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }
        .role-slide-page {
            flex: 0 0 100%;
            width: 100%;
        }
    </style>
</head>
<body class="text-[var(--cream)] font-sans antialiased h-[100dvh] flex flex-col justify-between p-4 sm:p-6 overflow-hidden">

    <div class="max-w-4xl mx-auto w-full flex flex-col items-center justify-center h-full text-center">

        <!-- Top Header -->
        <span class="inline-block px-3 py-1 rounded-full bg-[#01795F]/15 text-[#3fd6b0] text-[10px] sm:text-xs font-semibold uppercase tracking-wider mb-2">
            Langkah Utama • Pemilihan Komunitas
        </span>
        <h1 class="text-xl sm:text-3xl font-bold text-[var(--cream)] tracking-tight leading-tight">
            Pilih Minat Komunitas Utama Anda
        </h1>
        <p class="text-white/50 text-[11px] sm:text-xs mt-1.5 max-w-lg mx-auto">
            Pilihan Anda akan disimpan dan langsung mengarahkan Anda ke linimasa komunitas tersebut.
        </p>

        <!-- ROLE / KOMUNITAS SELECTION FORM -->
        <form action="{{ route('select-role.store') }}" method="POST" class="mt-4 w-full flex flex-col items-center">
            @csrf
            <input type="hidden" name="community_slug" id="communitySlugInput" value="">

            <!-- Slide Viewport -->
            <div class="role-slide-viewport mx-auto w-full">
                <div id="roleSlideTrack" class="role-slide-track">
                    {{-- diisi via JS secara dinamis --}}
                </div>
            </div>

            <!-- Pagination Controls -->
            <div id="roleNav" class="flex items-center justify-center gap-3 mt-1">
                <button type="button" id="prevPageBtn"
                        class="w-8 h-8 rounded-full border border-white/15 flex items-center justify-center text-white/60
                               hover:border-[#01795F] hover:bg-[#01795F] hover:text-white transition duration-200
                               disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-white/60 disabled:hover:border-white/15">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </button>

                <div id="roleDots" class="flex items-center gap-1.5"></div>

                <button type="button" id="nextPageBtn"
                        class="w-8 h-8 rounded-full border border-white/15 flex items-center justify-center text-white/60
                               hover:border-[#01795F] hover:bg-[#01795F] hover:text-white transition duration-200
                               disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-white/60 disabled:hover:border-white/15">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>

            <!-- Continue Button -->
            <button type="submit" id="continueBtn" disabled
                    class="mt-4 w-44 block py-2 rounded-full bg-[#01795F] text-white text-xs font-semibold shadow-md shadow-[#01795F]/30
                           disabled:opacity-40 disabled:shadow-none disabled:cursor-not-allowed
                           enabled:hover:bg-[#3F704D] enabled:-translate-y-0.5 transition-all duration-200">
                Lanjutkan ke Komunitas
            </button>
        </form>
    </div>

    <!-- Footer compact -->
    <div class="text-center text-[10px] text-white/40 shrink-0 mt-2 pb-1">
        &copy; {{ date('Y') }} TSAQIB • FSI SMAN 1 Bukittinggi
    </div>

    <!-- LOGIKA JAVASCRIPT SLIDER RESPONSIVE -->
    <script>
        const daftarKomunitas = @json($daftarKomunitas);

        function getItemsPerPage() {
            if (window.innerWidth < 640) return 1;
            if (window.innerWidth < 1024) return 2;
            return 3;
        }

        let PER_PAGE = getItemsPerPage();
        let pages = [];
        let currentPage = 0;
        let selectedSlug = null;

        const track = document.getElementById('roleSlideTrack');
        const dotsWrap = document.getElementById('roleDots');
        const continueBtn = document.getElementById('continueBtn');
        const slugInput = document.getElementById('communitySlugInput');
        const prevBtn = document.getElementById('prevPageBtn');
        const nextBtn = document.getElementById('nextPageBtn');
        const roleNav = document.getElementById('roleNav');

        function buildPages(items, perPage) {
            const chunks = [];
            for (let i = 0; i < items.length; i += perPage) {
                chunks.push(items.slice(i, i + perPage));
            }
            return chunks;
        }

        function iconFor(k) {
            return k.icon ? `<i class="${k.icon}"></i>` : '<i class="fa-solid fa-layer-group"></i>';
        }

        function buildCard(k) {
            const card = document.createElement('div');
            card.dataset.slug = k.slug;

            card.className = `role-card relative cursor-pointer bg-white/[.04] border-2 rounded-[16px] p-2 text-left transition-all duration-300 group border-white/10 shrink-0 w-[180px] sm:w-[200px] flex flex-col aspect-[2.5/3.4] hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#01795F]/10 mx-auto sm:mx-0`;

            card.innerHTML = `
                <div class="w-full h-[50%] rounded-xl overflow-hidden relative border border-white/10 bg-white/5 mb-2 shrink-0">
                    <img src="${k.image}" alt="${k.nama}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">

                    <div class="absolute bottom-1.5 right-1.5 bg-white w-6 h-6 rounded-full flex items-center justify-center text-[#01795F] shadow-sm text-[10px] z-10 transition-colors">
                        ${iconFor(k)}
                    </div>
                </div>

                <div class="flex flex-col flex-grow px-1 pb-1">
                    <h3 class="role-title font-bold text-[var(--cream)] text-sm leading-tight mb-1 transition">${k.nama}</h3>

                    <p class="text-[9px] text-white/50 flex-grow line-clamp-3 leading-snug">
                        ${k.deskripsi_singkat || ''}
                    </p>

                    <div class="pt-1.5 mt-1 shrink-0">
                        <span class="text-[8px] font-bold text-white/40 uppercase tracking-widest block mb-0.5">Peran:</span>
                        <span class="text-[9px] font-bold text-[#3fd6b0] line-clamp-2 leading-tight">${k.peran || '-'}</span>
                    </div>
                </div>
            `;

            card.addEventListener('click', () => {
                selectedSlug = k.slug;
                slugInput.value = k.slug;
                continueBtn.disabled = false;
                updateSelectedStyles();
            });

            return card;
        }

        function buildTrack() {
            track.innerHTML = '';
            pages.forEach(items => {
                const pageEl = document.createElement('div');
                pageEl.className = 'role-slide-page';

                const wrapEl = document.createElement('div');
                wrapEl.className = 'flex justify-center items-center gap-4 sm:gap-6 w-full max-w-4xl mx-auto px-4 py-3';

                items.forEach(k => wrapEl.appendChild(buildCard(k)));
                pageEl.appendChild(wrapEl);
                track.appendChild(pageEl);
            });
            updateSelectedStyles();
        }

        function updateSelectedStyles() {
            document.querySelectorAll('.role-card').forEach(card => {
                const isSelected = card.dataset.slug === selectedSlug;
                const title = card.querySelector('.role-title');

                card.classList.toggle('border-[#01795F]', isSelected);
                card.classList.toggle('ring-[3px]', isSelected);
                card.classList.toggle('ring-[#01795F]/20', isSelected);
                card.classList.toggle('border-white/10', !isSelected);
                card.classList.toggle('shadow-2xl', isSelected);
                card.classList.toggle('shadow-[#01795F]/20', isSelected);
                card.classList.toggle('-translate-y-1.5', isSelected);

                title.classList.toggle('text-[#3fd6b0]', isSelected);
            });
        }

        function renderDots() {
            dotsWrap.innerHTML = '';
            pages.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = `h-1.5 rounded-full transition-all duration-300 ${
                    i === currentPage ? 'w-5 bg-[#01795F]' : 'w-1.5 bg-white/15 hover:bg-white/25'
                }`;
                dot.addEventListener('click', () => goToPage(i));
                dotsWrap.appendChild(dot);
            });
        }

        function goToPage(index) {
            currentPage = Math.max(0, Math.min(index, pages.length - 1));
            track.style.transform = `translateX(-${currentPage * 100}%)`;
            renderDots();
            prevBtn.disabled = currentPage === 0;
            nextBtn.disabled = currentPage === pages.length - 1;
        }

        function initSlider() {
            PER_PAGE = getItemsPerPage();
            pages = buildPages(daftarKomunitas, PER_PAGE);

            if(currentPage >= pages.length) {
                currentPage = Math.max(0, pages.length - 1);
            }

            buildTrack();
            renderDots();
            roleNav.classList.toggle('hidden', pages.length <= 1);
            goToPage(currentPage);
        }

        initSlider();

        prevBtn.addEventListener('click', () => goToPage(currentPage - 1));
        nextBtn.addEventListener('click', () => goToPage(currentPage + 1));

        window.addEventListener('resize', () => {
            if (PER_PAGE !== getItemsPerPage()) {
                initSlider();
            }
        });
    </script>

</body>
</html>
