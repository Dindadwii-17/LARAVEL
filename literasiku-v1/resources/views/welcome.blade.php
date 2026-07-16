<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PustakaDigital - Portal Literasiku</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        sapphire: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            500: '#3B82F6',
                            600: '#2563EB',
                            700: '#1D4ED8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col pb-16 md:pb-0">

    <!-- Top Header Bar -->
    <header class="sticky top-0 z-40 w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="bg-blue-600 p-2 rounded-xl text-white shadow-lg shadow-blue-500/20">
                <i class="fas fa-book-open text-lg"></i>
            </div>
            <div>
                <h1 class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">PustakaDigital</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Portal Pratinjau Literasiku</p>
            </div>
        </div>

        <!-- Right Action Bar -->
        <div class="flex items-center gap-4">
            <!-- Theme Switcher -->
            <button onclick="toggleTheme()" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:scale-105 transition-transform" title="Ubah Tema">
                <i id="theme-icon" class="fas fa-moon"></i>
            </button>

            <!-- Login/Register (Desktop) -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
                        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition-colors flex items-center gap-2 shadow-lg shadow-emerald-500/10">
                            <i class="fas fa-chart-line"></i> Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/10">
                            <i class="fas fa-house-user"></i> Dashboard Member
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold rounded-xl text-sm transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-colors shadow-lg shadow-blue-500/15">
                        Daftar Anggota
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Navigation Tabs (Desktop Top) -->
    <nav class="hidden md:flex bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-6 py-2">
        <div class="flex gap-4">
            <button onclick="switchTab('beranda')" id="desktop-tab-beranda" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition-colors bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                <i class="fas fa-home mr-2"></i>Beranda
            </button>
            <button onclick="switchTab('jelajahi')" id="desktop-tab-jelajahi" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition-colors text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                <i class="fas fa-compass mr-2"></i>Jelajahi
            </button>
            <button onclick="switchTab('profil')" id="desktop-tab-profil" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition-colors text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                <i class="fas fa-user mr-2"></i>Profil
            </button>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <main class="flex-grow w-full max-w-7xl mx-auto p-4 md:p-6 overflow-hidden">

        <!-- TAB 1: BERANDA -->
        <section id="tab-content-beranda" class="tab-panel block space-y-6">
            <!-- Hero Welcome Card -->
            <div class="bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-xl shadow-blue-500/10">
                <div class="relative z-10 space-y-4 max-w-xl">
                    <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Perpustakaan Pintar</span>
                    <h2 class="text-2xl md:text-3xl font-extrabold leading-tight">Perkaya Pengetahuan Anda Tanpa Batas</h2>
                    <p class="text-sm text-blue-100 leading-relaxed">Telusuri ribuan koleksi buku fisik dan e-book digital berkualitas di mana saja, kapan saja secara instan.</p>
                </div>
                <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl pointer-events-none">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>

            <!-- Sponsor Banner Gramedia -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex flex-col sm:flex-row gap-4 items-center justify-between shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-950 dark:text-white">Koleksi Terbaru Gramedia Digital</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Dapatkan akses ribuan e-book pilihan penerbit terkemuka setelah terdaftar.</p>
                    </div>
                </div>
                <button onclick="showRestrictedModal('E-Book')" class="w-full sm:w-auto px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-lg text-xs transition-colors shrink-0">
                    Akses Premium
                </button>
            </div>

            <!-- Real Statistics Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500/10 text-blue-500 rounded-xl flex items-center justify-center"><i class="fas fa-book"></i></div>
                    <div>
                        <span class="text-lg font-bold block text-slate-900 dark:text-white">{{ $totalPhysicalBooks }}</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Buku Fisik</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center"><i class="fas fa-file-pdf"></i></div>
                    <div>
                        <span class="text-lg font-bold block text-slate-900 dark:text-white">{{ $totalEbooks }}</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">E-Book</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center"><i class="fas fa-clock"></i></div>
                    <div>
                        <span class="text-lg font-bold block text-slate-900 dark:text-white">08:00 - 16:00</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Jam Pelayanan</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <span class="text-lg font-bold block text-slate-900 dark:text-white">Gedung Pusat</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Lokasi Layanan</span>
                    </div>
                </div>
            </div>

            <!-- Popular Books List -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-extrabold text-base tracking-tight">🔥 Rekomendasi Buku Populer</h3>
                    <button onclick="switchTab('jelajahi')" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">Lihat Semua</button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach ($books->take(5) as $book)
                        <div onclick="openBookDetail({{ json_encode($book) }})" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-3 flex flex-col cursor-pointer hover:border-blue-500 hover:-translate-y-1 transition-all">
                            <div class="aspect-[3/4] bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mb-3 relative overflow-hidden">
                                <span class="text-4xl select-none" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">{{ $book->cover ?? '📚' }}</span>
                                @if($book->is_ebook)
                                    <span class="absolute top-2 right-2 bg-rose-500 text-white text-[9px] font-bold px-2 py-0.5 rounded">E-BOOK</span>
                                @else
                                    <span class="absolute top-2 right-2 bg-amber-500 text-slate-950 text-[9px] font-bold px-2 py-0.5 rounded">FISIK</span>
                                @endif
                            </div>
                            <h4 class="font-bold text-xs truncate text-slate-900 dark:text-white">{{ $book->title }}</h4>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-1">Oleh: {{ $book->author }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- TAB 2: JELAJAHI -->
        <section id="tab-content-jelajahi" class="tab-panel hidden space-y-6">
            <!-- Search & Filters -->
            <div class="space-y-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="catalog-search" oninput="filterCatalog()" class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm outline-none focus:border-blue-500 focus:bg-white transition-all shadow-sm" placeholder="Cari berdasarkan judul, penulis, atau kategori...">
                </div>
                <!-- Categories Pills -->
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <button onclick="filterCategory('all')" id="cat-all" class="cat-pill-btn px-4 py-1.5 rounded-full text-xs font-bold bg-blue-600 text-white transition-all">
                        Semua
                    </button>
                    <button onclick="filterCategory('E-Book')" id="cat-ebook" class="cat-pill-btn px-4 py-1.5 rounded-full text-xs font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 transition-all hover:bg-slate-50">
                        E-Book
                    </button>
                    <button onclick="filterCategory('Fisik')" id="cat-fisik" class="cat-pill-btn px-4 py-1.5 rounded-full text-xs font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 transition-all hover:bg-slate-50">
                        Buku Fisik
                    </button>
                </div>
            </div>

            <!-- Books Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4" id="catalog-grid">
                @foreach ($books as $book)
                    <div class="book-card-item bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-3 flex flex-col cursor-pointer hover:border-blue-500 hover:-translate-y-1 transition-all" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author) }}" data-category="{{ $book->is_ebook ? 'ebook' : 'fisik' }}" onclick="openBookDetail({{ json_encode($book) }})">
                        <div class="aspect-[3/4] bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mb-3 relative overflow-hidden">
                            @if($book->is_ebook)
                                <i class="fas fa-file-pdf text-4xl text-rose-500"></i>
                                <span class="absolute top-2 right-2 bg-rose-500 text-white text-[9px] font-bold px-2 py-0.5 rounded">E-BOOK</span>
                            @else
                                <i class="fas fa-book text-4xl text-blue-500"></i>
                                <span class="absolute top-2 right-2 bg-amber-500 text-slate-950 text-[9px] font-bold px-2 py-0.5 rounded">FISIK</span>
                            @endif
                        </div>
                        <h4 class="font-bold text-xs truncate text-slate-900 dark:text-white">{{ $book->title }}</h4>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-1">Oleh: {{ $book->author }}</p>
                    </div>
                @endforeach
            </div>
        </section>



        <!-- TAB 5: PROFIL -->
        <section id="tab-content-profil" class="tab-panel hidden space-y-6">
            <!-- Guest Profile Info Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 flex flex-col sm:flex-row items-center gap-6 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-3xl shrink-0"><i class="fas fa-user-circle"></i></div>
                <div class="text-center sm:text-left space-y-2 grow">
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Pengunjung Tamu</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Non-Anggota (Akses Pratinjau)</p>
                    <span class="inline-block bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-3 py-1 rounded-full text-[10px] font-bold">Limitasi Akses Aktif</span>
                </div>
                <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto">
                    <a href="{{ route('login') }}" class="grow sm:grow-0 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-all text-center">Masuk</a>
                    <a href="{{ route('register') }}" class="grow sm:grow-0 px-4 py-2 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg transition-all text-center">Daftar</a>
                </div>
            </div>

            <!-- FAQ Section Accordions -->
            <div class="space-y-4">
                <h3 class="font-extrabold text-base tracking-tight"><i class="fas fa-question-circle text-blue-500 mr-2"></i>Pertanyaan Umum (FAQ)</h3>
                <div class="space-y-3">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                        <button onclick="toggleFaq(1)" class="w-full px-5 py-4 text-left font-bold text-xs flex justify-between items-center text-slate-900 dark:text-white outline-none">
                            Bagaimana cara meminjam buku fisik?
                            <i id="faq-icon-1" class="fas fa-chevron-down text-slate-400 transition-transform"></i>
                        </button>
                        <div id="faq-content-1" class="px-5 pb-4 text-xs text-slate-500 dark:text-slate-400 hidden leading-relaxed">
                            Peminjaman dapat diajukan dengan mendaftar sebagai anggota resmi terlebih dahulu. Setelah akun disetujui, Anda dapat mengeklik tombol "Pinjam Buku" di katalog dan mengambil buku fisik di perpustakaan.
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                        <button onclick="toggleFaq(2)" class="w-full px-5 py-4 text-left font-bold text-xs flex justify-between items-center text-slate-900 dark:text-white outline-none">
                            Berapa lama batas waktu peminjaman?
                            <i id="faq-icon-2" class="fas fa-chevron-down text-slate-400 transition-transform"></i>
                        </button>
                        <div id="faq-content-2" class="px-5 pb-4 text-xs text-slate-500 dark:text-slate-400 hidden leading-relaxed">
                            Batas peminjaman buku standar adalah 7 hari semenjak permintaan disetujui oleh petugas perpustakaan.
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                        <button onclick="toggleFaq(3)" class="w-full px-5 py-4 text-left font-bold text-xs flex justify-between items-center text-slate-900 dark:text-white outline-none">
                            Bagaimana cara membaca e-book secara penuh?
                            <i id="faq-icon-3" class="fas fa-chevron-down text-slate-400 transition-transform"></i>
                        </button>
                        <div id="faq-content-3" class="px-5 pb-4 text-xs text-slate-500 dark:text-slate-400 hidden leading-relaxed">
                            Membaca e-book memerlukan status keanggotaan Premium. Anda dapat melakukan aktivasi akun premium melalui menu langganan di dalam Dashboard Member.
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Bottom Navigation Bar (Mobile Only) -->
    <nav class="md:hidden fixed bottom-0 left-0 z-40 w-full bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 px-6 py-2 flex justify-around items-center shadow-lg">
        <button onclick="switchTab('beranda')" id="mobile-tab-beranda" class="flex flex-col items-center gap-1 text-blue-600 dark:text-blue-400 shrink-0">
            <i class="fas fa-home text-base"></i>
            <span class="text-[9px] font-bold">Beranda</span>
        </button>
        <button onclick="switchTab('jelajahi')" id="mobile-tab-jelajahi" class="flex flex-col items-center gap-1 text-slate-400 dark:text-slate-500 shrink-0">
            <i class="fas fa-compass text-base"></i>
            <span class="text-[9px] font-bold">Jelajahi</span>
        </button>
        <button onclick="switchTab('profil')" id="mobile-tab-profil" class="flex flex-col items-center gap-1 text-slate-400 dark:text-slate-500 shrink-0">
            <i class="fas fa-user text-base"></i>
            <span class="text-[9px] font-bold">Profil</span>
        </button>
    </nav>

    <!-- BOOK DETAIL SHEET MODAL -->
    <div id="detailSheet" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-end justify-center transition-opacity" onclick="closeBookDetail()">
        <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-t-3xl p-6 space-y-5 animate-slide-up shadow-2xl relative" onclick="event.stopPropagation()">
            <!-- Handle drag bar mockup -->
            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-800 rounded-full mx-auto mb-2"></div>
            
            <div class="flex gap-4">
                <div class="w-24 h-32 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700 relative overflow-hidden">
                    <i id="sheet-book-icon" class="fas text-4xl"></i>
                    <span id="sheet-book-type" class="absolute top-1.5 right-1.5 text-[8px] font-bold px-1.5 py-0.5 rounded"></span>
                </div>
                <div class="space-y-2 select-none grow">
                    <h3 id="sheet-title" class="font-extrabold text-sm text-slate-900 dark:text-white leading-tight"></h3>
                    <p id="sheet-author" class="text-xs text-slate-500 dark:text-slate-400"></p>
                    <div class="flex items-center gap-1">
                        <i class="fas fa-star text-amber-500 text-xs"></i>
                        <i class="fas fa-star text-amber-500 text-xs"></i>
                        <i class="fas fa-star text-amber-500 text-xs"></i>
                        <i class="fas fa-star text-amber-500 text-xs"></i>
                        <i class="fas fa-star-half-alt text-amber-500 text-xs"></i>
                        <span class="text-[10px] text-slate-600 dark:text-slate-400 font-bold ml-1">4.5</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1.5 select-none">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white">Deskripsi Buku</h4>
                <p id="sheet-desc" class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed max-h-24 overflow-y-auto"></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-2">
                <button onclick="closeBookDetail()" class="px-4 py-3 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-xs transition-colors">Tutup</button>
                <button id="sheet-action-btn" class="flex-grow py-3 text-white font-bold rounded-xl text-xs transition-colors shadow-lg shadow-blue-500/10"></button>
            </div>
        </div>
    </div>

    <!-- RESTRICTED MODAL -->
    <div id="restrictedModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-3xl p-6 text-center space-y-6 shadow-2xl relative border border-slate-100 dark:border-slate-800">
            <div class="w-14 h-14 bg-rose-500/10 text-rose-500 rounded-full flex items-center justify-center text-2xl mx-auto">
                <i class="fas fa-lock"></i>
            </div>
            <div class="space-y-2">
                <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Akses Terbatas Keanggotaan</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Peminjaman buku fisik dan akses baca e-book digital secara penuh dibatasi khusus untuk anggota resmi perpustakaan.</p>
            </div>
            <div class="flex flex-col gap-2">
                <a href="{{ route('login') }}" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition-colors shadow-lg shadow-blue-500/15">Masuk Akun Anggota</a>
                <a href="{{ route('register') }}" class="w-full py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-xs transition-colors">Daftar Anggota Baru</a>
                <button onclick="closeRestrictedModal()" class="w-full py-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-xs font-semibold mt-2">Tutup</button>
            </div>
        </div>
    </div>

    <!-- E-BOOK READER PREVIEW MODAL -->
    <div id="readerModal" class="fixed inset-0 z-50 bg-slate-900 hidden flex-col">
        <!-- Header -->
        <header class="bg-slate-950 text-white px-6 py-4 flex justify-between items-center border-b border-slate-800">
            <div class="space-y-1">
                <h4 id="reader-title" class="font-bold text-xs"></h4>
                <p id="reader-author" class="text-[10px] text-slate-400"></p>
            </div>
            
            <!-- Page Controls -->
            <div class="flex items-center gap-2 bg-slate-900 border border-slate-700 rounded-full px-3 py-1 text-xs font-semibold">
                <button onclick="prevReaderPage()" id="btn-reader-prev" class="w-6 h-6 rounded-full flex items-center justify-center hover:bg-slate-800 disabled:opacity-30"><i class="fas fa-chevron-left"></i></button>
                <span>Halaman <span id="reader-page-num" class="text-blue-400 font-bold">1</span> / 10</span>
                <button onclick="nextReaderPage()" id="btn-reader-next" class="w-6 h-6 rounded-full flex items-center justify-center hover:bg-slate-800 disabled:opacity-30"><i class="fas fa-chevron-right"></i></button>
            </div>

            <div class="flex items-center gap-3">
                <!-- Theme toggle reader -->
                <button onclick="toggleReaderTheme('light')" class="w-8 h-8 rounded-lg bg-white text-slate-950 font-bold text-xs flex items-center justify-center border border-slate-700">L</button>
                <button onclick="toggleReaderTheme('sepia')" class="w-8 h-8 rounded-lg bg-orange-100 text-orange-950 font-bold text-xs flex items-center justify-center border border-slate-700">S</button>
                <button onclick="toggleReaderTheme('dark')" class="w-8 h-8 rounded-lg bg-slate-850 text-white font-bold text-xs flex items-center justify-center border border-slate-700">D</button>
                <div class="w-px h-6 bg-slate-800"></div>
                <button onclick="closeReader()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 font-semibold rounded-lg text-xs">Keluar</button>
            </div>
        </header>
        <!-- Reader Body -->
        <div id="reader-container" class="flex-grow overflow-y-auto p-6 md:p-12 bg-orange-50 text-orange-950 max-w-3xl mx-auto w-full relative">
            <div id="reader-content" class="space-y-4 select-none pb-48">
                <!-- Injected via JavaScript -->
            </div>

            <!-- Lock overlay on bottom half -->
            <div id="reader-lock-overlay" class="absolute bottom-0 left-0 w-full h-80 bg-gradient-to-t from-orange-50 via-orange-50/95 to-transparent flex flex-col justify-end items-center p-6 space-y-4 hidden">
                <div class="bg-white/85 dark:bg-slate-900/85 backdrop-blur border border-amber-200 dark:border-slate-850 rounded-3xl p-5 text-center max-w-sm space-y-4 shadow-xl">
                    <div class="w-10 h-10 bg-amber-500/10 text-amber-500 rounded-full flex items-center justify-center text-lg mx-auto">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-extrabold text-xs text-slate-900 dark:text-white">Batas Halaman Pratinjau Tercapai</h4>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400">Anda telah membaca 10 halaman gratis pratinjau. Silakan login sebagai anggota premium untuk membaca seluruh isi e-book ini.</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('login') }}" class="grow py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-[10px] transition-colors text-center shadow-lg shadow-blue-500/15">Login</a>
                        <a href="{{ route('register') }}" class="grow py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-lg text-[10px] transition-colors text-center">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Footnote Footer -->
    <footer class="hidden md:block">
        <p>&copy; {{ date('Y') }} PustakaDigital. Hak Cipta Dilindungi Undang-Undang.</p>
    </footer>

    <!-- JAVASCRIPT LOGIC CONTROLS -->
    <script>
        // Global variables
        let activeTab = 'beranda';
        let isDarkMode = false;
        let selectedBook = null;

        // Theme Mode Toggle
        function toggleTheme() {
            isDarkMode = !isDarkMode;
            const body = document.documentElement;
            const icon = document.getElementById('theme-icon');
            if (isDarkMode) {
                body.classList.add('dark');
                icon.className = 'fas fa-sun';
            } else {
                body.classList.remove('dark');
                icon.className = 'fas fa-moon';
            }
        }

        // Switch Navigation Tabs
        function switchTab(tabName) {
            activeTab = tabName;
            
            // Hide all tab panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            // Show active panel
            document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');

            // Reset navigation tab button classes
            const tabButtons = ['beranda', 'jelajahi', 'profil'];
            
            // For Desktop
            tabButtons.forEach(btn => {
                const el = document.getElementById(`desktop-tab-${btn}`);
                if (el) {
                    if (btn === tabName) {
                        el.className = 'tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition-colors bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400';
                    } else {
                        el.className = 'tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition-colors text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800';
                    }
                }
            });

            // For Mobile
            tabButtons.forEach(btn => {
                const el = document.getElementById(`mobile-tab-${btn}`);
                if (el) {
                    if (btn === tabName) {
                        el.className = 'flex flex-col items-center gap-1 text-blue-600 dark:text-blue-400 shrink-0';
                    } else {
                        el.className = 'flex flex-col items-center gap-1 text-slate-400 dark:text-slate-500 shrink-0';
                    }
                }
            });
        }

        // Live Filter Catalog
        function filterCatalog() {
            const query = document.getElementById('catalog-search').value.toLowerCase();
            const items = document.querySelectorAll('.book-card-item');

            items.forEach(item => {
                const title = item.getAttribute('data-title');
                const author = item.getAttribute('data-author');
                
                if (title.includes(query) || author.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Pill categories filter
        function filterCategory(catType) {
            const items = document.querySelectorAll('.book-card-item');
            
            // Toggle active pill button styles
            const pills = {
                'all': document.getElementById('cat-all'),
                'E-Book': document.getElementById('cat-ebook'),
                'Fisik': document.getElementById('cat-fisik')
            };

            for (const k in pills) {
                if (pills[k]) {
                    pills[k].className = 'cat-pill-btn px-4 py-1.5 rounded-full text-xs font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 transition-all hover:bg-slate-50';
                }
            }

            if (catType === 'all') {
                pills['all'].className = 'cat-pill-btn px-4 py-1.5 rounded-full text-xs font-bold bg-blue-600 text-white transition-all';
                items.forEach(item => item.style.display = 'flex');
            } else if (catType === 'E-Book') {
                pills['E-Book'].className = 'cat-pill-btn px-4 py-1.5 rounded-full text-xs font-bold bg-blue-600 text-white transition-all';
                items.forEach(item => {
                    if (item.getAttribute('data-category') === 'ebook') {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            } else {
                pills['Fisik'].className = 'cat-pill-btn px-4 py-1.5 rounded-full text-xs font-bold bg-blue-600 text-white transition-all';
                items.forEach(item => {
                    if (item.getAttribute('data-category') === 'fisik') {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        }

        // Book Detail Modal control
        function openBookDetail(book) {
            selectedBook = book;
            
            // Set sheet metadata
            document.getElementById('sheet-title').textContent = book.title;
            document.getElementById('sheet-author').textContent = `Oleh: ${book.author}`;
            document.getElementById('sheet-desc').textContent = book.description || 'Tidak ada deskripsi yang tersedia untuk buku ini.';
            
            const actionBtn = document.getElementById('sheet-action-btn');
            const icon = document.getElementById('sheet-book-icon');
            const badgeType = document.getElementById('sheet-book-type');

            if (book.is_ebook) {
                icon.className = 'fas fa-file-pdf text-4xl text-rose-500';
                badgeType.className = 'absolute top-1.5 right-1.5 text-[8px] font-bold px-1.5 py-0.5 rounded bg-rose-500 text-white';
                badgeType.textContent = 'E-BOOK';
                
                actionBtn.textContent = 'Baca E-Book';
                actionBtn.className = 'flex-grow py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors shadow-lg shadow-emerald-500/10';
                actionBtn.setAttribute('onclick', 'openReader()');
            } else {
                icon.className = 'fas fa-book text-4xl text-blue-500';
                badgeType.className = 'absolute top-1.5 right-1.5 text-[8px] font-bold px-1.5 py-0.5 rounded bg-amber-500 text-slate-950';
                badgeType.textContent = `${book.stock} STOK`;

                actionBtn.textContent = 'Pinjam Buku';
                actionBtn.className = 'flex-grow py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition-colors shadow-lg shadow-blue-500/10';
                actionBtn.setAttribute('onclick', 'showRestrictedModal()');
            }

            document.getElementById('detailSheet').className = 'fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-end justify-center transition-opacity';
        }

        function closeBookDetail() {
            document.getElementById('detailSheet').className = 'fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-end justify-center transition-opacity';
        }

        // Restricted access modal
        function showRestrictedModal() {
            closeBookDetail();
            document.getElementById('restrictedModal').classList.remove('hidden');
            document.getElementById('restrictedModal').classList.add('flex');
        }

        function closeRestrictedModal() {
            document.getElementById('restrictedModal').classList.remove('flex');
            document.getElementById('restrictedModal').classList.add('hidden');
        }

        // FAQ Toggle accordion
        function toggleFaq(id) {
            const content = document.getElementById(`faq-content-${id}`);
            const icon = document.getElementById(`faq-icon-${id}`);

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        // Ebook Reader Simulation Preview
        let readerPageNum = 1;
        const readerPages = [
            "<h2><b>Bab 1: Pentingnya Literasi Digital di Era Informasi</b></h2><br><p class='leading-relaxed text-sm'>Literasi digital bukan lagi sekadar kemampuan menggunakan komputer, melainkan kecakapan dasar untuk menyaring, mengevaluasi, dan memanfaatkan informasi secara bijak di era digital yang dinamis ini.</p><p class='leading-relaxed text-sm mt-3'>Kemampuan menganalisis sumber bacaan, membedakan fakta dan opini, serta menjaga etika berinternet menjadi fondasi penting bagi pembelajar abad 21.</p>",
            "<h2><b>Bab 2: Keunggulan E-Book Dibanding Buku Fisik</b></h2><br><p class='leading-relaxed text-sm'>E-book menawarkan fleksibilitas akses tanpa batas ruang dan waktu. Dengan format digital, ribuan buku dapat disimpan dalam satu perangkat genggam dan ramah terhadap lingkungan karena mengurangi konsumsi kertas.</p>",
            "<h2><b>Bab 3: Strategi Membaca Cepat dan Efektif</b></h2><br><p class='leading-relaxed text-sm'>Membaca cepat (speed reading) melibatkan teknik pemetaan visual kata secara kelompok serta meminimalkan subvokalisasi guna mempercepat penyerapan informasi esensial dalam waktu singkat.</p>",
            "<h2><b>Bab 4: Pemanfaatan Kecerdasan Buatan (AI) Dalam Pendidikan</b></h2><br><p class='leading-relaxed text-sm'>AI dapat mempersonalisasi materi pembelajaran berdasarkan kecepatan pemahaman individu, memberikan rekomendasi buku, serta membantu menjawab pertanyaan akademis secara instan dan interaktif.</p>",
            "<h2><b>Bab 5: Cara Mengembangkan Fokus dan Konsentrasi Saat Belajar</b></h2><br><p class='leading-relaxed text-sm'>Fokus maksimal dapat dicapai dengan meminimalkan gangguan notifikasi digital, menerapkan metode Pomodoro (25 menit belajar, 5 menit istirahat), dan menata area belajar yang kondusif.</p>",
            "<h2><b>Bab 6: Teknik Menulis Karya Ilmiah dan Jurnal</b></h2><br><p class='leading-relaxed text-sm'>Penulisan karya tulis ilmiah yang kredibel harus disusun secara sistematis, menggunakan metodologi yang jelas, didukung oleh data valid, serta mengikuti panduan sitasi resmi bebas plagiasi.</p>",
            "<h2><b>Bab 7: Manajemen Waktu Bagi Mahasiswa Aktif</b></h2><br><p class='leading-relaxed text-sm'>Manajemen waktu yang efektif melibatkan penetapan skala prioritas (Eisenhower Matrix) serta kedisiplinan dalam mengeksekusi rencana belajar harian.</p>",
            "<h2><b>Bab 8: Membangun Kebiasaan Membaca Harian</b></h2><br><p class='leading-relaxed text-sm'>Membaca harian dapat dibentuk dengan menyisihkan waktu khusus secara konsisten, misalnya 15 menit sebelum tidur atau saat memulai pagi hari.</p>",
            "<h2><b>Bab 9: Kolaborasi dan Diskusi Kelompok di Era Modern</b></h2><br><p class='leading-relaxed text-sm'>Diskusi kolaboratif menggunakan media digital mempercepat proses brainstorming dan penyelesaian tugas bersama secara efisien di mana pun anggota kelompok berada.</p>",
            "<h2><b>Bab 10: Batas Halaman Pratinjau Gratis Tercapai</b></h2><br><p class='leading-relaxed text-sm'>Anda telah membaca 10 halaman gratis pratinjau e-book. Untuk melanjutkan membaca seluruh isi dokumen, silakan lakukan aktivasi akun Premium perpustakaan digital secara resmi.</p>"
        ];

        function renderReaderPage() {
            document.getElementById('reader-content').innerHTML = readerPages[readerPageNum - 1];
            document.getElementById('reader-page-num').textContent = readerPageNum;

            document.getElementById('btn-reader-prev').disabled = (readerPageNum === 1);
            document.getElementById('btn-reader-next').disabled = (readerPageNum === 10);

            const lockOverlay = document.getElementById('reader-lock-overlay');
            if (readerPageNum === 10) {
                lockOverlay.classList.remove('hidden');
            } else {
                lockOverlay.classList.add('hidden');
            }
        }

        function prevReaderPage() {
            if (readerPageNum > 1) {
                readerPageNum--;
                renderReaderPage();
            }
        }

        function nextReaderPage() {
            if (readerPageNum < 10) {
                readerPageNum++;
                renderReaderPage();
            }
        }

        function openReader() {
            closeBookDetail();
            if (!selectedBook) return;

            readerPageNum = 1;
            renderReaderPage();

            document.getElementById('reader-title').textContent = selectedBook.title;
            document.getElementById('reader-author').textContent = `Oleh: ${selectedBook.author}`;
            document.getElementById('readerModal').className = 'fixed inset-0 z-50 bg-slate-900 flex flex-col';
        }

        function closeReader() {
            document.getElementById('readerModal').className = 'fixed inset-0 z-50 bg-slate-900 hidden flex-col';
        }

        function toggleReaderTheme(themeName) {
            const container = document.getElementById('reader-container');
            const overlay = document.getElementById('reader-lock-overlay');
            if (themeName === 'light') {
                container.className = 'flex-grow overflow-y-auto p-6 md:p-12 bg-white text-slate-900 max-w-3xl mx-auto w-full relative';
                overlay.className = 'absolute bottom-0 left-0 w-full h-80 bg-gradient-to-t from-white via-white/95 to-transparent flex flex-col justify-end items-center p-6 space-y-4';
            } else if (themeName === 'sepia') {
                container.className = 'flex-grow overflow-y-auto p-6 md:p-12 bg-orange-50 text-orange-950 max-w-3xl mx-auto w-full relative';
                overlay.className = 'absolute bottom-0 left-0 w-full h-80 bg-gradient-to-t from-orange-50 via-orange-50/95 to-transparent flex flex-col justify-end items-center p-6 space-y-4';
            } else {
                container.className = 'flex-grow overflow-y-auto p-6 md:p-12 bg-slate-900 text-slate-100 max-w-3xl mx-auto w-full relative';
                overlay.className = 'absolute bottom-0 left-0 w-full h-80 bg-gradient-to-t from-slate-900 via-slate-900/95 to-transparent flex flex-col justify-end items-center p-6 space-y-4';
            }
            if (readerPageNum === 10) {
                overlay.classList.remove('hidden');
            } else {
                overlay.classList.add('hidden');
            }
        }


    </script>
</body>
</html>
