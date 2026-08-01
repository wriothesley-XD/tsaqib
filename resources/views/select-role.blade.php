<!-- resources/views/select-role.blade.php -->
@php
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
            'deskripsi_singkat' => 'Wadah pengembangan bakat dan prestasi siswa (Forum Olimpiade).',
            'peran' => 'Commander, Supporter',
            'icon' => 'fa-solid fa-award',
            'image' => asset('images/icon/young-stars.jpg'),
        ],
        [
            'slug' => 'grow-up',
            'nama' => 'Grow Up',
            'deskripsi_singkat' => 'Berfokus pada pembentukan karakter remaja ke arah yang lebih positif.',
            'peran' => 'Commander, Support',
            'icon' => 'fa-solid fa-seedling',
            'image' => asset('images/icon/grow-up.jpg'),
        ],
        [
            'slug' => 'blitzsport',
            'nama' => 'Blitzsport',
            'deskripsi_singkat' => 'Wadah aktivitas olahraga Gen Z untuk mencetak fisik sehat dan kuat.',
            'peran' => 'Commander, Support',
            'icon' => 'fa-solid fa-dumbbell',
            'image' => asset('images/icon/blitzsport.jpg'),
        ],
        [
            'slug' => 'gofam',
            'nama' => 'Gofam',
            'deskripsi_singkat' => 'Gathering of Ambitious, tempat pemuda saling belajar dan berbagi wawasan.',
            'peran' => 'Commander, Supporter',
            'icon' => 'fa-solid fa-users',
            'image' => asset('images/icon/gofam.jpg'),
        ],
        [
            'slug' => 'leora',
            'nama' => 'Leora (Game)',
            'deskripsi_singkat' => 'Komunitas gamers untuk menjadikan game sebagai media positif.',
            'peran' => 'Leora Commander, Subcomunity, Support',
            'icon' => 'fa-solid fa-gamepad',
            'image' => asset('images/icon/leora.jpg'),
        ],
        [
            'slug' => 'mushou',
            'nama' => 'MuShou (Jejepangan)',
            'deskripsi_singkat' => 'Muslim Shounen, wadah peminat budaya Jejepangan & media dakwah.',
            'peran' => 'Commander, Support',
            'icon' => 'fa-solid fa-yin-yang',
            'image' => asset('images/icon/mushou.jpg'),
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Minat Komunitas Anda - TSAQIB SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .role-slide-viewport {
            overflow: hidden;
            width: 100%;
            padding: 1.5rem 0 2.5rem 0; 
        }
        .role-slide-track {
            display: flex;
            transition: transform 400ms cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }
        .role-slide-page {
            flex: 0 0 100%;
            width: 100%;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col justify-between p-4 sm:p-8">

    <div class="max-w-4xl mx-auto w-full my-auto text-center">

        <!-- Top Header -->
        <span class="inline-block px-3 py-1 rounded-full bg-[#01795F]/10 text-[#01795F] text-xs font-semibold uppercase tracking-wider mb-3">
            Langkah Utama • Pemilihan Komunitas
        </span>
        <h1 class="text-2xl sm:text-4xl font-bold text-slate-900 tracking-tight">
            Pilih Minat Komunitas Utama Anda
        </h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-2 max-w-lg mx-auto">
            Pilihan Anda akan disimpan dan langsung mengarahkan Anda ke linimasa komunitas tersebut.
        </p>

        <!-- ROLE / KOMUNITAS SELECTION FORM -->
        <form action="{{ route('select-role.store') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="community_slug" id="communitySlugInput" value="">

            <!-- Slide Viewport -->
            <div class="role-slide-viewport mx-auto w-full">
                <div id="roleSlideTrack" class="role-slide-track">
                    {{-- diisi via JS --}}
                </div>
            </div>

            <!-- Pagination Controls -->
            <div id="roleNav" class="flex items-center justify-center gap-4 mt-2">
                <button type="button" id="prevPageBtn"
                        class="w-9 h-9 rounded-full border border-slate-300 flex items-center justify-center text-slate-500 
                               hover:border-[#01795F] hover:bg-[#01795F] hover:text-white transition duration-200
                               disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-slate-500 disabled:hover:border-slate-300">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>

                <div id="roleDots" class="flex items-center gap-2"></div>

                <button type="button" id="nextPageBtn"
                        class="w-9 h-9 rounded-full border border-slate-300 flex items-center justify-center text-slate-500 
                               hover:border-[#01795F] hover:bg-[#01795F] hover:text-white transition duration-200
                               disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-slate-500 disabled:hover:border-slate-300">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>

            <!-- Continue Button -->
            <button type="submit" id="continueBtn" disabled
                    class="mt-6 w-full sm:w-64 mx-auto block py-3 rounded-full bg-slate-900 text-white text-sm font-semibold shadow-lg shadow-slate-900/20
                           disabled:opacity-40 disabled:shadow-none disabled:cursor-not-allowed 
                           enabled:hover:bg-[#01795F] enabled:hover:shadow-[#01795F]/30 enabled:-translate-y-0.5 transition-all duration-200">
                Lanjutkan ke Komunitas
            </button>
        </form>

    </div>

    <div class="text-center text-xs text-slate-400 py-4 mt-4">
        &copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi
    </div>

    <script>
        const daftarKomunitas = @json($daftarKomunitas);
        const PER_PAGE = 3; 

        function buildPages(items, perPage) {
            const chunks = [];
            for (let i = 0; i < items.length; i += perPage) {
                chunks.push(items.slice(i, i + perPage));
            }
            return chunks;
        }

        const pages = buildPages(daftarKomunitas, PER_PAGE);

        let currentPage = 0;
        let selectedSlug = null;

        const track = document.getElementById('roleSlideTrack');
        const dotsWrap = document.getElementById('roleDots');
        const continueBtn = document.getElementById('continueBtn');
        const slugInput = document.getElementById('communitySlugInput');
        const prevBtn = document.getElementById('prevPageBtn');
        const nextBtn = document.getElementById('nextPageBtn');
        const roleNav = document.getElementById('roleNav');

        function iconFor(k) {
            return k.icon ? `<i class="${k.icon}"></i>` : '<i class="fa-solid fa-layer-group"></i>';
        }

        function buildCard(k) {
            const card = document.createElement('div');
            card.dataset.slug = k.slug;
            
            // Lebar dikecilkan menjadi w-[200px] dan menggunakan rasio standar kartu poker aspect-[2.5/3.5]
            card.className = `role-card relative cursor-pointer bg-white border-2 rounded-[14px] p-2 text-left transition-all duration-300 group border-slate-200 shrink-0 w-[200px] flex flex-col aspect-[2.5/3.5] hover:-translate-y-3 hover:shadow-2xl hover:shadow-[#01795F]/20 mx-auto sm:mx-0`;

            card.innerHTML = `
                <!-- Badge Check (Aktif jika dipilih) - Ukuran sedikit diperkecil -->
                <div class="role-check-wrap absolute -top-2.5 -right-2.5 w-7 h-7 rounded-full bg-[#01795F] text-white flex items-center justify-center text-xs shadow-md transition-all duration-300 opacity-0 scale-0 z-20">
                    <i class="fa-solid fa-check"></i>
                </div>

                <!-- Bagian Gambar Kartu (Tinggi proporsi 50% untuk kesan vertikal) -->
                <div class="w-full h-[50%] rounded-t-xl rounded-b-md overflow-hidden relative border border-slate-100 bg-slate-100 mb-2.5 shrink-0">
                    <img src="${k.image}" alt="${k.nama}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                    
                    <div class="absolute bottom-1.5 right-1.5 bg-white/95 backdrop-blur w-7 h-7 rounded-full flex items-center justify-center text-[#01795F] shadow-sm text-xs z-10 transition-colors">
                        ${iconFor(k)}
                    </div>
                </div>

                <!-- Konten Teks Kartu (Ukuran font dikecilkan menyesuaikan lebar kartu) -->
                <div class="flex flex-col flex-grow px-1 pb-1">
                    <h3 class="role-title font-bold text-slate-900 text-[15px] leading-tight mb-1 transition">${k.nama}</h3>
                    
                    <p class="text-[10px] text-slate-500 flex-grow line-clamp-4 leading-snug">
                        ${k.deskripsi_singkat || ''}
                    </p>

                    <!-- Info Peran di Bagian Paling Bawah -->
                    <div class="pt-1.5 mt-1.5 border-t border-slate-100 shrink-0">
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Peran:</span>
                        <span class="text-[10px] font-semibold text-[#01795F] line-clamp-2 leading-tight">${k.peran || '-'}</span>
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
                // Mengurangi gap agar kartu yang lebih kecil jaraknya proporsional
                wrapEl.className = 'flex flex-wrap sm:flex-nowrap justify-center items-center gap-3 sm:gap-6 w-full max-w-4xl mx-auto px-4';

                items.forEach(k => wrapEl.appendChild(buildCard(k)));
                pageEl.appendChild(wrapEl);
                track.appendChild(pageEl);
            });
        }

        function updateSelectedStyles() {
            document.querySelectorAll('.role-card').forEach(card => {
                const isSelected = card.dataset.slug === selectedSlug;
                const checkWrap = card.querySelector('.role-check-wrap');
                const title = card.querySelector('.role-title');

                card.classList.toggle('border-[#01795F]', isSelected);
                card.classList.toggle('ring-[3px]', isSelected);
                card.classList.toggle('ring-[#01795F]/30', isSelected);
                card.classList.toggle('border-slate-200', !isSelected);
                card.classList.toggle('shadow-2xl', isSelected);
                card.classList.toggle('shadow-[#01795F]/20', isSelected);
                card.classList.toggle('-translate-y-3', isSelected);

                checkWrap.classList.toggle('opacity-0', !isSelected);
                checkWrap.classList.toggle('scale-0', !isSelected);
                checkWrap.classList.toggle('opacity-100', isSelected);
                checkWrap.classList.toggle('scale-100', isSelected);

                title.classList.toggle('text-[#01795F]', isSelected);
            });
        }

        function renderDots() {
            dotsWrap.innerHTML = '';
            pages.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = `h-1.5 rounded-full transition-all duration-300 ${
                    i === currentPage ? 'w-6 bg-[#01795F]' : 'w-1.5 bg-slate-200 hover:bg-slate-300'
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

        prevBtn.addEventListener('click', () => goToPage(currentPage - 1));
        nextBtn.addEventListener('click', () => goToPage(currentPage + 1));

        buildTrack();
        roleNav.classList.toggle('hidden', pages.length <= 1);
        goToPage(0);
    </script>

</body>
</html>