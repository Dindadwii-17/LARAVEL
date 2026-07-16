<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LiterasiKu - Portal Anggota Digital</title>
    <!-- Google Fonts: Inter for Clean & Professional Aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons for UI Elements -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js for Reading Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        },
                        darkBg: '#0b0f19',
                        darkCard: '#151b2c',
                        darkBorder: '#222b44'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    fontSize: {
                        'xs': ['0.82rem', { lineHeight: '1.2rem' }],
                        'sm': ['0.95rem', { lineHeight: '1.4rem' }],
                        'base': ['1.05rem', { lineHeight: '1.6rem' }],
                        'lg': ['1.2rem', { lineHeight: '1.75rem' }],
                        'xl': ['1.35rem', { lineHeight: '1.9rem' }],
                        '2xl': ['1.65rem', { lineHeight: '2.2rem' }],
                        '3xl': ['2rem', { lineHeight: '2.5rem' }]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        /* Custom scrollbar */
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
            background: #334155;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Custom Glassmorphism effects */
        .glass-panel {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .dark .glass-panel {
            background: rgba(21, 27, 44, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        /* Page transitions */
        .page-content {
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Beautiful Book 3D Hover Effect */
        .book-card-3d {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }
        .book-card-3d:hover {
            transform: translateY(-8px) scale(1.02);
        }
        /* Shimmer effect for loaders */
        .shimmer {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: loading-shimmer 1.5s infinite;
        }
        @keyframes loading-shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-darkBg dark:text-slate-200 min-h-screen flex flex-col md:flex-row antialiased transition-colors duration-300">

    <script>
        // Loaded dynamically from Laragon perpustakaan MySQL database
        const booksData = @json($formattedBooks);

        // Global Application State
        let currentUser = @json($currentUser);

        let currentActiveTab = "{{ $activeTab ?? 'dashboard' }}";
        let searchQuery = "";
        let activeFilterCategory = "Semua";
        let sortOption = "rating-desc";
        let selectedBook = null;
        let isDarkMode = false;
        let profileActiveSubTab = "info";

        // Save current user to login history
        if (currentUser && currentUser.email) {
            let historyList = JSON.parse(localStorage.getItem("loginHistory") || "[]");
            const current = {
                name: currentUser.name,
                email: currentUser.email,
                avatar: currentUser.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(currentUser.name),
                lastLogin: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
            };
            historyList = historyList.filter(item => item.email !== current.email);
            historyList.unshift(current);
            if (historyList.length > 3) historyList.pop();
            localStorage.setItem("loginHistory", JSON.stringify(historyList));
        }

        let activeModalTab = "synopsis";
        let activeRatingSelection = 5;

        // Seeding comments data in memory (indexed by book slug or id)
        let bookComments = {
            "laskar-pelangi": [
                { user: "Andi Saputra", rating: 5, text: "Buku yang sangat menginspirasi tentang perjuangan anak-anak Belitong.", date: "12 Juli 2026" },
                { user: "Siti Rahma", rating: 4, text: "Alur ceritanya sangat menyentuh dan menggambarkan realitas sosial.", date: "15 Juli 2026" }
            ],
            "clean-code": [
                { user: "Budi Santoso", rating: 5, text: "Sangat direkomendasikan untuk programmer profesional yang ingin menulis kode bersih.", date: "10 Juli 2026" },
                { user: "Dewi Lestari", rating: 4, text: "Sangat teknis namun sangat berguna untuk merapikan codebase.", date: "11 Juli 2026" }
            ],
            "bumi-manusia": [
                { user: "Farhan Wijaya", rating: 5, text: "Karya sastra luar biasa dari Pramoedya Ananta Toer. Luar biasa!", date: "14 Juli 2026" }
            ],
            "refactoring": [
                { user: "Rian Kurniawan", rating: 5, text: "Panduan refactoring terbaik dengan contoh yang konkret.", date: "09 Juli 2026" }
            ],
            "the-maze-runner": [
                { user: "Nabila Putri", rating: 4, text: "Ketegangan yang konsisten dari bab pertama sampai akhir.", date: "16 Juli 2026" }
            ]
        };

        // Function to toggle tabs in the book modal
        function toggleModalTab(tabName) {
            activeModalTab = tabName;
            const tabSynopsis = document.getElementById("modal-tab-synopsis");
            const tabComments = document.getElementById("modal-tab-comments");
            const contentSynopsis = document.getElementById("modal-content-synopsis");
            const contentComments = document.getElementById("modal-content-comments");

            if (tabName === "synopsis") {
                if (tabSynopsis) tabSynopsis.className = "flex-1 pb-2 text-xs font-bold text-brand-600 dark:text-brand-400 border-b-2 border-brand-500 text-center focus:outline-none";
                if (tabComments) tabComments.className = "flex-1 pb-2 text-xs font-semibold text-slate-400 text-center focus:outline-none";
                if (contentSynopsis) contentSynopsis.classList.remove("hidden");
                if (contentComments) contentComments.classList.add("hidden");
            } else {
                if (tabComments) tabComments.className = "flex-1 pb-2 text-xs font-bold text-brand-600 dark:text-brand-400 border-b-2 border-brand-500 text-center focus:outline-none";
                if (tabSynopsis) tabSynopsis.className = "flex-1 pb-2 text-xs font-semibold text-slate-400 text-center focus:outline-none";
                if (contentSynopsis) contentSynopsis.classList.add("hidden");
                if (contentComments) contentComments.classList.remove("hidden");
                renderCommentsList();
            }
        }

        // Function to set the rating selection in the star selector
        function setCommentRating(rating) {
            activeRatingSelection = rating;
            const stars = document.querySelectorAll("#star-rating-selector .star-btn");
            stars.forEach(star => {
                const r = parseInt(star.getAttribute("data-rating"));
                if (r <= rating) {
                    star.classList.remove("text-slate-300", "dark:text-slate-600");
                    star.classList.add("text-amber-400");
                } else {
                    star.classList.add("text-slate-300", "dark:text-slate-600");
                    star.classList.remove("text-amber-400");
                }
            });
        }

        // Render the list of comments for the currently selected book
        function renderCommentsList() {
            if (!selectedBook) return;
            const key = selectedBook.slug;
            const comments = bookComments[key] || [];

            // Update comment count badges
            const commentCount = document.getElementById("modal-comment-count");
            if (commentCount) commentCount.innerText = comments.length;

            const listContainer = document.getElementById("modal-comments-list");
            if (!listContainer) return;
            
            if (comments.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-xs italic">
                        Belum ada ulasan untuk buku ini. Jadilah yang pertama memberikan ulasan!
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = comments.map(c => {
                let starsHTML = "";
                for (let i = 1; i <= 5; i++) {
                    if (i <= c.rating) {
                        starsHTML += `<i class="fa-solid fa-star text-amber-400 text-[10px]"></i>`;
                    } else {
                        starsHTML += `<i class="fa-solid fa-star text-slate-200 dark:text-slate-700 text-[10px]"></i>`;
                    }
                }

                return `
                    <div class="bg-slate-50 dark:bg-slate-800/40 p-3 rounded-xl border border-slate-100 dark:border-darkBorder space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">${c.user}</span>
                            <span class="text-[9px] text-slate-400 font-semibold">${c.date}</span>
                        </div>
                        <div class="flex items-center space-x-1">${starsHTML}</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">${c.text}</p>
                    </div>
                `;
            }).join("");
        }

        // Submit comment from input
        function submitUserComment() {
            if (!selectedBook) return;
            const textInput = document.getElementById("new-comment-text");
            if (!textInput) return;
            const text = textInput.value.trim();
            if (!text) {
                showToast("Ulasan tidak boleh kosong!", "error");
                return;
            }

            const key = selectedBook.slug;
            if (!bookComments[key]) {
                bookComments[key] = [];
            }

            // Create new comment object
            const newComment = {
                user: currentUser.name,
                rating: activeRatingSelection,
                text: text,
                date: "Hari Ini"
            };

            bookComments[key].unshift(newComment);
            
            // Recalculate average rating of the book
            const comments = bookComments[key];
            const sum = comments.reduce((acc, c) => acc + c.rating, 0);
            const avg = (sum / comments.length).toFixed(1);
            
            // Update book ratings locally
            selectedBook.rating = parseFloat(avg);
            const ratingDisplay = document.getElementById("modal-book-rating");
            if (ratingDisplay) ratingDisplay.innerText = avg;

            // Clear input and reset stars
            textInput.value = "";
            setCommentRating(5);

            // Re-render
            renderCommentsList();
            showToast("Ulasan berhasil diposting!", "success");
            
            // Update books list views if currently rendered
            updateActiveView();
        }
    </script>

    <!-- Sidebar Navigation for Desktop -->
    <aside class="w-full md:w-64 md:h-screen md:sticky md:top-0 bg-white dark:bg-darkCard border-r border-slate-200 dark:border-darkBorder flex-shrink-0 flex flex-col justify-between p-5 z-20 transition-all duration-300 overflow-y-auto no-scrollbar">
        <div>
            <!-- Library Branding -->
            <div class="flex items-center space-x-3 mb-8 px-2 py-1">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-brand-500/20">
                    <i class="fa-solid fa-book-open text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-tight tracking-tight dark:text-white">LiterasiKu</h1>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-semibold tracking-wider uppercase">PORTAL &bull; ACADEMIC</span>
                </div>
            </div>

            <!-- Main Menu List -->
            <nav class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Menu Utama</p>
                <button onclick="switchTab('dashboard')" id="nav-dashboard" class="nav-btn w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Dashboard</span>
                </button>
                <button onclick="switchTab('catalog')" id="nav-catalog" class="nav-btn w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-800 dark:hover:text-slate-100">
                    <i class="fa-solid fa-compass w-5 text-center"></i>
                    <span>Eksplor Buku</span>
                </button>
                <button onclick="switchTab('bookshelf')" id="nav-bookshelf" class="nav-btn w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-800 dark:hover:text-slate-100">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i>
                    <span>Denda</span>
                </button>

                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 pt-6 mb-2">Akun & Layanan</p>
                <button onclick="switchTab('profile')" id="nav-profile" class="nav-btn w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-800 dark:hover:text-slate-100">
                    <i class="fa-solid fa-user-graduate w-5 text-center"></i>
                    <span>Profil Akademik</span>
                </button>
                <button onclick="switchTab('history')" id="nav-history" class="nav-btn w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-800 dark:hover:text-slate-100">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                    <span>Riwayat Pinjam</span>
                </button>
                <button onclick="submitLogout()" class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 text-rose-600 dark:text-rose-455 hover:bg-rose-50 dark:hover:bg-rose-950/20">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                    <span>Keluar / Logout</span>
                </button></nav>
        </div>

        <!-- User Information Bottom Widget -->
        <div class="mt-8 border-t border-slate-100 dark:border-darkBorder pt-4">
            <div class="flex items-center space-x-3 p-1">
                <img id="sidebar-avatar" src="" alt="User Avatar" class="w-10 h-10 rounded-xl object-cover border border-brand-100 dark:border-brand-900">
                <div class="flex-1 min-w-0">
                    <h4 id="sidebar-username" class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate"></h4>
                    <span id="sidebar-membership" class="text-[10px] bg-indigo-50 dark:bg-indigo-950/40 text-brand-600 dark:text-brand-400 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider"></span>
                </div>
            </div>
            <!-- Theme Toggle Button inside Sidebar -->
            <button onclick="toggleTheme()" class="w-full mt-4 flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-100 dark:border-darkBorder transition-all">
                <span class="flex items-center space-x-2">
                    <i class="fa-solid fa-moon text-indigo-500 dark:text-amber-400"></i>
                    <span class="dark:hidden text-slate-600">Mode Gelap</span>
                    <span class="hidden dark:inline text-slate-300">Mode Terang</span>
                </span>
                <i class="fa-solid fa-circle-half-stroke text-slate-400"></i>
            </button>
        </div>
    </aside>

        @if(session('success'))
            <div id="flash-alert-success" class="max-w-7xl mx-auto px-4 pt-4 transition-all duration-500">
                <div class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 rounded-xl text-xs font-bold text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div id="flash-alert-error" class="max-w-7xl mx-auto px-4 pt-4 transition-all duration-500">
                <div class="flex items-center space-x-3 px-4 py-3 bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/40 rounded-xl text-xs font-bold text-red-600 dark:text-red-400">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

    <!-- Mobile Header/Navigation -->
    <div class="md:hidden flex items-center justify-between bg-white dark:bg-darkCard px-4 py-3 border-b border-slate-200 dark:border-darkBorder z-20">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-brand-500 to-indigo-600 flex items-center justify-center text-white">
                <i class="fa-solid fa-book-open text-sm"></i>
            </div>
            <span class="font-bold text-base dark:text-white">LiterasiKu</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="toggleTheme()" class="p-2 text-slate-500 dark:text-slate-400 rounded-lg bg-slate-100 dark:bg-slate-800">
                <i id="mobile-theme-icon" class="fa-solid fa-moon"></i>
            </button>
            <button onclick="toggleMobileMenu()" class="p-2 text-slate-500 dark:text-slate-400 rounded-lg bg-slate-100 dark:bg-slate-800">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-darkCard border-b border-slate-200 dark:border-darkBorder absolute top-14 left-0 w-full z-10 p-4 shadow-lg space-y-2">
        <button onclick="switchTab('dashboard'); toggleMobileMenu()" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800">Dashboard</button>
        <button onclick="switchTab('catalog'); toggleMobileMenu()" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800">Eksplor Buku</button>
        <button onclick="switchTab('bookshelf'); toggleMobileMenu()" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800">Denda</button>
        <button onclick="switchTab('profile'); toggleMobileMenu()" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800">Profil</button>
        <button onclick="switchTab('history'); toggleMobileMenu()" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800">Riwayat</button>
        <button onclick="submitLogout()" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-rose-600 hover:bg-rose-50">Keluar</button></div>

    <!-- Main Workspace -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">

        <!-- Top Header Panel -->
        <header class="h-16 border-b border-slate-100 dark:border-darkBorder bg-white/75 dark:bg-darkCard/75 backdrop-blur-md px-6 hidden md:flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center space-x-4 w-96">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" oninput="handleSearch(event)" placeholder="Cari buku, penulis, kategori akademik..." class="w-full pl-9 pr-4 py-1.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 dark:text-slate-200 transition-all">
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-400">IP Akademik</p>
                    <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center space-x-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                        <span>10.124.8.44 (Terhubung)</span>
                    </p>
                </div>
                <div class="h-8 w-px bg-slate-200 dark:bg-darkBorder"></div>
                <!-- Mini Alert Notifications -->
                <div class="relative">
                    <button onclick="toggleNotificationDropdown(event)" class="relative p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all focus:outline-none">
                        <i class="fa-regular fa-bell text-sm"></i>
                        <span id="notif-badge" class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500"></span>
                    </button>
                    <!-- Notification Dropdown Container -->
                    <div id="notif-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-darkCard border border-slate-150 dark:border-darkBorder rounded-2xl shadow-xl z-30 py-3 animate-fadeIn">
                        <div class="flex justify-between items-center px-4 pb-2 border-b border-slate-100 dark:border-darkBorder/40">
                            <span class="text-xs font-bold text-slate-850 dark:text-slate-200">Notifikasi</span>
                            <button onclick="markAllNotificationsAsRead()" class="text-[10px] text-brand-600 dark:text-brand-400 font-bold hover:underline">Tandai Dibaca</button>
                        </div>
                        <div id="notif-items-list" class="max-h-64 overflow-y-auto divide-y divide-slate-50 dark:divide-slate-800/40 text-xs">
                            <!-- Dynamic Notification Items -->
                        </div>
                        <div class="text-center pt-2 border-t border-slate-100 dark:border-darkBorder/40">
                            <span class="text-[10px] text-slate-400 font-semibold">Menampilkan notifikasi terbaru</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Area -->
        <div id="main-content" class="flex-1 p-4 md:p-8 space-y-8 page-content">
            <!-- Dynamic Sections get injected here by updateActiveView() -->
        </div>

    </main>

    <!-- Detailed Book Information Modal -->
    <div id="book-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-darkCard border border-slate-200 dark:border-darkBorder rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col animate-fadeIn">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-slate-100 dark:border-darkBorder">
                <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">DETAIL REFERENSI</span>
                <button onclick="closeBookModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-6 flex-1">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Book Visual Left -->
                    <div id="modal-book-cover-container" class="w-full md:w-44 flex-shrink-0 flex justify-center">
                        <!-- Dynamic Cover Injected Here -->
                    </div>
                    
                    <!-- Book Specs Right -->
                    <div class="flex-1 space-y-4">
                        <div>
                            <span id="modal-book-category" class="text-xs font-semibold px-2 py-0.5 rounded bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400"></span>
                            <h2 id="modal-book-title" class="text-xl font-bold mt-2 text-slate-900 dark:text-white leading-tight"></h2>
                            <p id="modal-book-author" class="text-sm text-slate-500 dark:text-slate-400 mt-1"></p>
                        </div>
                        
                        <!-- Ratings and Pages Indicators -->
                        <div class="grid grid-cols-3 gap-3 bg-slate-50 dark:bg-slate-800/40 p-3 rounded-xl border border-slate-100 dark:border-darkBorder text-center">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-semibold">RATING</span>
                                <span class="text-sm font-bold text-slate-800 dark:text-white flex items-center justify-center space-x-1">
                                    <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                                    <span id="modal-book-rating"></span>
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block font-semibold">HALAMAN</span>
                                <span id="modal-book-pages" class="text-sm font-bold text-slate-800 dark:text-white"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block font-semibold">TAHUN TERBIT</span>
                                <span id="modal-book-year" class="text-sm font-bold text-slate-800 dark:text-white"></span>
                            </div>
                        </div>

                        <!-- Technical Specs Metadata -->
                        <div class="text-xs space-y-1.5 text-slate-500 dark:text-slate-400">
                            <p><span class="font-semibold text-slate-700 dark:text-slate-300">Penerbit:</span> <span id="modal-book-publisher"></span></p>
                            <p><span class="font-semibold text-slate-700 dark:text-slate-300">Status Ketersediaan:</span> <span id="modal-book-status-badge"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Modal Tabs -->
                <div class="flex border-b border-slate-100 dark:border-darkBorder mb-4">
                    <button onclick="toggleModalTab('synopsis')" id="modal-tab-synopsis" class="flex-1 pb-2 text-xs font-bold text-brand-600 dark:text-brand-400 border-b-2 border-brand-500 text-center focus:outline-none">
                        Sinopsis
                    </button>
                    <button onclick="toggleModalTab('comments')" id="modal-tab-comments" class="flex-1 pb-2 text-xs font-semibold text-slate-400 text-center focus:outline-none">
                        Ulasan & Komentar (<span id="modal-comment-count">0</span>)
                    </button>
                </div>

                <!-- Synopsis Tab Content -->
                <div id="modal-content-synopsis" class="space-y-2">
                    <p id="modal-book-synopsis" class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed text-justify"></p>
                </div>

                <!-- Comments Tab Content -->
                <div id="modal-content-comments" class="space-y-4 hidden">
                    <!-- List of Comments -->
                    <div id="modal-comments-list" class="space-y-3 max-h-48 overflow-y-auto pr-1">
                        <!-- Dynamic Comments will be rendered here -->
                    </div>

                    <!-- Add Comment Form -->
                    <div class="border-t border-slate-100 dark:border-darkBorder pt-3 space-y-2">
                        <h4 class="text-xs font-bold text-slate-800 dark:text-white">Tulis Ulasan Anda</h4>
                        <div class="flex items-center space-x-1 mb-1">
                            <span class="text-[11px] text-slate-400 font-semibold mr-2">Rating Anda:</span>
                            <div class="flex space-x-1" id="star-rating-selector">
                                <button onclick="setCommentRating(1)" class="star-btn text-amber-400 transition-colors" data-rating="1"><i class="fa-solid fa-star text-xs"></i></button>
                                <button onclick="setCommentRating(2)" class="star-btn text-amber-400 transition-colors" data-rating="2"><i class="fa-solid fa-star text-xs"></i></button>
                                <button onclick="setCommentRating(3)" class="star-btn text-amber-400 transition-colors" data-rating="3"><i class="fa-solid fa-star text-xs"></i></button>
                                <button onclick="setCommentRating(4)" class="star-btn text-amber-400 transition-colors" data-rating="4"><i class="fa-solid fa-star text-xs"></i></button>
                                <button onclick="setCommentRating(5)" class="star-btn text-amber-400 transition-colors" data-rating="5"><i class="fa-solid fa-star text-xs"></i></button>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <input type="text" id="new-comment-text" placeholder="Bagikan pendapat Anda tentang buku ini..." class="flex-1 px-3 py-1.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 dark:text-slate-200">
                            <button onclick="submitUserComment()" class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center space-x-1.5">
                                <i class="fa-solid fa-paper-plane text-[10px]"></i>
                                <span>Kirim</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Controls -->
            <div id="modal-footer-actions" class="p-4 border-t border-slate-100 dark:border-darkBorder flex items-center justify-end space-x-3 bg-slate-50 dark:bg-darkCard">
                <!-- Action buttons will be rendered programmatically -->
            </div>
        </div>
    </div>

    <!-- Fullscreen eReader Interface Simulation -->
    <div id="ereader-panel" class="fixed inset-0 bg-white dark:bg-[#0f1420] z-50 flex flex-col hidden animate-fadeIn">
        <!-- Reader Bar -->
        <header class="h-14 border-b border-slate-200 dark:border-darkBorder bg-slate-50 dark:bg-darkCard px-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button onclick="closeEReader()" class="p-2 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 rounded-lg">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div>
                    <h3 id="ereader-book-title" class="text-xs font-bold truncate max-w-xs md:max-w-md dark:text-white"></h3>
                    <p id="ereader-book-author" class="text-[10px] text-slate-400"></p>
                </div>
            </div>
            <!-- Customizations & Controls -->
            <div class="flex items-center space-x-2">
                <!-- Text Scale Controllers -->
                <div class="flex bg-slate-200/60 dark:bg-slate-800 p-0.5 rounded-lg border border-slate-300/40 dark:border-darkBorder">
                    <button onclick="changeFontSize(-1)" class="px-2 py-1 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-300 dark:hover:bg-slate-700 rounded-md">A-</button>
                    <button onclick="changeFontSize(1)" class="px-2 py-1 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-300 dark:hover:bg-slate-700 rounded-md">A+</button>
                </div>
                <!-- Reading Theme Toggles -->
                <div class="flex space-x-1">
                    <button onclick="setReaderTheme('light')" class="w-6 h-6 rounded-full bg-white border border-slate-300 shadow-sm" title="Mode Terang"></button>
                    <button onclick="setReaderTheme('sepia')" class="w-6 h-6 rounded-full bg-[#f4ebd0] border border-[#e6d8b3] shadow-sm" title="Mode Sepia"></button>
                    <button onclick="setReaderTheme('dark')" class="w-6 h-6 rounded-full bg-slate-900 border border-slate-800 shadow-sm" title="Mode Gelap"></button>
                </div>
            </div>
        </header>

        <!-- Book Reader Body content -->
        <div id="ereader-body" class="flex-1 overflow-y-auto px-4 py-8 md:py-12 bg-white text-slate-800">
            <div class="max-w-2xl mx-auto space-y-6">
                <!-- Reader Mock Pages Context -->
                <div id="ereader-content-wrapper" class="prose dark:prose-invert max-w-none transition-all duration-200">
                    <!-- Text gets loaded by JS -->
                </div>
            </div>
        </div>

        <!-- Footer Page Indicator -->
        <footer class="h-12 border-t border-slate-200 dark:border-darkBorder bg-slate-50 dark:bg-darkCard px-4 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
            <button onclick="navigateMockPage(-1)" class="flex items-center space-x-1 px-3 py-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800">
                <i class="fa-solid fa-chevron-left"></i>
                <span>Kembali</span>
            </button>
            <span id="ereader-page-status"></span>
            <button onclick="navigateMockPage(1)" class="flex items-center space-x-1 px-3 py-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800">
                <span>Lanjut</span>
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </footer>
    </div>

    <!-- Alert / Toast Message System -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 space-y-2 pointer-events-none"></div>

    <!-- Main JS Application Logic -->
    <script>
        // Dynamic actions
        function openRealReader(book) {
            if (book.is_ebook) {
                window.location.href = "/ebooks/read/" + book.slug;
            } else {
                showToast("Buku ini adalah buku fisik, silakan pinjam terlebih dahulu.", "error");
            }
        }

        function openRealReaderById(bookId) {
            const book = booksData.find(b => b.id === bookId);
            if (book) {
                openRealReader(book);
            }
        }

        function upgradeToPremium(duration = 12) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('membership.upgrade') }}';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const durationInput = document.createElement('input');
            durationInput.type = 'hidden';
            durationInput.name = 'duration';
            durationInput.value = duration;
            form.appendChild(durationInput);
            
            document.body.appendChild(form);
            form.submit();
        }

        function submitLogout() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}';
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }

        // Override simulated borrow to perform real POST borrow request
        function borrowBook(bookId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/borrow/' + bookId;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }

        // Render member card view panel
        function renderMemberCardView(container) {
            container.innerHTML = `
                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Kartu Anggota Digital</h2>
                        <p class="text-xs text-slate-400">Tunjukkan kartu ini kepada petugas saat berkunjung ke perpustakaan fisik.</p>
                    </div>

                    <div class="flex justify-center items-center py-6">
                        <div class="w-full max-w-[450px] min-h-[280px] bg-slate-900 dark:bg-darkCard rounded-3xl relative overflow-hidden text-white shadow-xl border border-slate-700/50 p-6 flex flex-col justify-between" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                            <div class="absolute -right-16 -bottom-16 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
                            
                            <div class="flex justify-between items-start">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-book-open text-brand-400 text-lg"></i>
                                    <span class="text-xs font-black tracking-widest uppercase">LITERASIKU DIGITAL</span>
                                </div>
                                <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full ${
                                    currentUser.membership === 'Premium Scholar'
                                        ? 'bg-amber-400 text-slate-950 shadow-sm'
                                        : 'bg-slate-700 text-slate-300'
                                }">
                                    ${currentUser.membership === 'Premium Scholar' ? '👑 Premium' : 'Reguler'}
                                </span>
                            </div>

                            <div class="flex items-center space-x-4 my-6">
                                <div class="w-20 h-20 rounded-2xl bg-white/10 flex items-center justify-center border border-white/20 shadow-inner">
                                    <i class="fa-solid fa-user text-4xl text-brand-300"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-base font-extrabold truncate uppercase tracking-wide text-white">${currentUser.name}</h4>
                                    <span class="text-xs font-mono tracking-wider opacity-85 text-brand-200 block mt-0.5">${currentUser.nim}</span>
                                    <span class="text-[9px] text-slate-400 block mt-2 font-semibold">STATUS: AKTIF</span>
                                </div>
                                <div class="w-20 h-20 bg-white p-1 rounded-xl flex-shrink-0 flex items-center justify-center shadow-md">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=MEMBER_ID:${currentUser.id}|NIM:${currentUser.nim}|NAME:${encodeURIComponent(currentUser.name)}" alt="QR Code" class="w-full h-full object-contain">
                                </div>
                            </div>

                            <div class="flex justify-between items-center border-t border-white/10 pt-3 text-[9px] text-slate-400 uppercase tracking-widest">
                                <span>OFFICIAL DIGITAL MEMBER CARD</span>
                                <i class="fa-solid fa-wifi opacity-50 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-5 shadow-sm max-w-xl mx-auto space-y-3">
                        <h4 class="text-xs font-bold text-slate-800 dark:text-white flex items-center space-x-2">
                            <i class="fa-solid fa-circle-info text-brand-500"></i>
                            <span>Panduan Penggunaan Kartu</span>
                        </h4>
                        <ul class="text-xs text-slate-500 dark:text-slate-400 space-y-2 list-none pl-0">
                            <li class="flex items-start space-x-2">
                                <span class="text-emerald-500 font-bold">&#10003;</span>
                                <span>Gunakan QR Code di atas saat berkunjung ke perpustakaan fisik untuk melakukan absensi mandiri.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-emerald-500 font-bold">&#10003;</span>
                                <span>Tunjukkan kartu ke petugas untuk memverifikasi keanggotaan Premium Scholar Anda.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-emerald-500 font-bold">&#10003;</span>
                                <span>Anda dapat menyimpan kartu ini di handphone dengan melakukan tangkapan layar (screenshot).</span>
                            </li>
                        </ul>
                    </div>
                </div>
            `;
        }

        // Global variables for eReader simulated progress
        let activeReaderBook = null;
        let readerFontSize = 14; // Default Font size in PX
        let activeReaderTheme = "light";
        let mockPageCurrent = 1;
        let mockPageTotal = 30;

        // Onload Event Initialization
        window.onload = function() {
            initApp();
        };

        // Initialize Core App Properties
        function initApp() {
            detectSystemTheme();
            updateSidebarWidget();
            updateActiveView();
            initUserNotifications();
            showToast("Selamat datang kembali di LiterasiKu, " + currentUser.name, "success");

            // Auto hide flash alerts after 3 seconds (3000ms)
            setTimeout(() => {
                const successAlert = document.getElementById('flash-alert-success');
                const errorAlert = document.getElementById('flash-alert-error');
                if (successAlert) {
                    successAlert.style.opacity = '0';
                    successAlert.style.maxHeight = '0';
                    successAlert.style.paddingTop = '0';
                    successAlert.style.overflow = 'hidden';
                    setTimeout(() => successAlert.remove(), 500);
                }
                if (errorAlert) {
                    errorAlert.style.opacity = '0';
                    errorAlert.style.maxHeight = '0';
                    errorAlert.style.paddingTop = '0';
                    errorAlert.style.overflow = 'hidden';
                    setTimeout(() => errorAlert.remove(), 500);
                }
            }, 3000);
        }

        // Auto/detect default preferences
        function detectSystemTheme() {
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                isDarkMode = true;
            } else {
                document.documentElement.classList.remove('dark');
                isDarkMode = false;
            }
            updateThemeIcons();
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                isDarkMode = false;
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                isDarkMode = true;
            }
            updateThemeIcons();
            // Re-render dashboard chart to match active theme axes color
            if (currentActiveTab === "dashboard") {
                renderReadingChart();
            }
        }

        function updateThemeIcons() {
            const mobileIcon = document.getElementById("mobile-theme-icon");
            if (mobileIcon) {
                if (isDarkMode) {
                    mobileIcon.className = "fa-solid fa-sun text-amber-400";
                } else {
                    mobileIcon.className = "fa-solid fa-moon";
                }
            }
        }

        function toggleMobileMenu() {
            const menu = document.getElementById("mobile-menu");
            menu.classList.toggle("hidden");
        }

        function updateSidebarWidget() {
            document.getElementById("sidebar-username").innerText = currentUser.name;
            document.getElementById("sidebar-membership").innerText = currentUser.membership;
            document.getElementById("sidebar-avatar").src = currentUser.avatar;
        }

        // Global Navigation Coordinator
        function switchTab(tabName) {
            currentActiveTab = tabName;
            
            // Adjust active CSS states inside navigation buttons
            const buttons = document.querySelectorAll('.nav-btn');
            buttons.forEach(btn => {
                if (btn.id === `nav-${tabName}`) {
                    btn.className = "nav-btn w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400";
                } else {
                    btn.className = "nav-btn w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-800 dark:hover:text-slate-100";
                }
            });

            updateActiveView();
        }

        // Dispatch page view rendering based on state
        function updateActiveView() {
            const contentArea = document.getElementById("main-content");
            contentArea.innerHTML = ""; // Wipe current template

            switch (currentActiveTab) {
                case "dashboard":
                    renderDashboardView(contentArea);
                    break;
                case "catalog":
                    renderCatalogView(contentArea);
                    break;
                case "bookshelf":
                    renderBookshelfView(contentArea);
                    break;
                case "profile":
                    renderProfileInfoView(contentArea);
                    break;
                case "history":
                    renderHistoryView(contentArea);
                    break;
                default:
                    renderDashboardView(contentArea);
            }
        }

        // Programmatic Gradient Cover generator helper (Prevents blank placeholders)
        function generateBookCoverHTML(book, extraClasses = "w-full h-48") {
            return `
                <div class="relative ${extraClasses} rounded-xl bg-gradient-to-br ${book.colorGradient} p-4 flex flex-col justify-between shadow-md transition-all select-none overflow-hidden border border-white/10">
                    <div class="absolute -right-8 -bottom-8 w-24 h-24 rounded-full bg-white/5"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-[8px] font-bold tracking-widest uppercase text-white/50">${book.category}</span>
                        <i class="fa-solid fa-bookmark ${currentUser.favoriteBooks.includes(book.id) ? 'text-amber-400' : 'text-white/20'} text-xs cursor-pointer" onclick="event.stopPropagation(); toggleFavorite('${book.id}')"></i>
                    </div>
                    <div class="my-4">
                        <h4 class="text-xs md:text-sm font-extrabold ${book.textColor} tracking-tight leading-snug line-clamp-3">${book.title}</h4>
                    </div>
                    <div class="flex items-center justify-between mt-auto">
                        <span class="text-[9px] ${book.textColor} opacity-75 font-semibold truncate max-w-[80%]">${book.author}</span>
                        <div class="w-4 h-4 rounded bg-white/20 flex items-center justify-center">
                            <i class="fa-solid fa-feather text-[8px] text-white"></i>
                        </div>
                    </div>
                </div>
            `;
        }

        /* RENDER SUBSECTION: DASHBOARD VIEW */
        function renderDashboardView(container) {
            // Compute Statistics dynamic metadata values
            const totalBorrowedCount = currentUser.borrowedBooks.length;
            const goalPercent = Math.round((currentUser.readingGoal.current / currentUser.readingGoal.target) * 100);

            let activeReadingListHTML = "";
            let recommendedBooksHTML = "";

            // Render Currently Active Readings
            const activeReadings = booksData.filter(b => currentUser.borrowedBooks.includes(b.id) && b.progress > 0);
            if (activeReadings.length === 0) {
                activeReadingListHTML = `
                    <div class="col-span-full py-8 text-center bg-white dark:bg-darkCard rounded-2xl border border-slate-100 dark:border-darkBorder">
                        <i class="fa-solid fa-book-open-reader text-slate-300 text-3xl mb-2 block"></i>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Belum ada buku aktif yang dibaca saat ini.</p>
                        <button onclick="switchTab('catalog')" class="mt-3 px-4 py-1.5 text-xs bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition-all font-semibold">Temukan Buku</button>
                    </div>
                `;
            } else {
                activeReadings.forEach(book => {
                    activeReadingListHTML += `
                        <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-4 flex items-center space-x-4 hover:shadow-sm transition-all">
                            <div class="w-16 h-20 flex-shrink-0">
                                ${generateBookCoverHTML(book, "w-full h-full")}
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[9px] font-bold text-brand-600 dark:text-brand-400 uppercase tracking-widest">${book.category}</span>
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate mt-0.5">${book.title}</h4>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">${book.author}</p>
                                
                                <div class="mt-3">
                                    <div class="flex justify-between items-center text-[10px] text-slate-500 dark:text-slate-400 mb-1">
                                        <span class="font-semibold">${book.progress}% Selesai</span>
                                        <span>Hal. ${Math.round(book.pages * (book.progress/100))}/${book.pages}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-brand-500 h-full rounded-full" style="width: ${book.progress}%"></div>
                                    </div>
                                </div>
                            </div>
                            <button onclick="openEReaderSim('${book.id}')" class="w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-950/40 text-brand-500 hover:bg-brand-500 hover:text-white flex items-center justify-center transition-all flex-shrink-0" title="Lanjut Membaca">
                                <i class="fa-solid fa-play text-xs pl-0.5"></i>
                            </button>
                        </div>
                    `;
                });
            }

            // Render AI Recommended Books
            const recommended = booksData.filter(b => b.popular).slice(0, 4);
            recommended.forEach(book => {
                recommendedBooksHTML += `
                    <div onclick="openBookModal('${book.id}')" class="book-card-3d bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-3 flex flex-col justify-between cursor-pointer shadow-sm hover:shadow-md transition-all">
                        <div class="w-full h-44 mb-3">
                            ${generateBookCoverHTML(book, "w-full h-full")}
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-brand-600 dark:text-brand-400 tracking-wider uppercase">${book.category}</span>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-1 line-clamp-1">${book.title}</h4>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-1">${book.author}</p>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-slate-50 dark:border-darkBorder/60">
                            <span class="text-[10px] text-slate-400 font-bold flex items-center space-x-1">
                                <i class="fa-solid fa-star text-amber-400"></i>
                                <span>${book.rating}</span>
                            </span>
                            <span class="text-[9px] ${book.status === 'Tersedia' ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400'} px-2 py-0.5 rounded font-bold">${book.status}</span>
                        </div>
                    </div>
                `;
            });

            // Set main outer structure HTML layout for Dashboard
            container.innerHTML = `
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-brand-600 to-indigo-800 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-lg">
                    <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/5"></div>
                    <div class="absolute right-12 top-4 w-32 h-32 rounded-full bg-indigo-500/10 blur-xl"></div>
                    <div class="relative z-10 max-w-xl">
                        <span class="bg-white/20 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">HAK AKSES RESMI SCHOLAR</span>
                        <h2 class="text-2xl md:text-3xl font-extrabold mt-4 leading-tight">Selamat Membaca Kembali, <br>${currentUser.name}!</h2>
                        <p class="text-indigo-100 text-xs md:text-sm mt-2 font-medium">Bulan ini target membaca akademik Anda meningkat 15%. Akses jurnal berbayar global kini aktif.</p>
                        <button onclick="switchTab('catalog')" class="mt-6 px-6 py-2.5 bg-white text-brand-600 rounded-xl text-xs font-bold hover:shadow-lg hover:scale-105 transition-all">Eksplor Referensi Baru</button>
                    </div>
                </div>

                <!-- Numerical Statistics -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-4 flex items-center justify-between shadow-sm">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block">BUKU DIPINJAM</span>
                            <span class="text-2xl font-black text-slate-800 dark:text-white mt-1 block">${totalBorrowedCount}</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-brand-500 flex items-center justify-center">
                            <i class="fa-solid fa-book text-base"></i>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-4 flex items-center justify-between shadow-sm">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block">TARGET MEMBACA</span>
                            <span class="text-2xl font-black text-slate-800 dark:text-white mt-1 block">${currentUser.readingGoal.current}/${currentUser.readingGoal.target}</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-500 flex items-center justify-center">
                            <i class="fa-solid fa-bullseye text-base"></i>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-4 flex items-center justify-between shadow-sm">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block">PRESTASI SKOR</span>
                            <span class="text-2xl font-black text-slate-800 dark:text-white mt-1 block">4.920</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 flex items-center justify-center">
                            <i class="fa-solid fa-award text-base"></i>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-4 flex items-center justify-between shadow-sm">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block">TARGET SELESAI</span>
                            <span class="text-2xl font-black text-slate-800 dark:text-white mt-1 block">${goalPercent}%</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-cyan-50 dark:bg-cyan-950/40 text-cyan-500 flex items-center justify-center">
                            <i class="fa-solid fa-percent text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- Chart.js Activity Section and Active Books shelf -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Activity Graph -->
                    <div class="lg:col-span-2 bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Analisis Aktivitas Membaca</h3>
                                <p class="text-[11px] text-slate-400">Total durasi membaca mingguan (menit)</p>
                            </div>
                            <span class="text-[10px] bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg text-slate-500 dark:text-slate-400 font-bold">7 Hari Terakhir</span>
                        </div>
                        <div class="h-64">
                            <canvas id="readingAnalyticsChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Bookshelf Right sidebar quick action status -->
                    <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">Mulai Membaca Kembali</h3>
                            <p class="text-[11px] text-slate-400 mb-4">Lanjutkan lembaran halaman dari buku Anda sebelumnya</p>
                            <div class="space-y-3">
                                ${activeReadingListHTML}
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-darkBorder flex justify-between items-center text-xs">
                            <span class="text-slate-400 font-semibold">Tantangan membaca</span>
                            <span class="text-brand-600 dark:text-brand-400 font-bold">${currentUser.readingGoal.current} dari ${currentUser.readingGoal.target} buku</span>
                        </div>
                    </div>
                </div>

                <!-- Personalized AI Curated Recommendations Section -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Rekomendasi Cerdas AI</h3>
                            <p class="text-[11px] text-slate-400">Kurasi khusus berdasarkan minat dan bidang akademik Anda</p>
                        </div>
                        <button onclick="switchTab('catalog')" class="text-xs text-brand-600 dark:text-brand-400 font-bold hover:underline">Eksplor Semua <i class="fa-solid fa-arrow-right ml-1"></i></button>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        ${recommendedBooksHTML}
                    </div>
                </div>
            `;

            // Instantiate Chart.js analytics graph dynamically
            setTimeout(renderReadingChart, 50);
        }

        // Render Dynamic Analytics Chart using Canvas
        function renderReadingChart() {
            const chartCanvas = document.getElementById("readingAnalyticsChart");
            if (!chartCanvas) return;

            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#222b44' : '#f1f5f9';
            const labelColor = isDark ? '#94a3b8' : '#64748b';

            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Durasi Membaca',
                        data: [45, 60, 30, 90, 40, 120, 75],
                        borderColor: '#6366f1',
                        borderWidth: 3,
                        backgroundColor: 'rgba(99, 102, 241, 0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#4f46e5'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            grid: { color: gridColor },
                            ticks: { color: labelColor, font: { family: 'Plus Jakarta Sans', size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: labelColor, font: { family: 'Plus Jakarta Sans', size: 10 } }
                        }
                    }
                }
            });
        }

        /* RENDER SUBSECTION: EXPLORE CATALOG VIEW */
        function renderCatalogView(container) {
            // Loaded dynamically from Laragon perpustakaan MySQL database
            const categories = @json($formattedCategories);
            
            let categoryFiltersHTML = "";
            categories.forEach(cat => {
                const isActive = activeFilterCategory === cat;
                categoryFiltersHTML += `
                    <button onclick="setFilterCategory('${cat}')" class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all ${
                        isActive 
                            ? 'bg-brand-600 text-white shadow-sm shadow-brand-600/10' 
                            : 'bg-white dark:bg-darkCard border border-slate-200 dark:border-darkBorder text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'
                    }">
                        ${cat}
                    </button>
                `;
            });

            // Filter database list dynamically based on Search query & selected Category tab
            let filteredBooks = booksData.filter(book => {
                const matchesSearch = book.title.toLowerCase().includes(searchQuery.toLowerCase()) || 
                                      book.author.toLowerCase().includes(searchQuery.toLowerCase()) ||
                                      book.publisher.toLowerCase().includes(searchQuery.toLowerCase());
                const matchesCategory = activeFilterCategory === "Semua" || book.category === activeFilterCategory;
                return matchesSearch && matchesCategory;
            });

            // Apply catalog sorting options
            if (sortOption === "rating-desc") {
                filteredBooks.sort((a, b) => b.rating - a.rating);
            } else if (sortOption === "title-asc") {
                filteredBooks.sort((a, b) => a.title.localeCompare(b.title));
            } else if (sortOption === "year-desc") {
                filteredBooks.sort((a, b) => b.year - a.year);
            }

            // Catalog Grid Generation
            let catalogGridHTML = "";
            if (filteredBooks.length === 0) {
                catalogGridHTML = `
                    <div class="col-span-full py-16 text-center bg-white dark:bg-darkCard rounded-3xl border border-slate-100 dark:border-darkBorder">
                        <i class="fa-solid fa-magnifying-glass-minus text-slate-300 text-4xl mb-3 block"></i>
                        <p class="text-sm text-slate-800 dark:text-slate-200 font-bold">Buku tidak ditemukan</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter kategori Anda.</p>
                        <button onclick="resetFilters()" class="mt-4 px-4 py-2 bg-brand-500 text-white rounded-xl text-xs font-bold hover:bg-brand-600 transition-all">Atur Ulang Pencarian</button>
                    </div>
                `;
            } else {
                filteredBooks.forEach(book => {
                    catalogGridHTML += `
                        <div onclick="openBookModal('${book.id}')" class="book-card-3d bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-4 flex flex-col justify-between cursor-pointer shadow-sm hover:shadow-md transition-all">
                            <div class="w-full h-48 mb-4">
                                ${generateBookCoverHTML(book, "w-full h-full")}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-bold text-brand-600 dark:text-brand-400 tracking-wider uppercase">${book.category}</span>
                                    <span class="text-[8px] font-bold px-1.5 py-0.5 rounded-full ${
                                        book.is_ebook 
                                            ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400' 
                                            : 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400'
                                    } flex items-center space-x-1 flex-shrink-0">
                                        <i class="${book.is_ebook ? 'fa-solid fa-tablet-screen-button' : 'fa-solid fa-book'} text-[7px]"></i>
                                        <span>${book.is_ebook ? 'E-Book' : 'Fisik'}</span>
                                    </span>
                                </div>
                                <h4 class="text-xs font-extrabold text-slate-800 dark:text-slate-100 mt-1.5 line-clamp-2 leading-snug">${book.title}</h4>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">${book.author}</p>
                                ${book.is_ebook ? `
                                <div class="mt-3">
                                    <button onclick="event.stopPropagation(); openRealReaderById('${book.id}')" class="w-full py-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl flex items-center justify-center space-x-1.5 transition-all shadow-md shadow-brand-600/10">
                                        <i class="fa-solid fa-book-open text-[10px]"></i>
                                        <span>Baca Ebook</span>
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-darkBorder/40 flex items-center justify-between">
                                <div class="flex items-center space-x-1">
                                    <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">${book.rating}</span>
                                    <span class="text-[10px] text-slate-400">(${book.reviewsCount})</span>
                                </div>
                                <span class="text-[9px] ${book.status === 'Tersedia' ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400'} px-2 py-0.5 rounded font-bold">${book.status}</span>
                            </div>
                        </div>
                    `;
                });
            }

            container.innerHTML = `
                <!-- Advanced Filters Header -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Katalog Referensi Terintegrasi</h2>
                        <p class="text-xs text-slate-400">Akses koleksi buku, literatur riset, dan panduan akademik berlisensi resmi.</p>
                    </div>

                    <!-- Search Input for Mobile/Tablet layout inside content -->
                    <div class="md:hidden">
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" oninput="handleSearch(event)" value="${searchQuery}" placeholder="Cari buku, penulis..." class="w-full pl-9 pr-4 py-2 text-xs bg-white dark:bg-darkCard border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none dark:text-slate-200 shadow-sm">
                        </div>
                    </div>

                    <!-- Dynamic Layout Filter bar -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                        <!-- Horizontal Scrollable Category filters -->
                        <div class="flex items-center space-x-2 overflow-x-auto pb-1 max-w-full no-scrollbar">
                            ${categoryFiltersHTML}
                        </div>
                        
                        <!-- Sort drop-down widget -->
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <span class="text-xs font-semibold text-slate-400">Urutkan:</span>
                            <select onchange="handleSortChange(event)" class="text-xs font-bold bg-white dark:bg-darkCard border border-slate-200 dark:border-darkBorder rounded-lg p-2 text-slate-600 dark:text-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-500 shadow-sm">
                                <option value="rating-desc" ${sortOption === 'rating-desc' ? 'selected' : ''}>Rating Tertinggi</option>
                                <option value="title-asc" ${sortOption === 'title-asc' ? 'selected' : ''}>Judul (A-Z)</option>
                                <option value="year-desc" ${sortOption === 'year-desc' ? 'selected' : ''}>Tahun (Terbaru)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Book Catalog Output Grid Layout -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    ${catalogGridHTML}
                </div>
            `;
        }

        // Handle typing events dynamically for searching
        function handleSearch(event) {
            searchQuery = event.target.value;
            // Force re-render of catalog only if on catalog view, otherwise switch
            if (currentActiveTab !== "catalog") {
                switchTab("catalog");
            } else {
                updateActiveView();
            }
        }

        function setFilterCategory(category) {
            activeFilterCategory = category;
            updateActiveView();
        }

        function handleSortChange(event) {
            sortOption = event.target.value;
            updateActiveView();
        }

        function resetFilters() {
            searchQuery = "";
            activeFilterCategory = "Semua";
            sortOption = "rating-desc";
            updateActiveView();
        }

        /* RENDER SUBSECTION: MY BOOKSHELF */
        /* RENDER SUBSECTION: DENDA PANEL */
        let activeDendaPaymentMethod = 'qris';
        let dendaTimerInterval = null;
        let dendaSecondsRemaining = 899; // 14:59

        function startDendaTimer() {
            if (dendaTimerInterval) clearInterval(dendaTimerInterval);
            dendaSecondsRemaining = 899;
            const timerEl = document.getElementById("denda-timer");
            if (!timerEl) return;
            
            dendaTimerInterval = setInterval(() => {
                if (dendaSecondsRemaining <= 0) {
                    clearInterval(dendaTimerInterval);
                    timerEl.innerText = "00:00";
                    return;
                }
                dendaSecondsRemaining--;
                const mins = Math.floor(dendaSecondsRemaining / 60);
                const secs = dendaSecondsRemaining % 60;
                timerEl.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }, 1000);
        }

        function selectDendaPaymentMethod(method) {
            activeDendaPaymentMethod = method;
            
            // Manage form view
            const qrisForm = document.getElementById("denda-form-qris");
            const ewalletForm = document.getElementById("denda-form-ewallet");
            const vaForm = document.getElementById("denda-form-va");
            
            if (qrisForm) qrisForm.classList.add("hidden");
            if (ewalletForm) ewalletForm.classList.add("hidden");
            if (vaForm) vaForm.classList.add("hidden");
            
            if (method === 'qris') {
                if (qrisForm) qrisForm.classList.remove("hidden");
                startDendaTimer();
            } else if (method === 'ewallet') {
                if (ewalletForm) ewalletForm.classList.remove("hidden");
            } else if (method === 'va') {
                if (vaForm) vaForm.classList.remove("hidden");
                generateDendaVaNumber();
            }

            // Update buttons border styling
            const btnQris = document.getElementById("btn-denda-pay-qris");
            const btnEwallet = document.getElementById("btn-denda-pay-ewallet");
            const btnVa = document.getElementById("btn-denda-pay-va");
            
            if (btnQris) btnQris.className = "border border-slate-100 hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700 rounded-xl p-3 flex flex-col items-center justify-center gap-1 transition text-slate-500 bg-transparent";
            if (btnEwallet) btnEwallet.className = "border border-slate-100 hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700 rounded-xl p-3 flex flex-col items-center justify-center gap-1 transition text-slate-500 bg-transparent";
            if (btnVa) btnVa.className = "border border-slate-100 hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700 rounded-xl p-3 flex flex-col items-center justify-center gap-1 transition text-slate-500 bg-transparent";

            const activeBtn = document.getElementById(`btn-denda-pay-${method}`);
            if (activeBtn) activeBtn.className = "border-2 border-brand-500 bg-brand-50/50 dark:bg-brand-950/20 rounded-xl p-3 flex flex-col items-center justify-center gap-1 transition text-brand-600 dark:text-brand-400 font-bold";

            // Update checkout label
            let methodLabel = "QRIS Dinamis";
            if (method === 'ewallet') methodLabel = "E-Wallet Transfer";
            if (method === 'va') methodLabel = "Virtual Account Bank";
            
            const methodText = document.getElementById("selected-denda-payment-text");
            if (methodText) methodText.innerText = methodLabel;
        }

        function generateDendaVaNumber() {
            const selectedBank = document.getElementById("denda-va-bank").value;
            let code = "880120";
            if (selectedBank === 'mandiri') code = "890120";
            if (selectedBank === 'bni') code = "820120";
            
            const vaInput = document.getElementById("denda-va-number-input");
            if (vaInput) vaInput.value = `${code}${currentUser.nim.replace(/[^0-9]/g, '').padEnd(10, '0')}`;
        }

        function copyDendaVaNumber() {
            const vaInput = document.getElementById("denda-va-number-input");
            if (!vaInput) return;
            vaInput.select();
            document.execCommand('copy');
            showToast("Nomor Virtual Account berhasil disalin!", "success");
        }

        function recalculateDendaTotal() {
            const checkboxes = document.querySelectorAll(".denda-checkbox");
            let grandTotal = 0;
            let checkedCount = 0;

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    grandTotal += parseInt(cb.getAttribute("data-fine"), 10);
                    checkedCount++;
                }
            });

            const checkoutDisplay = document.getElementById("denda-total-checkout-display");
            if (checkoutDisplay) checkoutDisplay.innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;

            const countDisplay = document.getElementById("denda-selected-books-count");
            if (countDisplay) countDisplay.innerText = `${checkedCount} Buku`;

            const payBtn = document.getElementById("btn-denda-submit-payment");
            if (payBtn) {
                if (grandTotal === 0) {
                    payBtn.disabled = true;
                    payBtn.className = "w-full py-3 bg-slate-300 dark:bg-slate-800 text-slate-500 dark:text-slate-500 rounded-xl text-xs font-extrabold cursor-not-allowed transition flex items-center justify-center gap-2";
                } else {
                    payBtn.disabled = false;
                    payBtn.className = "w-full py-3 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-brand-500/10 transition flex items-center justify-center gap-2 cursor-pointer";
                }
            }
        }

        function toggleSelectAllDenda() {
            const checkboxes = document.querySelectorAll(".denda-checkbox");
            if (checkboxes.length === 0) return;
            const areAllChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(cb => {
                cb.checked = !areAllChecked;
            });

            recalculateDendaTotal();
            showToast(areAllChecked ? "Pilihan buku dikosongkan" : "Seluruh denda buku terpilih!", "info");
        }

        function processDendaPayment() {
            const checkboxes = document.querySelectorAll(".denda-checkbox:checked");
            if (checkboxes.length === 0) {
                showToast("Pilih denda buku yang ingin dibayar terlebih dahulu!", "error");
                return;
            }

            const txId = checkboxes[0].getAttribute("data-tx-id");
            window.location.href = `/denda/bayar/` + txId;
        }

        function renderBookshelfView(container) {
            const historicalRecords = @json($historyRecords);
            const unpaidLoans = historicalRecords.filter(t => t.fine > 0 && !t.fine_paid);
            
            let lateBooksHTML = "";
            let totalFines = 0;
            let lateCount = 0;

            if (unpaidLoans.length === 0) {
                lateBooksHTML = `
                    <div class="py-12 text-center bg-white dark:bg-darkCard rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto text-xl mb-3">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">Anda Bebas Denda</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Selamat! Anda tidak memiliki tunggakan denda keterlambatan pengembalian buku saat ini.</p>
                    </div>
                `;
            } else {
                unpaidLoans.forEach(tx => {
                    totalFines += tx.fine;
                    lateCount++;
                    const lateDays = Math.max(1, Math.ceil(tx.fine / 1000));
                    
                    lateBooksHTML += `
                        <!-- Card Book (Late) -->
                        <div class="border border-slate-150 dark:border-darkBorder p-4 sm:p-5 rounded-2xl bg-white dark:bg-darkCard hover:shadow-md transition flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between relative overflow-hidden">
                            <div class="flex gap-4 items-center">
                                <!-- Checkbox selection -->
                                <input type="checkbox" checked onchange="recalculateDendaTotal()" data-tx-id="${tx.id}" data-fine="${tx.fine}" class="denda-checkbox w-5 h-5 rounded-md border-slate-350 dark:border-slate-650 text-brand-600 focus:ring-brand-500 cursor-pointer">
                                
                                <div class="w-12 h-16 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-lg text-white flex flex-col items-center justify-center p-1 text-center shadow-inner flex-shrink-0">
                                    <i class="fa-solid fa-book text-lg mb-0.5"></i>
                                    <span class="text-[6px] font-bold leading-none uppercase">CETAK</span>
                                </div>
                                <div class="space-y-1">
                                    <span class="inline-block bg-amber-500/10 text-amber-700 dark:text-amber-400 text-[9px] font-bold px-2 py-0.5 rounded-full">Buku Cetak</span>
                                    <h4 class="font-bold text-slate-850 dark:text-slate-100 text-xs sm:text-sm">${tx.title}</h4>
                                    <p class="text-[10px] text-slate-400">ID Pinjam: ${tx.id} • Jatuh Tempo: ${tx.due_date}</p>
                                </div>
                            </div>
                            <div class="flex sm:flex-col items-end justify-between sm:justify-center w-full sm:w-auto border-t sm:border-t-0 border-slate-100 dark:border-slate-800/40 pt-3 sm:pt-0">
                                <span class="text-xs bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 px-2.5 py-1 rounded-full font-bold text-[10px] sm:mb-1">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Telat ${lateDays} Hari
                                </span>
                                <div class="text-right">
                                    <p class="text-[9px] text-slate-400">Total Denda</p>
                                    <p class="text-sm font-extrabold text-rose-600 dark:text-rose-400">Rp ${tx.fine.toLocaleString('id-ID')}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            // Circulation Status
            let circulationStatus = "Aktif";
            let circulationClass = "text-emerald-600 dark:text-emerald-400";
            let circulationBg = "bg-emerald-50 dark:bg-emerald-950/20";
            let circulationDesc = "Sirkulasi lancar. Anda dapat meminjam buku fisik & digital.";
            let circulationIcon = "fa-solid fa-circle-check";
            
            if (totalFines > 20000) {
                circulationStatus = "Ditangguhkan";
                circulationClass = "text-amber-600 dark:text-amber-400";
                circulationBg = "bg-amber-50 dark:bg-amber-950/20";
                circulationDesc = "Selesaikan denda untuk dapat meminjam kembali.";
                circulationIcon = "fa-solid fa-circle-pause";
            }

            container.innerHTML = `
                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Denda & Keterlambatan</h2>
                        <p class="text-xs text-slate-400">Daftar denda keterlambatan pengembalian buku dan status sirkulasi Anda.</p>
                    </div>

                    <!-- Upper Quick Cards Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Total Fines Card -->
                        <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-5 shadow-sm flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Denda Berjalan</span>
                                <p class="text-2xl font-extrabold text-rose-600 dark:text-rose-400" id="denda-total-fines-display">Rp ${totalFines.toLocaleString('id-ID')}</p>
                                <span class="text-[10px] text-slate-400 block"><strong class="text-slate-650 dark:text-slate-350">${lateCount} Buku</strong> Terlambat Pengembalian</span>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                        </div>

                        <!-- Membership Status Card -->
                        <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-5 shadow-sm flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Status Akses Sirkulasi</span>
                                <p class="text-lg font-extrabold ${circulationClass} flex items-center gap-1">
                                    <i class="${circulationIcon} text-sm"></i> ${circulationStatus}
                                </p>
                                <p class="text-[10px] text-slate-400">${circulationDesc}</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl ${circulationBg} flex items-center justify-center text-xl">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                        </div>

                        <!-- Grace Period Card -->
                        <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-5 shadow-sm flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Diskon / Program Bebas Denda</span>
                                <p class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400">Hari Kemerdekaan</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">Bebas denda pengembalian s/d 17 Agustus 2026!</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                                <i class="fa-solid fa-percent"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Multi-payment & Late Books Feed -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        
                        <!-- Left: Book list containing late statuses -->
                        <div class="xl:col-span-2 space-y-4">
                            <div class="flex items-center justify-between px-1">
                                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Buku Terlambat & Denda Aktif</h2>
                                <button onclick="toggleSelectAllDenda()" class="text-xs text-brand-600 hover:text-brand-700 font-bold transition">Pilih Semua Buku</button>
                            </div>

                            <div class="space-y-3">
                                ${lateBooksHTML}
                            </div>

                            <!-- Info Area / Alternative Action -->
                            <div class="bg-indigo-50/40 dark:bg-indigo-950/10 border border-indigo-100 dark:border-indigo-950/40 rounded-2xl p-4 text-xs text-indigo-850 dark:text-indigo-350 flex items-start gap-3">
                                <i class="fa-solid fa-info-circle text-base text-brand-500 mt-0.5"></i>
                                <div>
                                    <p class="font-bold">Kehilangan Buku Cetak?</p>
                                    <p class="text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Jika Anda tidak sengaja menghilangkan atau merusak buku yang sedang dipinjam, segera lakukan pelaporan agar dapat dilakukan penyesuaian biaya ganti rugi sesuai kebijakan resmi perpustakaan.</p>
                                    <button class="mt-2 text-xs font-extrabold text-brand-600 hover:underline">
                                        Hubungi Pustakawan untuk Pelaporan &rarr;
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Pay Panel / Checkout Container -->
                        <div class="space-y-4">
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider px-1">Ringkasan Pembayaran</h2>
                            <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-5 shadow-sm space-y-4 relative">
                                
                                <!-- Payment Checklist breakdown -->
                                <div class="space-y-2 text-xs pb-3 border-b border-slate-100 dark:border-darkBorder">
                                    <div class="flex justify-between text-slate-400">
                                        <span>Metode Pembayaran</span>
                                        <span class="font-bold text-slate-700 dark:text-slate-350" id="selected-denda-payment-text">QRIS Dinamis</span>
                                    </div>
                                    <div class="flex justify-between text-slate-400">
                                        <span>Jumlah Buku Terpilih</span>
                                        <span class="font-bold text-slate-700 dark:text-slate-350" id="denda-selected-books-count">${lateCount} Buku</span>
                                    </div>
                                </div>

                                <!-- Pricing Total -->
                                <div class="flex justify-between items-center py-2">
                                    <div>
                                        <span class="text-xs text-slate-400 font-semibold block">Total yang Harus Dibayar</span>
                                        <span class="text-[10px] text-brand-600 font-bold">*Bebas biaya admin</span>
                                    </div>
                                    <span class="text-xl font-extrabold text-slate-800 dark:text-slate-100" id="denda-total-checkout-display">Rp ${totalFines.toLocaleString('id-ID')}</span>
                                </div>

                                <!-- Payment Channels Selector -->
                                <div class="space-y-2">
                                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block">Pilih Saluran Pembayaran</span>
                                    
                                    <div class="grid grid-cols-3 gap-2">
                                        <!-- QRIS -->
                                        <button onclick="selectDendaPaymentMethod('qris')" id="btn-denda-pay-qris" class="payment-method-btn border-2 border-brand-500 bg-brand-50/50 dark:bg-brand-950/20 rounded-xl p-3 flex flex-col items-center justify-center gap-1 transition text-brand-600 dark:text-brand-400 font-bold">
                                            <i class="fa-solid fa-qrcode text-lg"></i>
                                            <span class="text-[9px] font-bold text-slate-700 dark:text-slate-300">QRIS</span>
                                        </button>
                                        <!-- E-Wallet -->
                                        <button onclick="selectDendaPaymentMethod('ewallet')" id="btn-denda-pay-ewallet" class="payment-method-btn border border-slate-150 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-xl p-3 flex flex-col items-center justify-center gap-1 transition text-slate-500 dark:text-slate-400 bg-transparent">
                                            <i class="fa-solid fa-wallet text-lg text-slate-500 dark:text-slate-400"></i>
                                            <span class="text-[9px] font-bold text-slate-700 dark:text-slate-300">E-Wallet</span>
                                        </button>
                                        <!-- VA -->
                                        <button onclick="selectDendaPaymentMethod('va')" id="btn-denda-pay-va" class="payment-method-btn border border-slate-150 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-xl p-3 flex flex-col items-center justify-center gap-1 transition text-slate-500 dark:text-slate-400 bg-transparent">
                                            <i class="fa-solid fa-building-columns text-lg text-slate-500 dark:text-slate-400"></i>
                                            <span class="text-[9px] font-bold text-slate-700 dark:text-slate-300">Virtual Acc</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Dynamic forms based on selection -->
                                <!-- QRIS Area -->
                                <div id="denda-form-qris" class="payment-form bg-slate-50 dark:bg-slate-800/40 p-4 rounded-xl border border-slate-100 dark:border-darkBorder space-y-3 text-center">
                                    <p class="text-[10px] text-slate-400">Pindai kode QRIS di bawah menggunakan aplikasi e-wallet (GoPay, OVO, ShopeePay, LinkAja) atau Mobile Banking Anda.</p>
                                    <div class="bg-white p-3 rounded-xl border border-slate-200 w-32 h-32 mx-auto flex items-center justify-center relative shadow-sm">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=LITERASIKU_DENDA_${totalFines}" alt="QRIS Denda" class="w-28 h-28">
                                    </div>
                                    <div class="flex justify-center items-center gap-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                                        <i class="fa-solid fa-shield-halved text-emerald-500"></i> Terenkripsi & Aman oleh LiteraPay
                                    </div>
                                </div>

                                <!-- E-Wallet Area -->
                                <div id="denda-form-ewallet" class="payment-form hidden bg-slate-50 dark:bg-slate-800/40 p-4 rounded-xl border border-slate-100 dark:border-darkBorder space-y-3">
                                    <div class="space-y-1">
                                        <label class="block font-bold text-slate-500 dark:text-slate-400">Pilih Provider</label>
                                        <select class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300 focus:outline-none focus:border-brand-500">
                                            <option value="gopay">GoPay</option>
                                            <option value="ovo">OVO</option>
                                            <option value="dana">DANA</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block font-bold text-slate-500 dark:text-slate-400">Nomor Handphone Terdaftar</label>
                                        <input type="text" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300 focus:outline-none focus:border-brand-500" placeholder="Contoh: 08123456789">
                                    </div>
                                </div>

                                <!-- Virtual Account Area -->
                                <div id="denda-form-va" class="payment-form hidden bg-slate-50 dark:bg-slate-800/40 p-4 rounded-xl border border-slate-100 dark:border-darkBorder space-y-3">
                                    <div class="space-y-1">
                                        <label class="block font-bold text-slate-500 dark:text-slate-400">Pilih Bank</label>
                                        <select id="denda-va-bank" onchange="generateDendaVaNumber()" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300 focus:outline-none focus:border-brand-500">
                                            <option value="bca">BCA Virtual Account</option>
                                            <option value="mandiri">Mandiri Virtual Account</option>
                                            <option value="bni">BNI Virtual Account</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block font-bold text-slate-500 dark:text-slate-400">Nomor Virtual Account</label>
                                        <div class="flex gap-2">
                                            <input type="text" id="denda-va-number-input" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-750 dark:text-slate-250 font-mono font-bold text-center focus:outline-none" value="8801208123456789" readonly>
                                            <button onclick="copyDendaVaNumber()" class="px-3 bg-brand-500 hover:bg-brand-600 text-white rounded-lg transition" title="Salin Kode"><i class="fa-regular fa-copy"></i></button>
                                        </div>
                                        <span class="text-[8px] text-slate-400 block">*Instruksi transfer akan muncul secara detail setelah Anda menekan tombol bayar.</span>
                                    </div>
                                </div>

                                <!-- Action Pay Button -->
                                <button onclick="processDendaPayment()" id="btn-denda-submit-payment" class="w-full py-3 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-brand-500/10 transition duration-200 flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-credit-card"></i> Bayar Denda Sekarang
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Info Desk: Kebijakan Denda -->
                    <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-6 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-circle-info text-brand-600"></i> Ketentuan & Aturan Tarif Denda (Umum)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs text-slate-500 dark:text-slate-400">
                            <div class="space-y-1 p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-darkBorder">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200">1. Keterlambatan Pengembalian</h4>
                                <p>Tarif denda keterlambatan buku cetak adalah <strong class="text-brand-600">Rp 1.000 / hari</strong> per buku, dihitung dari tanggal tenggat pengembalian.</p>
                            </div>
                            <div class="space-y-1 p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-darkBorder">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200">2. Kehilangan / Kerusakan Buku</h4>
                                <p>Pengguna wajib mengganti dengan buku bersubjek sama atau membayar denda ganti rugi sebesar <strong class="text-brand-600">1.5x harga buku asli</strong>.</p>
                            </div>
                            <div class="space-y-1 p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-darkBorder">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200">3. Pembekuan Hak Peminjaman</h4>
                                <p>Akses peminjaman buku digital (e-book) dan pemesanan buku fisik akan <strong class="text-rose-600">dibekukan sementara</strong> jika denda akumulatif melebih <strong class="text-rose-600">Rp 20.000</strong>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Recalculate and start timer if applicable
            recalculateDendaTotal();
            if (unpaidLoans.length > 0) {
                startDendaTimer();
            }
        }

        // NOTIFICATION HANDLERS
        let userNotifications = [];

        function initUserNotifications() {
            userNotifications = [];
            
            // 1. Check pending loans
            const pendingLoans = currentUser.pendingBooks || [];
            if (pendingLoans.length > 0) {
                pendingLoans.forEach(bookId => {
                    const book = booksData.find(b => b.id === bookId);
                    if (book) {
                        userNotifications.push({
                            id: 'pending-' + bookId,
                            title: 'Peminjaman Tertunda',
                            text: `Pinjaman buku "${book.title}" sedang menunggu persetujuan admin.`,
                            time: 'Baru saja',
                            type: 'pending',
                            read: false
                        });
                    }
                });
            }

            // 2. Check unpaid fines
            const historicalRecords = @json($historyRecords);
            const unpaidLoans = historicalRecords.filter(t => t.fine > 0 && !t.fine_paid);
            if (unpaidLoans.length > 0) {
                const totalFines = unpaidLoans.reduce((sum, t) => sum + t.fine, 0);
                userNotifications.push({
                    id: 'fine-unpaid',
                    title: 'Tagihan Denda Aktif',
                    text: `Anda memiliki denda berjalan sebesar Rp ${totalFines.toLocaleString('id-ID')}. Mohon segera bayar denda.`,
                    time: '1 jam lalu',
                    type: 'fine',
                    read: false
                });
            }

            // 3. Check Premium status
            if (currentUser.membership === "Premium Scholar") {
                userNotifications.push({
                    id: 'premium-active',
                    title: 'Layanan Premium Aktif',
                    text: 'Status Premium Scholar Anda aktif. Anda bebas membaca E-Book tanpa batas halaman!',
                    time: 'Hari Ini',
                    type: 'premium',
                    read: false
                });
            } else {
                userNotifications.push({
                    id: 'premium-promo',
                    title: 'Rekomendasi Layanan',
                    text: 'Aktivasi Premium Scholar untuk menikmati pembaca E-Book penuh hingga ratusan halaman.',
                    time: 'Kemarin',
                    type: 'promo',
                    read: true
                });
            }

            // Add a default welcome notif if empty
            if (userNotifications.length === 0) {
                userNotifications.push({
                    id: 'welcome',
                    title: 'Selamat Datang',
                    text: 'Selamat datang kembali di portal akademik perpustakaan digital LiterasiKu!',
                    time: 'Baru saja',
                    type: 'welcome',
                    read: false
                });
            }

            renderNotifications();
        }

        function renderNotifications() {
            const listContainer = document.getElementById("notif-items-list");
            if (!listContainer) return;

            const badge = document.getElementById("notif-badge");
            const unreadCount = userNotifications.filter(n => !n.read).length;
            
            if (badge) {
                if (unreadCount > 0) {
                    badge.classList.remove("hidden");
                } else {
                    badge.classList.add("hidden");
                }
            }

            if (userNotifications.length === 0) {
                listContainer.innerHTML = `
                    <div class="p-6 text-center text-slate-400 dark:text-slate-500 italic">
                        Tidak ada notifikasi.
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = userNotifications.map(n => {
                let iconClass = "fa-solid fa-bell text-brand-500 bg-brand-50 dark:bg-brand-950/20";
                if (n.type === 'pending') iconClass = "fa-solid fa-hourglass-half text-amber-500 bg-amber-50 dark:bg-amber-950/20";
                if (n.type === 'fine') iconClass = "fa-solid fa-triangle-exclamation text-rose-500 bg-rose-50 dark:bg-rose-950/20";
                if (n.type === 'premium') iconClass = "fa-solid fa-crown text-amber-500 bg-amber-50 dark:bg-amber-950/20";
                if (n.type === 'welcome') iconClass = "fa-solid fa-handshake text-emerald-500 bg-emerald-50 dark:bg-emerald-950/20";

                return `
                    <div class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors flex items-start gap-3 ${!n.read ? 'bg-slate-50/50 dark:bg-slate-800/20 font-medium' : ''}">
                        <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-xs ${iconClass}">
                            <i class="${iconClass.split(' ')[0]} ${iconClass.split(' ')[1]}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-1">
                                <span class="font-bold text-slate-850 dark:text-slate-200 block truncate">${n.title}</span>
                                <span class="text-[9px] text-slate-400 flex-shrink-0">${n.time}</span>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed break-words">${n.text}</p>
                        </div>
                    </div>
                `;
            }).join("");
        }

        function toggleNotificationDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById("notif-dropdown");
            if (!dropdown) return;
            
            if (dropdown.classList.contains("hidden")) {
                dropdown.classList.remove("hidden");
            } else {
                dropdown.classList.add("hidden");
            }
        }

        function markAllNotificationsAsRead() {
            userNotifications.forEach(n => n.read = true);
            renderNotifications();
            showToast("Semua notifikasi ditandai telah dibaca", "success");
        }

        // Close dropdown when clicking outside
        document.addEventListener("click", () => {
            const dropdown = document.getElementById("notif-dropdown");
            if (dropdown) dropdown.classList.add("hidden");
        });

        // Toggle Favorite status bookmark
        function toggleFavorite(bookId) {
            const index = currentUser.favoriteBooks.indexOf(bookId);
            if (index > -1) {
                currentUser.favoriteBooks.splice(index, 1);
                showToast("Dihapus dari daftar favorit", "info");
            } else {
                currentUser.favoriteBooks.push(bookId);
                showToast("Ditambahkan ke daftar favorit", "success");
            }
            updateActiveView();
        }

        /* DYNAMIC CONTROLLER: DISPLAY DETAILED MODAL */
        function openBookModal(bookId) {
            const book = booksData.find(b => b.id === bookId);
            if (!book) return;

            selectedBook = book;

            // Load specs
            document.getElementById("modal-book-title").innerText = book.title;
            document.getElementById("modal-book-author").innerText = `Karya ${book.author}`;
            document.getElementById("modal-book-category").innerText = book.category;
            document.getElementById("modal-book-rating").innerText = book.rating;
            document.getElementById("modal-book-pages").innerText = `${book.pages} Hlm`;
            document.getElementById("modal-book-year").innerText = book.year;
            document.getElementById("modal-book-publisher").innerText = book.publisher;
            document.getElementById("modal-book-synopsis").innerText = book.synopsis;

            // Cover Visual Setup
            const coverContainer = document.getElementById("modal-book-cover-container");
            coverContainer.innerHTML = generateBookCoverHTML(book, "w-40 h-52");

            // Status Badge setup
            const statusBadge = document.getElementById("modal-book-status-badge");
            if (book.is_ebook) {
                statusBadge.className = "px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-bold";
                statusBadge.innerText = "Tersedia (E-Book)";
            } else {
                if (book.status === "Tersedia") {
                    statusBadge.className = "px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-bold";
                    statusBadge.innerText = "Tersedia (Buku Fisik)";
                } else {
                    statusBadge.className = "px-2 py-0.5 rounded bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 font-bold";
                    statusBadge.innerText = "Sedang Dipinjam (Buku Fisik)";
                }
            }

            // Footer action buttons setup conditionally
            const footerActions = document.getElementById("modal-footer-actions");
            const isBorrowedByMe = currentUser.borrowedBooks.includes(book.id);
            const isPendingByMe = currentUser.pendingBooks && currentUser.pendingBooks.includes(book.id);

            let buttonsHTML = "";
            if (book.is_ebook) {
                if (currentUser.membership === "Premium Scholar") {
                    buttonsHTML = `
                        <button onclick="openRealReader(selectedBook)" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-extrabold rounded-xl flex items-center space-x-1 transition-all shadow-md shadow-brand-600/10">
                            <i class="fa-solid fa-play text-[9px] mr-1"></i>
                            <span>Baca E-Book</span>
                        </button>
                    `;
                } else {
                    buttonsHTML = `
                        <button onclick="openRealReader(selectedBook)" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-extrabold rounded-xl flex items-center space-x-1 transition-all shadow-sm">
                            <i class="fa-solid fa-play text-[9px] mr-1"></i>
                            <span>Pratinjau (50 Hal)</span>
                        </button>
                        <div class="flex items-center space-x-2 border border-slate-200 dark:border-slate-700 rounded-xl p-1 bg-white dark:bg-slate-800">
                            <select id="modal-pkg-select" class="text-xs font-bold bg-transparent text-slate-700 dark:text-slate-200 border-none outline-none focus:ring-0 cursor-pointer">
                                <option value="3">3 Bulan - Rp 49rb</option>
                                <option value="6">6 Bulan - Rp 89rb</option>
                                <option value="12" selected>1 Tahun - Rp 159rb</option>
                            </select>
                            <button onclick="const val = document.getElementById('modal-pkg-select').value; upgradeToPremium(val)" class="px-4 py-1.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-slate-950 hover:text-white text-xs font-black rounded-lg transition-all shadow-md">
                                <i class="fa-solid fa-crown text-[9px] mr-1"></i>
                                <span>Upgrade</span>
                            </button>
                        </div>
                    `;
                }
            } else {
                if (isBorrowedByMe) {
                    buttonsHTML = `
                        <span class="text-[11px] text-brand-600 dark:text-brand-400 font-bold flex items-center">
                            <i class="fa-solid fa-circle-check mr-1.5"></i> Anda Sedang Meminjam Buku Ini
                        </span>
                    `;
                } else if (isPendingByMe) {
                    buttonsHTML = `
                        <span class="text-[11px] text-amber-500 font-bold flex items-center">
                            <i class="fa-solid fa-hourglass-half mr-1.5 animate-spin"></i> Menunggu Verifikasi Admin
                        </span>
                    `;
                } else {
                    if (book.status === "Tersedia") {
                        buttonsHTML = `
                            <button onclick="borrowBook('${book.id}')" class="px-5 py-2 bg-gradient-to-r from-brand-500 to-indigo-600 hover:from-brand-600 hover:to-indigo-700 text-white text-xs font-extrabold rounded-xl transition-all shadow-md shadow-brand-500/15">
                                Pinjam Buku Fisik
                            </button>
                        `;
                    } else {
                        buttonsHTML = `
                            <button disabled class="px-5 py-2 bg-slate-200 dark:bg-slate-800 text-slate-400 cursor-not-allowed text-xs font-bold rounded-xl">
                                Sedang Dipinjam
                            </button>
                        `;
                    }
                }
            }

            // Reset modal tabs and update comment count
            toggleModalTab("synopsis");
            const comments = bookComments[book.slug] || [];
            document.getElementById("modal-comment-count").innerText = comments.length;

            footerActions.innerHTML = buttonsHTML;

            // Show modal
            document.getElementById("book-modal").classList.remove("hidden");
        }

        function closeBookModal() {
            document.getElementById("book-modal").classList.add("hidden");
            selectedBook = null;
        }



        function returnBook(bookId) {
            const book = booksData.find(b => b.id === bookId);
            if (!book) return;

            // Remove book from active list
            const index = currentUser.borrowedBooks.indexOf(bookId);
            if (index > -1) {
                currentUser.borrowedBooks.splice(index, 1);
            }
            book.status = "Tersedia";
            delete book.dueDate;
            book.progress = 0;

            closeBookModal();
            updateActiveView();
            showToast(`Buku "${book.title}" berhasil dikembalikan ke server LiterasiKu. Terima kasih.`, "info");
        }

        /* DYNAMIC CONTROLLER: EXCLUSIVE eREADER SIMULATION PANEL */
        function openEReaderSim(bookId) {
            const book = booksData.find(b => b.id === bookId);
            if (!book) return;

            activeReaderBook = book;
            
            mockPageTotal = book.pages || 100;
            mockPageCurrent = Math.max(1, Math.round(mockPageTotal * (book.progress / 100)));
            
            if (currentUser.membership !== "Premium Scholar" && mockPageCurrent > 30) {
                mockPageCurrent = 31; // Go directly to premium wall
            }

            document.getElementById("ereader-book-title").innerText = book.title;
            document.getElementById("ereader-book-author").innerText = `Karya ${book.author}`;
            
            closeBookModal(); // Hide modal first
            document.getElementById("ereader-panel").classList.remove("hidden");
            
            // Set reader content context
            renderEReaderContent();
        }

        function closeEReader() {
            // Calculate and save reading progress on close
            if (activeReaderBook) {
                const pageForProgress = currentUser.membership === "Premium Scholar" ? mockPageCurrent : Math.min(30, mockPageCurrent);
                const percent = Math.round((pageForProgress / mockPageTotal) * 100);
                activeReaderBook.progress = percent;
                
                // If it is read for the first time, increment goal counts
                if (percent > 0 && activeReaderBook.progress === 0) {
                    currentUser.readingGoal.current = Math.min(currentUser.readingGoal.target, currentUser.readingGoal.current + 1);
                }
            }

            document.getElementById("ereader-panel").classList.add("hidden");
            activeReaderBook = null;

            // Reset ereader body to standard structure
            const body = document.getElementById("ereader-body");
            body.className = "flex-1 overflow-y-auto px-4 py-8 md:py-12 bg-white text-slate-800";
            body.innerHTML = `
                <div class="max-w-2xl mx-auto space-y-6">
                    <div id="ereader-content-wrapper" class="prose dark:prose-invert max-w-none transition-all duration-200"></div>
                </div>
            `;

            updateActiveView();
        }

        function renderEReaderContent() {
            const wrapper = document.getElementById("ereader-content-wrapper");
            const indicator = document.getElementById("ereader-page-status");
            const body = document.getElementById("ereader-body");

            if (activeReaderBook && activeReaderBook.pdf_url) {
                if (currentUser.membership !== "Premium Scholar") {
                    body.className = "flex-1 overflow-y-auto px-4 py-8 bg-[#0f1420] flex items-center justify-center text-white";
                    body.innerHTML = `
                        <div class="text-center p-8 max-w-md mx-auto space-y-6">
                            <div class="w-16 h-16 bg-amber-500/10 text-amber-500 rounded-full flex items-center justify-center text-2xl mx-auto shadow-lg border border-amber-500/20">
                                <i class="fa-solid fa-crown animate-pulse"></i>
                            </div>
                            <div class="space-y-3">
                                <h3 class="font-extrabold text-xl text-white">Khusus Anggota Premium</h3>
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Berkas PDF asli untuk buku <strong class="text-amber-500">"${activeReaderBook.title}"</strong> hanya dapat diakses secara penuh oleh anggota **Premium Scholar**.
                                </p>
                            </div>
                            <button onclick="upgradeToPremium(12)" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-slate-950 hover:text-white font-black text-xs rounded-xl transition-all shadow-lg shadow-amber-500/20">
                                Upgrade Ke Keanggotaan Premium
                            </button>
                        </div>
                    `;
                    indicator.innerText = "Akses Premium Diperlukan";
                    return;
                } else {
                    body.className = "flex-1 overflow-hidden p-0 bg-slate-900";
                    body.innerHTML = `<iframe src="${activeReaderBook.pdf_url}" class="w-full h-full border-0"></iframe>`;
                    indicator.innerText = "Membaca Berkas PDF Asli";
                    return;
                }
            }

            body.className = "flex-1 overflow-y-auto px-4 py-8 md:py-12 bg-white text-slate-800";
            if (activeReaderTheme === "sepia") {
                body.classList.remove("bg-white", "text-slate-800");
                body.classList.add("bg-[#f4ebd0]", "text-[#433422]");
            } else if (activeReaderTheme === "dark") {
                body.classList.remove("bg-white", "text-slate-800");
                body.classList.add("bg-[#0f1420]", "text-slate-200");
            }

            if (currentUser.membership !== "Premium Scholar" && mockPageCurrent > 30) {
                body.className = "flex-1 overflow-y-auto px-4 py-8 bg-[#0f1420] flex items-center justify-center text-white";
                body.innerHTML = `
                    <div class="text-center p-8 space-y-6 max-w-md mx-auto">
                        <div class="w-16 h-16 bg-amber-500/10 text-amber-500 rounded-full flex items-center justify-center text-2xl mx-auto border border-amber-500/20">
                            <i class="fa-solid fa-lock text-amber-500"></i>
                        </div>
                        <div class="space-y-3">
                            <h3 class="font-extrabold text-lg text-white">Batas Halaman Terbaca Tercapai</h3>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto leading-relaxed">
                                Anda telah membaca sampai **halaman 30** (batas maksimum untuk Anggota Reguler). Upgrade ke **Keanggotaan Premium Scholar** untuk melanjutkan membaca seluruh isi buku ini tanpa batasan halaman.
                            </p>
                        </div>
                        <div class="max-w-xs mx-auto p-4 border border-slate-800 rounded-xl space-y-4 bg-slate-900/50 text-left">
                            <h4 class="font-bold text-xs text-slate-300">Pilihan Paket Premium</h4>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-2 text-xs text-slate-400">
                                    <input type="radio" name="reader_premium_pkg" value="3" checked class="accent-amber-500">
                                    <span>3 Bulan - Rp 45.000</span>
                                </label>
                                <label class="flex items-center space-x-2 text-xs text-slate-400">
                                    <input type="radio" name="reader_premium_pkg" value="6" class="accent-amber-500">
                                    <span>6 Bulan - Rp 80.000</span>
                                </label>
                                <label class="flex items-center space-x-2 text-xs text-slate-400">
                                    <input type="radio" name="reader_premium_pkg" value="12" class="accent-amber-500">
                                    <span>12 Bulan - Rp 150.000</span>
                                </label>
                            </div>
                            <button 
                                onclick="const val = document.querySelector('input[name=reader_premium_pkg]:checked').value; upgradeToPremium(val)"
                                class="w-full py-2 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-slate-950 hover:text-white font-black text-xs rounded-lg transition-colors flex items-center justify-center space-x-1.5"
                            >
                                <i class="fa-solid fa-crown text-[10px]"></i>
                                <span>Aktifkan Premium Scholar</span>
                            </button>
                        </div>
                    </div>
                `;
                indicator.innerText = `Halaman 30 / ${mockPageTotal} (Batas Tercapai)`;
                return;
            }

            // Mock academic book texts
            const pageTexts = [
                "Bab I: Landasan Filosofi Pengetahuan. Hakikat filsafat dimulai dari ketakjuban manusia terhadap alam semesta. Sebagai entitas yang memiliki kesadaran kritis, manusia senantiasa mempertanyakan keberadaan dirinya, perannya, dan dari mana semua materi penyusun kehidupan ini berasal. Sejarah mencatat bahwa filsafat Barat lahir di pesisir Asia Kecil (Miletus) pada abad ke-6 SM.",
                "Dalam konteks ini, Thales muncul sebagai figur pertama yang mencoba menerangkan gejala alam secara rasional tanpa bersandar pada mitologi tradisional Yunani. Pemikiran kritis ini kemudian berkembang pesat melalui Socrates, Plato, dan Aristoteles, membentuk tiang penyangga utama metodologi ilmiah modern.",
                "Bab II: Metodologi Berpikir Logis. Berpikir logis membutuhkan kedisiplinan intelektual. Premis-premis yang dibangun harus diuji keabsahannya baik secara deduktif maupun induktif. Kesalahan logika (logical fallacies) kerap terjadi akibat bias konfirmasi, generalisasi yang terburu-buru, atau ad hominem yang menyerang pribadi pembawa argumen.",
                "Bab III: Konstruksi Sosial Realitas. Peter L. Berger menegaskan bahwa masyarakat adalah produk manusia, namun manusia sendiri juga merupakan produk dari masyarakatnya. Dialektika tiga langkah (eksternalisasi, objektivasi, internalisasi) menjelaskan bagaimana realitas institusional dipahami sebagai kebenaran objektif.",
                "Bab IV: Dampak Revolusi Kognitif. Sejak 70.000 tahun silam, mutasi genetik yang tidak terduga memungkinkan Homo sapiens berkomunikasi tentang hal-hal fiktif. Mitos bersama ini menyatukan ribuan orang dalam kerja sama yang fleksibel, melebihi kapasitas spesies Neanderthal manapun.",
                "Bab V: Peningkatan Kebiasaan Mikro. Perubahan kecil sebesar 1% setiap hari secara konsisten akan menghasilkan peningkatan sebanyak 37 kali lipat dalam satu tahun. Hal ini membuktikan bahwa kesuksesan jangka panjang adalah hasil akumulasi sistem kebiasaan, bukan kejadian instan sekali seumur hidup.",
                "Bab VI: Prinsip Fokus Mendalam (Deep Work). Di era informasi yang bising ini, kemampuan memusatkan perhatian penuh tanpa distraksi kognitif menjadi komoditas langka. Deep work meningkatkan efisiensi mielinisasi neuron di otak, memungkinkan penguasaan topik rumit dalam waktu singkat.",
                "Bab VII: Narasi Sejarah dan Kekuasaan. Sejarah sering kali ditulis oleh para pemenang peperangan. Namun, jejak penderitaan masyarakat kelas bawah (subaltern) tetap membekas dalam memori kolektif yang ditransmisikan secara lisan, menunggu untuk direkonstruksi secara jujur oleh sejarawan masa kini.",
                "Bab VIII: Stoisisme dan Kontrol Emosi. Dikotomi kendali membagi hal-hal di dunia menjadi dua bagian: apa yang berada di bawah kendali kita (pikiran, tindakan, nilai diri) dan apa yang di luar kendali kita (reputasi, cuaca, tindakan orang lain). Kedamaian sejati lahir ketika kita melepaskan keterikatan pada hal di luar kendali.",
                "Bab IX: Dialektika Hegelian. Tesis, antitesis, dan sintesis merupakan mekanisme perkembangan peradaban. Setiap ideologi besar yang mendominasi suatu era pasti akan melahirkan pertentangan internal, yang akhirnya melahirkan sintesis baru yang lebih sempurna.",
                "Bab X: Etika di Era Kecerdasan Buatan. Ketika algoritma mulai mengambil alih keputusan medis, hukum, dan militer, pertanyaan tentang tanggung jawab moral bergeser dari individu manusia ke struktur sistemik dan pencipta kode program.",
                "Bab XI: Analisis Struktur Kekuasaan Foucauldian. Kekuasaan tidak hanya terpusat pada lembaga negara, melainkan tersebar di seluruh jaringan diskursus, termasuk dalam institusi pendidikan, rumah sakit, dan penjara.",
                "Bab XII: Kajian Ekofeminisme. Menghubungkan eksploitasi alam dengan penindasan gender. Ekofeminisme menawarkan paradigma baru yang melihat bumi sebagai mitra hidup, bukan sekadar komoditas mentah untuk dieksploitasi.",
                "Bab XIII: Hermeneutika Teks Klasik. Menafsirkan literatur kuno membutuhkan pemahaman mendalam tentang cakrawala historis penulis asli (horizon of the author) sekaligus cakrawala pembaca modern (horizon of the reader).",
                "Bab XIV: Penutup dan Refleksi Akademik. Sintesis akhir dari seluruh teori yang telah dipelajari membawa kita pada satu kesimpulan mutlak: belajar adalah proses dialektis tanpa batas akhir."
            ];

            // Render current simulated page
            const textToShow = pageTexts[(mockPageCurrent - 1) % pageTexts.length];
            
            wrapper.innerHTML = `
                <div class="space-y-4">
                    <p class="text-xs font-bold text-brand-600 tracking-widest uppercase">HALAMAN ${mockPageCurrent} DARI ${mockPageTotal}</p>
                    <div style="font-size: ${readerFontSize}px" class="leading-relaxed text-justify indent-8 space-y-4 text-slate-800 dark:text-slate-200">
                        <p>${textToShow}</p>
                        <p>Penelitian empiris menunjukkan bahwa meluangkan waktu 15 menit setiap hari untuk mencerna referensi berkualitas tinggi secara terstruktur meningkatkan memori jangka panjang hingga 40%. Di LiterasiKu, setiap lembaran buku dirancang untuk menstimulasi fokus akademik yang mendalam.</p>
                    </div>
                </div>
            `;

            indicator.innerText = `Halaman ${mockPageCurrent} / ${mockPageTotal}`;
        }

        function navigateMockPage(direction) {
            mockPageCurrent += direction;
            if (mockPageCurrent < 1) mockPageCurrent = 1;
            
            const maxAllowedPage = currentUser.membership === "Premium Scholar" ? mockPageTotal : Math.min(31, mockPageTotal);
            if (mockPageCurrent > maxAllowedPage) mockPageCurrent = maxAllowedPage;
            
            renderEReaderContent();
        }

        function changeFontSize(delta) {
            readerFontSize += delta;
            if (readerFontSize < 10) readerFontSize = 10;
            if (readerFontSize > 24) readerFontSize = 24;
            renderEReaderContent();
        }

        function setReaderTheme(theme) {
            const body = document.getElementById("ereader-body");
            activeReaderTheme = theme;

            body.className = "flex-1 overflow-y-auto px-4 py-8 md:py-12 transition-colors duration-200";

            if (theme === "light") {
                body.classList.add("bg-white", "text-slate-800");
            } else if (theme === "sepia") {
                body.classList.add("bg-[#f4ebd0]", "text-[#433422]");
            } else if (theme === "dark") {
                body.classList.add("bg-[#0f1420]", "text-slate-200");
            }
        }

        /* RENDER SUBSECTION: ACADEMIC PROFILE INFO VIEW */
        function renderProfileInfoView(container) {
            container.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Detail Profil Akademik</h2>
                        <p class="text-xs text-slate-400">Kelola informasi riset, target pencapaian literasi, dan status keanggotaan premium Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Column 1: Profile Summary Card & Upgrade -->
                        <div class="space-y-6">
                            <!-- Left card profile detail -->
                            <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-6 text-center shadow-sm">
                                <div class="relative w-24 h-24 mx-auto mb-4">
                                    <img src="${currentUser.avatar}" alt="User Avatar" class="w-full h-full rounded-2xl object-cover border-4 border-brand-500/10 shadow-lg">
                                    <span class="absolute bottom-1 right-1 w-5 h-5 bg-emerald-500 border-2 border-white dark:border-darkCard rounded-full flex items-center justify-center text-white text-[8px]" title="Aktif"><i class="fa-solid fa-check"></i></span>
                                </div>
                                <h3 class="font-extrabold text-base text-slate-800 dark:text-white">${currentUser.name}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">NIM/NPM: ${currentUser.nim}</p>
                                
                                <span class="inline-block mt-3 px-3 py-1 bg-brand-50 dark:bg-brand-950/40 text-brand-600 dark:text-brand-400 text-xs font-bold rounded-full">${currentUser.membership}</span>
                                
                                <div class="mt-6 pt-6 border-t border-slate-100 dark:border-darkBorder space-y-3 text-left">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-400 font-semibold">Institusi:</span>
                                        <span class="text-slate-800 dark:text-slate-200 font-bold">Universitas Indonesia</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-400 font-semibold">Program Studi:</span>
                                        <span class="text-slate-800 dark:text-slate-200 font-bold">Filsafat & Humaniora</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-400 font-semibold">Status Anggota:</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">Sangat Aktif</span>
                                    </div>
                                </div>
                                
                                ${currentUser.membership === 'Regular Scholar' ? `
                                <div class="mt-6 pt-6 border-t border-slate-100 dark:border-darkBorder">
                                    <div class="bg-gradient-to-tr from-amber-500/10 to-orange-500/10 border border-amber-500/20 rounded-xl p-3.5 text-left mb-4">
                                        <h4 class="text-xs font-bold text-amber-800 dark:text-amber-300 flex items-center space-x-1.5">
                                            <i class="fa-solid fa-crown text-[10px]"></i>
                                            <span>Upgrade ke Premium</span>
                                        </h4>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Pilih paket langganan Anda:</p>
                                        
                                        <div class="mt-3 space-y-2">
                                            <label class="flex items-center justify-between p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-amber-500 transition-all">
                                                <div class="flex items-center space-x-2">
                                                    <input type="radio" name="profile_premium_pkg" value="3" checked class="accent-amber-500">
                                                    <span class="text-[11px] font-bold text-slate-800 dark:text-slate-200">3 Bulan</span>
                                                </div>
                                                <span class="text-[11px] font-black text-amber-600 dark:text-amber-400">Rp 49.000</span>
                                            </label>
                                            <label class="flex items-center justify-between p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-amber-500 transition-all">
                                                <div class="flex items-center space-x-2">
                                                    <input type="radio" name="profile_premium_pkg" value="6" class="accent-amber-500">
                                                    <span class="text-[11px] font-bold text-slate-800 dark:text-slate-200">6 Bulan</span>
                                                </div>
                                                <span class="text-[11px] font-black text-amber-600 dark:text-amber-400">Rp 89.000</span>
                                            </label>
                                            <label class="flex items-center justify-between p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-amber-500 transition-all">
                                                <div class="flex items-center space-x-2">
                                                    <input type="radio" name="profile_premium_pkg" value="12" class="accent-amber-500">
                                                    <span class="text-[11px] font-bold text-slate-800 dark:text-slate-200">1 Tahun</span>
                                                </div>
                                                <span class="text-[11px] font-black text-amber-600 dark:text-amber-400">Rp 159.000</span>
                                            </label>
                                        </div>
                                    </div>
                                    <button onclick="const val = document.querySelector('input[name=profile_premium_pkg]:checked').value; upgradeToPremium(val)" class="w-full py-2 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-slate-950 hover:text-white text-xs font-black rounded-xl transition-all shadow-md shadow-amber-500/25 flex items-center justify-center space-x-1.5">
                                        <i class="fa-solid fa-crown text-[10px]"></i>
                                        <span>Aktifkan Premium</span>
                                    </button>
                                </div>
                                ` : `
                                <div class="mt-6 pt-6 border-t border-slate-100 dark:border-darkBorder">
                                    <div class="bg-gradient-to-tr from-brand-500/10 to-indigo-500/10 border border-brand-500/20 rounded-xl p-3 text-center">
                                        <h4 class="text-xs font-bold text-brand-700 dark:text-brand-300 flex items-center justify-center space-x-1.5">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                                            <span>Premium Aktif</span>
                                        </h4>
                                        <p class="text-[9px] text-slate-500 dark:text-slate-400 mt-1">Akses penuh seluruh koleksi buku & E-Book digital aktif.</p>
                                    </div>
                                </div>
                                `}
                            </div>
                        </div>

                        <!-- Column 2 & 3: Forms for Profile & Password Updates (span 2) -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Card A: Informasi Profil Akademik (Permanen / Terkunci) -->
                            <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-6 shadow-sm">
                                <div class="flex items-center justify-between mb-4 border-b border-slate-50 dark:border-darkBorder/60 pb-3">
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center space-x-2">
                                        <i class="fa-solid fa-user-shield text-brand-500 text-xs"></i>
                                        <span>Informasi Profil Akademik</span>
                                    </h3>
                                    <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800/80 rounded-md text-[10px] font-bold text-slate-500 dark:text-slate-400 flex items-center space-x-1 border border-slate-200/40 dark:border-darkBorder/30 shadow-sm">
                                        <i class="fa-solid fa-lock text-[8px] text-slate-400"></i>
                                        <span>Permanen</span>
                                    </span>
                                </div>
                                
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Nama -->
                                        <div class="bg-slate-50/50 dark:bg-slate-800/20 p-3.5 rounded-xl border border-slate-100/50 dark:border-darkBorder/20">
                                            <span class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap</span>
                                            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase">${currentUser.name}</span>
                                        </div>
                                        
                                        <!-- NPM / NIM -->
                                        <div class="bg-slate-50/50 dark:bg-slate-800/20 p-3.5 rounded-xl border border-slate-100/50 dark:border-darkBorder/20">
                                            <span class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">NIM / NPM</span>
                                            <span class="text-xs font-mono font-bold text-slate-800 dark:text-slate-200">${currentUser.nim}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Phone -->
                                        <div class="bg-slate-50/50 dark:bg-slate-800/20 p-3.5 rounded-xl border border-slate-100/50 dark:border-darkBorder/20">
                                            <span class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nomor Handphone</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">${currentUser.phone}</span>
                                        </div>
                                        
                                        <!-- Gender -->
                                        <div class="bg-slate-50/50 dark:bg-slate-800/20 p-3.5 rounded-xl border border-slate-100/50 dark:border-darkBorder/20">
                                            <span class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Jenis Kelamin</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">${currentUser.gender}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Address -->
                                    <div class="bg-slate-50/50 dark:bg-slate-800/20 p-3.5 rounded-xl border border-slate-100/50 dark:border-darkBorder/20">
                                        <span class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Alamat Tempat Tinggal</span>
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">${currentUser.address}</span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 p-3 bg-brand-50/20 dark:bg-brand-950/10 border border-brand-100/30 rounded-xl text-[10px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                        <i class="fa-solid fa-circle-info text-brand-500 text-xs"></i>
                                        <span>Perubahan data di atas hanya dapat diproses oleh administrator/petugas perpustakaan melalui sistem backend.</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card B: Change Password Form -->
                            <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-6 shadow-sm">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4 flex items-center space-x-2">
                                    <i class="fa-solid fa-key text-brand-500 text-xs"></i>
                                    <span>Ganti Kata Sandi / Password</span>
                                </h3>
                                
                                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                                    @csrf
                                    
                                    <!-- Current Password -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-1.5">Password Saat Ini</label>
                                        <input type="password" name="current_password" required placeholder="••••••••" class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 dark:text-slate-200">
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- New Password -->
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-1.5">Password Baru</label>
                                            <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 dark:text-slate-200">
                                        </div>
                                        
                                        <!-- Confirm New Password -->
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-1.5">Konfirmasi Password Baru</label>
                                            <input type="password" name="password_confirmation" required placeholder="Minimal 8 karakter" class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 dark:text-slate-200">
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/10 transition-all">
                                            Ganti Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Goals & Achievements -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                        <!-- Goals setup panel card -->
                        <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Pengaturan Target Literasi</h3>
                                <div class="space-y-4">
                                    <div>
                                        <div class="flex justify-between items-center text-xs mb-1.5">
                                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Target Membaca Buku Bulanan</span>
                                            <span id="profile-goal-counter" class="text-brand-600 dark:text-brand-400 font-bold">${currentUser.readingGoal.current} dari ${currentUser.readingGoal.target} Buku</span>
                                        </div>
                                        <input type="range" min="5" max="30" value="${currentUser.readingGoal.target}" oninput="updateReadingTarget(event)" class="w-full accent-brand-500 h-2 bg-slate-100 dark:bg-slate-800 rounded-lg cursor-pointer">
                                    </div>
                                    <p class="text-[11px] text-slate-400">Target literasi Anda dirancang otomatis sesuai dengan beban studi.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Badges achievements -->
                        <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl p-6 shadow-sm">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Lencana & Kompetensi Membaca</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-darkBorder text-center">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-950/40 text-brand-500 flex items-center justify-center mx-auto mb-2 text-base">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </div>
                                    <h4 class="text-[11px] font-bold text-slate-800 dark:text-white text-center">Akademisi Kritis</h4>
                                </div>
                                <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-darkBorder text-center">
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 flex items-center justify-center mx-auto mb-2 text-base">
                                        <i class="fa-solid fa-bolt"></i>
                                    </div>
                                    <h4 class="text-[11px] font-bold text-slate-800 dark:text-white text-center">Kecepatan Tinggi</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        /* RENDER SUBSECTION: DIGITAL MEMBER CARD VIEW */
        function renderProfileCardView(container) {
            container.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Kartu Anggota Digital</h2>
                        <p class="text-xs text-slate-400">Tunjukkan kartu ini kepada petugas saat berkunjung ke perpustakaan fisik.</p>
                    </div>

                    <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-3xl p-8 max-w-xl mx-auto shadow-sm space-y-6">
                        <div class="flex justify-center items-center py-4">
                            <div class="w-full max-w-[420px] min-h-[260px] bg-slate-900 dark:bg-darkCard rounded-3xl relative overflow-hidden text-white shadow-xl border border-slate-700/50 p-6 flex flex-col justify-between" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                                <div class="absolute -right-16 -bottom-16 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
                                
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-book-open text-brand-400 text-lg"></i>
                                        <span class="text-xs font-black tracking-widest uppercase">LITERASIKU DIGITAL</span>
                                    </div>
                                    <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full ${
                                        currentUser.membership === 'Premium Scholar'
                                            ? 'bg-amber-400 text-slate-950 shadow-sm'
                                            : 'bg-slate-700 text-slate-300'
                                    }">
                                        ${currentUser.membership === 'Premium Scholar' ? '👑 Premium' : 'Reguler'}
                                    </span>
                                </div>

                                <div class="flex items-center space-x-4 my-6">
                                    <div class="w-18 h-18 rounded-2xl bg-white/10 flex items-center justify-center border border-white/20 shadow-inner flex-shrink-0">
                                        <i class="fa-solid fa-user text-3xl text-brand-300"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-extrabold truncate uppercase tracking-wide text-white">${currentUser.name}</h4>
                                        <span class="text-xs font-mono tracking-wider opacity-85 text-brand-200 block mt-0.5">${currentUser.nim}</span>
                                        <span class="text-[9px] text-slate-400 block mt-1.5 font-semibold">STATUS: AKTIF</span>
                                    </div>
                                    <div class="w-18 h-18 bg-white p-1 rounded-xl flex-shrink-0 flex items-center justify-center shadow-md">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=MEMBER_ID:${currentUser.id}|NIM:${currentUser.nim}|NAME:${encodeURIComponent(currentUser.name)}" alt="QR Code" class="w-full h-full object-contain">
                                    </div>
                                </div>

                                <div class="flex justify-between items-center border-t border-white/10 pt-3 text-[9px] text-slate-400 uppercase tracking-widest">
                                    <span>OFFICIAL DIGITAL MEMBER CARD</span>
                                    <i class="fa-solid fa-wifi opacity-50 text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Dynamically recalculate state targets on input adjustment
        function updateReadingTarget(event) {
            currentUser.readingGoal.target = parseInt(event.target.value);
            const counterLabel = document.getElementById("profile-goal-counter");
            if (counterLabel) {
                counterLabel.innerText = `${currentUser.readingGoal.current} dari ${currentUser.readingGoal.target} Buku`;
            }
        }

        function handleProfileParentClick() {
            toggleProfileSubmenu();
            if (!currentActiveTab.startsWith("profile-")) {
                switchTab('profile-info');
            }
        }

        function toggleProfileSubmenu() {
            const submenu = document.getElementById("profile-submenu-items");
            const chevron = document.getElementById("profile-submenu-chevron");
            if (submenu) {
                const isHidden = submenu.classList.toggle("hidden");
                if (chevron) {
                    if (isHidden) {
                        chevron.classList.remove("rotate-180");
                    } else {
                        chevron.classList.add("rotate-180");
                    }
                }
            }
        }

        function switchProfileSubTab(subTabName) {
            profileActiveSubTab = subTabName;
            updateActiveView();
        }

        /* RENDER SUBSECTION: BORROWING HISTORY VIEW */
        function renderHistoryView(container) {
            // Built dynamic history table records
            let historyRowsHTML = "";
            const historicalRecords = @json($historyRecords);

            historicalRecords.forEach(rec => {
                historyRowsHTML += `
                    <tr class="border-b border-slate-100 dark:border-darkBorder/40 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-50/50 dark:hover:bg-slate-800/10">
                        <td class="px-4 py-3 font-mono font-bold text-brand-600">${rec.id}</td>
                        <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">${rec.title}</td>
                        <td class="px-4 py-3">${rec.date}</td>
                        <td class="px-4 py-3">${rec.returnDate}</td>
                        <td class="px-4 py-3">${rec.penalty}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold ${
                                rec.status === 'Kembali' 
                                    ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400' 
                                    : 'bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400'
                            }">
                                ${rec.status}
                            </span>
                        </td>
                    </tr>
                `;
            });

            container.innerHTML = `
                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Riwayat Peminjaman Buku</h2>
                        <p class="text-xs text-slate-400">Log lengkap transaksi digital peminjaman dan pengembalian literatur perpustakaan.</p>
                    </div>

                    <div class="bg-white dark:bg-darkCard border border-slate-100 dark:border-darkBorder rounded-2xl overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-800/60 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-200 dark:border-darkBorder">
                                        <th class="px-4 py-3">ID TRANSAKSI</th>
                                        <th class="px-4 py-3">JUDUL REFERENSI</th>
                                        <th class="px-4 py-3">TGL PINJAM</th>
                                        <th class="px-4 py-3">TGL KEMBALI</th>
                                        <th class="px-4 py-3">DENDA & KETERANGAN</th>
                                        <th class="px-4 py-3">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${historyRowsHTML}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
        }

        // Custom Premium Toast Messaging UI engine (Prevents reliance on alert())
        function showToast(message, type = "success") {
            const container = document.getElementById("toast-container");
            if (!container) return;

            const toast = document.createElement("div");
            toast.className = "flex items-center space-x-3 px-4 py-3 rounded-xl shadow-lg border text-xs font-bold bg-white dark:bg-darkCard transition-all transform duration-300 pointer-events-auto min-w-[280px] translate-y-2 opacity-0";

            let iconHTML = "";
            if (type === "success") {
                toast.classList.add("border-emerald-100", "dark:border-emerald-900/40");
                iconHTML = `<i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>`;
            } else if (type === "info") {
                toast.classList.add("border-blue-100", "dark:border-blue-900/40");
                iconHTML = `<i class="fa-solid fa-circle-info text-blue-500 text-sm"></i>`;
            } else {
                toast.classList.add("border-red-100", "dark:border-red-900/40");
                iconHTML = `<i class="fa-solid fa-triangle-exclamation text-red-500 text-sm"></i>`;
            }

            toast.innerHTML = `
                ${iconHTML}
                <div class="flex-1 text-slate-700 dark:text-slate-200">${message}</div>
            `;

            container.appendChild(toast);

            // Trigger animation frame entry
            setTimeout(() => {
                toast.classList.remove("translate-y-2", "opacity-0");
            }, 10);

            // Automatic dismiss timeout
            setTimeout(() => {
                toast.classList.add("opacity-0", "translate-y-2");
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    </script>
@include('chatbot_widget')
</body>
</html>