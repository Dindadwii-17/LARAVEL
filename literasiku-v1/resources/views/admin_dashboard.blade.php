<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LiteraAdmin - E-Perpustakaan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome & Lucide Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
<body 
    x-data="adminApp()" 
    x-init="init()"
    :class="isDarkMode ? 'dark' : ''"
    class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 flex h-screen overflow-hidden antialiased"
>

    <!-- Toast Notification -->
    <template x-if="toast">
        <div 
            :class="toast.type === 'error' ? 'bg-red-955/95 border-red-500 text-red-200' : 'bg-emerald-955/95 border-emerald-500 text-emerald-200'"
            class="fixed top-4 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-xl border shadow-2xl transition-all transform duration-300 translate-y-0"
        >
            <i :class="toast.type === 'error' ? 'fa-solid fa-circle-xmark text-red-400' : 'fa-solid fa-circle-check text-emerald-400'" class="text-lg"></i>
            <span class="text-sm font-semibold" x-text="toast.message"></span>
        </div>
    </template>

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col justify-between shrink-0 transition-colors shadow-sm dark:shadow-none">
        <div>
            <!-- Logo Brand -->
            <div class="p-6 border-b border-slate-200 dark:border-slate-850 flex items-center gap-3">
                <div class="p-2.5 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-500/20">
                    <i data-lucide="book-open-check" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="font-black text-lg leading-tight tracking-wide text-slate-900 dark:text-white">LiteraAdmin</h1>
                    <span class="text-[10px] text-indigo-500 font-bold tracking-widest uppercase">E-Perpustakaan</span>
                </div>
            </div>

            <!-- Nav Items -->
            <nav class="p-4 space-y-1.5">
                <button 
                    @click="switchTab('dashboard')"
                    :class="currentTab === 'dashboard' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-500 dark:text-slate-400 hover:bg-indigo-600/10 hover:text-indigo-500'"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all"
                >
                    <div class="flex items-center gap-3">
                        <i data-lucide="trending-up" class="w-4.5 h-4.5"></i>
                        <span>Dashboard Analitik</span>
                    </div>
                </button>

                <button 
                    @click="switchTab('physical_books')"
                    :class="currentTab === 'physical_books' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-500 dark:text-slate-400 hover:bg-indigo-600/10 hover:text-indigo-500'"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all"
                >
                    <div class="flex items-center gap-3">
                        <i data-lucide="book" class="w-4.5 h-4.5"></i>
                        <span>Data Buku Fisik</span>
                    </div>
                    <span 
                        :class="isDarkMode ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-600'"
                        class="px-2 py-0.5 text-[10px] rounded-full font-bold" 
                        x-text="books.filter(b => !b.is_ebook).length"
                    ></span>
                </button>

                <button 
                    @click="switchTab('ebooks')"
                    :class="currentTab === 'ebooks' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-500 dark:text-slate-400 hover:bg-indigo-600/10 hover:text-indigo-500'"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all"
                >
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-text" class="w-4.5 h-4.5"></i>
                        <span>Data E-Book (PDF)</span>
                    </div>
                    <span 
                        :class="isDarkMode ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-600'"
                        class="px-2 py-0.5 text-[10px] rounded-full font-bold" 
                        x-text="books.filter(b => b.is_ebook).length"
                    ></span>
                </button>

                <button 
                    @click="switchTab('members')"
                    :class="currentTab === 'members' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-500 dark:text-slate-400 hover:bg-indigo-600/10 hover:text-indigo-500'"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all"
                >
                    <div class="flex items-center gap-3">
                        <i data-lucide="users" class="w-4.5 h-4.5"></i>
                        <span>Manajemen Anggota</span>
                    </div>
                    <span 
                        :class="isDarkMode ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-600'"
                        class="px-2 py-0.5 text-[10px] rounded-full font-bold" 
                        x-text="members.length"
                    ></span>
                </button>

                <button 
                    @click="switchTab('transactions')"
                    :class="currentTab === 'transactions' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-500 dark:text-slate-400 hover:bg-indigo-600/10 hover:text-indigo-500'"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all"
                >
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-text" class="w-4.5 h-4.5"></i>
                        <span>Manajemen Peminjaman</span>
                    </div>
                    <template x-if="getOverdueCount() > 0">
                        <span class="px-2 py-0.5 text-[10px] bg-red-600 text-white font-black rounded-full animate-pulse" x-text="getOverdueCount() + '!'"></span>
                    </template>
                </button>

                <button 
                    @click="switchTab('categories')"
                    :class="currentTab === 'categories' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-500 dark:text-slate-400 hover:bg-indigo-600/10 hover:text-indigo-500'"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all"
                >
                    <div class="flex items-center gap-3">
                        <i data-lucide="layers" class="w-4.5 h-4.5"></i>
                        <span>Sirkulasi Denda</span>
                    </div>
                </button>
            </nav>
        </div>

        <!-- User Admin Profiling Bottom -->
        <div class="p-4 border-t border-slate-200 dark:border-slate-850">
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-100 dark:bg-slate-905">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center font-bold text-indigo-500 text-xs">
                        AD
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 dark:text-white">Admin Utama</p>
                        <p class="text-[10px] text-slate-500">administrator@litera.id</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit"
                        class="p-1.5 text-slate-400 hover:text-red-500 rounded-lg transition-colors cursor-pointer"
                        title="Logout"
                    >
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Area Container -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
        
        <!-- Topbar Header -->
        <header class="h-16 bg-white/80 dark:bg-slate-900/85 border-b border-slate-200 dark:border-slate-800 backdrop-blur px-6 flex items-center justify-between shrink-0 transition-colors">
            <div>
                <h2 
                    class="text-base font-black capitalize tracking-wide text-slate-900 dark:text-white"
                    x-text="currentTab === 'dashboard' ? 'Overview Admin Dashboard' : currentTab === 'physical_books' ? 'Katalog Koleksi Buku Fisik' : currentTab === 'ebooks' ? 'Katalog Koleksi E-Book (PDF)' : currentTab === 'members' ? 'Data & Hak Akses Anggota' : currentTab === 'transactions' ? 'Manajemen Peminjaman' : 'Sirkulasi Denda'"
                ></h2>
                <p class="text-[10px] text-slate-400">Hari ini: Minggu, 12 Juli 2026</p>
            </div>

            <div class="flex items-center gap-3.5 relative">
                <!-- Dark/Light Theme Button -->
                <button 
                    @click="toggleTheme()"
                    :class="isDarkMode ? 'bg-slate-900 border-slate-800 text-amber-400 hover:bg-slate-850' : 'bg-slate-100 border-slate-200 text-indigo-600 hover:bg-slate-200'"
                    class="p-2 rounded-xl border transition-all"
                    title="Ganti Tema Visual"
                >
                    <template x-if="isDarkMode">
                        <i data-lucide="sun" class="w-4 h-4"></i>
                    </template>
                    <template x-if="!isDarkMode">
                        <i data-lucide="moon" class="w-4 h-4"></i>
                    </template>
                </button>

                <!-- Quick Circulation Trigger -->
                <button 
                    @click="handleOpenBorrow()"
                    class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-3.5 py-2 rounded-xl text-xs font-bold shadow-md shadow-indigo-600/10 transition-all hover:scale-102"
                >
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Sirkulasi Baru</span>
                </button>

                <!-- Notification Center -->
                <button 
                    @click="showNotifications = !showNotifications"
                    :class="isDarkMode ? 'text-slate-400 border-slate-855 hover:text-slate-200' : 'text-slate-650 border-slate-200 hover:text-slate-900'"
                    class="p-2 rounded-xl border transition-all relative"
                >
                    <i data-lucide="bell" class="w-4 h-4"></i>
                    <template x-if="notifications.length > 0">
                        <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                    </template>
                </button>

                <!-- Notifications Dropdown Card -->
                <div 
                    x-show="showNotifications"
                    @click.away="showNotifications = false"
                    :class="isDarkMode ? 'bg-slate-950 border-slate-800' : 'bg-white border-slate-200 shadow-xl'"
                    class="absolute right-0 top-12 w-80 rounded-xl shadow-2xl z-40 overflow-hidden border"
                    style="display: none;"
                >
                    <div :class="isDarkMode ? 'border-slate-800' : 'border-slate-200'" class="p-4 border-b flex justify-between items-center">
                        <h4 class="font-bold text-xs text-slate-900 dark:text-white">Notifikasi Sistem</h4>
                        <button 
                            @click="clearNotifications()"
                            class="text-[10px] text-indigo-500 hover:text-indigo-400 font-bold"
                        >
                            Hapus Semua
                        </button>
                    </div>
                    <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-850">
                        <template x-if="notifications.length === 0">
                            <div class="p-6 text-center text-xs text-slate-500">Tidak ada notifikasi baru</div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.id">
                            <div class="p-3.5 flex gap-3 items-start hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                <div 
                                    :class="notif.type === 'warning' ? 'bg-amber-500' : notif.type === 'info' ? 'bg-sky-500' : 'bg-emerald-500'"
                                    class="w-2 h-2 mt-1.5 rounded-full shrink-0"
                                ></div>
                                <div>
                                    <p class="text-xs leading-normal text-slate-700 dark:text-slate-300" x-text="notif.text"></p>
                                    <span class="text-[10px] text-slate-500 mt-1 block font-medium" x-text="notif.time"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <!-- Scrollable Contents Grid -->
        <main class="flex-1 overflow-y-auto p-6 space-y-6">

            <!-- TAB 1: DASHBOARD VIEW -->
            <div x-show="currentTab === 'dashboard'" class="space-y-6">
                <!-- Key Indicators Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl transition-colors shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Buku</span>
                            <div class="p-2 bg-indigo-500/10 rounded-xl text-indigo-500"><i data-lucide="book" class="w-5 h-5"></i></div>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white" x-text="getStats().totalBooks"></h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-2 flex items-center gap-1 font-medium">
                            Dengan total <span class="text-emerald-500 font-bold" x-text="books.filter(b => !b.is_ebook).reduce((sum, b) => sum + b.stock, 0)"></span> eksemplar fisik
                        </p>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl transition-colors shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Anggota Aktif</span>
                            <div class="p-2 bg-emerald-500/10 rounded-xl text-emerald-500"><i data-lucide="users" class="w-5 h-5"></i></div>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white" x-text="getStats().activeMembers"></h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-2 font-medium">
                            Dari total <span x-text="members.length"></span> member terverifikasi
                        </p>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl transition-colors shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sedang Dipinjam</span>
                            <div class="p-2 bg-sky-500/10 rounded-xl text-sky-500"><i data-lucide="file-text" class="w-5 h-5"></i></div>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white" x-text="getStats().borrowedBooks"></h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-2 font-medium">
                            Buku fisik beredar di luar
                        </p>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl transition-colors shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jatuh Tempo</span>
                            <div class="p-2 bg-red-500/10 rounded-xl text-red-500"><i data-lucide="clock" class="w-5 h-5"></i></div>
                        </div>
                        <h3 
                            :class="getStats().overdue > 0 ? 'text-red-500 animate-pulse' : 'text-slate-900 dark:text-white'"
                            class="text-2xl font-black tracking-tight" 
                            x-text="getStats().overdue"
                        ></h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-2 font-medium">
                            Segera hubungi anggota terkait
                        </p>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl transition-colors shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Denda Tertangguh</span>
                            <div class="p-2 bg-amber-500/10 rounded-xl text-amber-500"><i data-lucide="dollar-sign" class="w-5 h-5"></i></div>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white" x-text="'Rp ' + getStats().fines.toLocaleString('id-ID')"></h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-2 font-medium">
                            Dari denda keterlambatan aktif
                        </p>
                    </div>

                </div>

                <!-- Graphical Visualizations Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Circulation Area Chart -->
                    <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col transition-colors shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                            <div>
                                <h3 class="font-black text-sm text-slate-900 dark:text-white">Statistik Unduhan & Sirkulasi Buku</h3>
                                <p class="text-xs text-slate-400">Analisis traffic peminjaman fisik vs unduhan digital</p>
                            </div>
                            <div class="flex gap-4 text-[10px] font-bold">
                                <span class="flex items-center gap-1.5 text-indigo-500"><span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>Pinjam</span>
                                <span class="flex items-center gap-1.5 text-emerald-500"><span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>Kembali</span>
                                <span class="flex items-center gap-1.5 text-sky-400"><span class="w-2.5 h-2.5 bg-sky-400 rounded-full"></span>E-Book</span>
                            </div>
                        </div>
                        <div class="h-64 w-full relative">
                            <canvas id="circulationChart"></canvas>
                        </div>
                    </div>

                    <!-- Categories Bar Chart -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col justify-between transition-colors shadow-sm">
                        <div>
                            <h3 class="font-black text-sm text-slate-900 dark:text-white">Katalog Per Kategori</h3>
                            <p class="text-xs text-slate-400">Distribusi total item buku per bidang kategori</p>
                        </div>
                        <div class="h-56 w-full mt-6 relative">
                            <canvas id="categoriesChart"></canvas>
                        </div>
                    </div>

                </div>

                <!-- Bottom Quick-actions & Overdue lists -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Overdue alert list -->
                    <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl transition-colors shadow-sm">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="font-black text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                <i data-lucide="alert-triangle" class="w-4.5 h-4.5 text-amber-500 animate-pulse"></i>
                                <span>Sirkulasi Lewat Jatuh Tempo</span>
                            </h3>
                            <button 
                                @click="currentTab = 'transactions'; txStatusFilter = 'Terlambat';"
                                class="text-xs font-bold text-indigo-500 hover:text-indigo-400 flex items-center gap-1"
                            >
                                Manajemen Sanksi <i data-lucide="chevron-right" class="w-3 h-3"></i>
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold">
                                        <th class="pb-3">Peminjam</th>
                                        <th class="pb-3">Buku</th>
                                        <th class="pb-3">Jatuh Tempo</th>
                                        <th class="pb-3">Denda Terkumpul</th>
                                        <th class="pb-3 text-right">Tindakan Admin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                    <template x-if="transactions.filter(t => t.status === 'Terlambat').length === 0">
                                        <tr>
                                            <td colSpan="5" class="py-6 text-center text-slate-500 font-medium">Sempurna! Tidak ada keterlambatan pengembalian hari ini.</td>
                                        </tr>
                                    </template>
                                    <template x-for="tx in transactions.filter(t => t.status === 'Terlambat')" :key="tx.id">
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                            <td class="py-3.5 font-bold text-slate-850 dark:text-slate-200" x-text="tx.memberName"></td>
                                            <td class="py-3.5 text-slate-400" x-text="tx.bookTitle"></td>
                                            <td class="py-3.5 text-red-500 font-black" x-text="tx.dueDate"></td>
                                            <td class="py-3.5 text-amber-500 font-black" x-text="'Rp ' + tx.fine.toLocaleString('id-ID')"></td>
                                            <td class="py-3.5 text-right">
                                                <div class="flex justify-end gap-1.5">
                                                    <button 
                                                        @click="handleExtendBook(tx.id)"
                                                        class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-550 text-white rounded-lg text-[10px] font-bold transition-colors"
                                                    >
                                                        Perpanjang
                                                    </button>
                                                    <button 
                                                        @click="handleReturnBook(tx.id)"
                                                        class="px-2.5 py-1 bg-emerald-605 hover:bg-emerald-500 text-white rounded-lg text-[10px] font-bold transition-colors"
                                                    >
                                                        Pengembalian
                                                    </button>
                                                    <button 
                                                        @click="handlePayFine(tx.id)"
                                                        class="px-2.5 py-1 bg-amber-600 hover:bg-amber-500 text-white rounded-lg text-[10px] font-bold transition-colors"
                                                    >
                                                        Bayar Denda
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Quick Add and statistics summary -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col justify-between transition-colors shadow-sm bg-gradient-to-br from-indigo-600/[0.03] to-transparent">
                        <div>
                            <h3 class="font-black text-sm text-indigo-500">Pusat Pintasan Admin</h3>
                            <p class="text-xs text-slate-400 mb-6">Optimalkan sirkulasi digital dan laporan berkas.</p>
                            
                            <div class="space-y-3.5">
                                <button 
                                    @click="handleOpenAddBook()"
                                    class="w-full flex items-center justify-between p-3.5 rounded-xl border border-slate-100 dark:border-slate-800 text-left transition-all group bg-slate-50 dark:bg-slate-900/60 hover:border-indigo-500/50"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-indigo-600/10 text-indigo-500 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-800 dark:text-slate-200">Tambah File / E-Book</p>
                                            <p class="text-[10px] text-slate-400">Masukkan PDF & data stok fisik</p>
                                        </div>
                                    </div>
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                                </button>

                                <button 
                                    @click="simulateExport('Sirkulasi-Sistem')"
                                    class="w-full flex items-center justify-between p-3.5 rounded-xl border border-slate-100 dark:border-slate-800 text-left transition-all group bg-slate-50 dark:bg-slate-900/60 hover:border-emerald-500/50"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-emerald-500/10 text-emerald-500 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-800 dark:text-slate-200">Ekspor Laporan Transaksi</p>
                                            <p class="text-[10px] text-slate-400">Download data sirkulasi XLS</p>
                                        </div>
                                    </div>
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-[10px] text-slate-500 font-bold">
                            <span>SISTEM SINKRONISASI FILE</span>
                            <span class="flex items-center gap-1.5 text-emerald-500">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></span> ONLINE
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- TAB 2A: PHYSICAL BOOKS MANAGEMENT -->
            <div x-show="currentTab === 'physical_books'" class="space-y-6" style="display: none;">
                <!-- Filter controls block -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl transition-colors shadow-sm">
                    <div class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative flex-1 max-w-sm">
                            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                            <input 
                                type="text" 
                                placeholder="Cari judul, penulis, ISBN..." 
                                x-model="bookSearch"
                                class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="filter" class="w-4 h-4 text-slate-400"></i>
                            <select 
                                x-model="bookCategoryFilter"
                                class="rounded-xl text-xs py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 text-slate-700 dark:text-slate-200"
                            >
                                <option value="Semua">Semua Kategori</option>
                                <template x-for="cat in categories" :key="cat">
                                    <option :value="cat" x-text="cat"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button 
                            @click="simulateExport('Katalog-Buku-Fisik')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800"
                        >
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span>Download Excel</span>
                        </button>
                        <button 
                            @click="handleOpenAddBook(false)"
                            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md transition-colors w-full md:w-auto justify-center"
                        >
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Tambah Buku Fisik</span>
                        </button>
                    </div>
                </div>

                <!-- Books Grid Visual List -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-if="getFilteredPhysicalBooks().length === 0">
                        <div class="col-span-full p-12 text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
                            <i data-lucide="book" class="w-12 h-12 text-slate-400 mx-auto mb-4"></i>
                            <p class="text-slate-400 font-bold">Buku fisik tidak ditemukan</p>
                            <p class="text-xs text-slate-505 mt-1">Coba sesuaikan kata kunci pencarian atau filter Anda.</p>
                        </div>
                    </template>
                    <template x-for="book in getFilteredPhysicalBooks()" :key="book.id">
                        <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col justify-between hover:border-indigo-500/50 transition-all shadow-md">
                            <div>
                                <!-- Upper Header -->
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <div class="flex gap-3 items-center">
                                        <span :class="isDarkMode ? 'bg-slate-950' : 'bg-slate-100'" class="text-2xl p-2.5 rounded-xl" x-text="book.cover"></span>
                                        <div>
                                            <span class="text-[9px] px-2 py-0.5 font-bold bg-indigo-500/10 text-indigo-500 rounded-full border border-indigo-500/10" x-text="book.category"></span>
                                            <h4 class="font-black text-xs mt-1.5 leading-snug line-clamp-1 text-slate-900 dark:text-white" x-text="book.title"></h4>
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-mono font-bold text-slate-400" x-text="book.id"></span>
                                </div>

                                <!-- Mid Details -->
                                <div class="space-y-2 text-xs text-slate-400 mb-6">
                                    <div class="flex justify-between">
                                        <span>Penulis:</span>
                                        <span class="font-bold text-slate-700 dark:text-slate-200" x-text="book.author"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>ISBN:</span>
                                        <span class="font-mono text-[10px] text-slate-700 dark:text-slate-200" x-text="book.isbn"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Total Halaman:</span>
                                        <span class="text-slate-750 dark:text-slate-200" x-text="book.pages + ' hlm'"></span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2.5 border-t border-slate-100 dark:border-slate-850">
                                        <span>Stok / Ketersediaan:</span>
                                        <span :class="book.available > 0 ? 'text-emerald-500' : 'text-red-500'" class="font-bold" x-text="book.available + ' / ' + book.stock + ' Unit'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action buttons -->
                            <div class="flex gap-1.5 pt-4 border-t border-slate-100 dark:border-slate-850">
                                <button 
                                    @click="handleOpenEditBook(book)"
                                    :class="isDarkMode ? 'bg-slate-900 hover:bg-slate-800 border-slate-800 text-slate-200' : 'bg-slate-100 hover:bg-slate-200 border-slate-250 text-slate-700'"
                                    class="flex-1 flex justify-center items-center gap-1 py-1.5 rounded-xl text-[11px] font-bold transition-colors border"
                                >
                                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                    <span>Ubah</span>
                                </button>
                                <button 
                                    @click="handleDeleteBook(book.id, book.title)"
                                    class="p-2 bg-red-600/10 hover:bg-red-650 hover:text-white text-red-500 rounded-xl text-xs transition-colors border border-red-500/10"
                                    title="Hapus Buku"
                                >
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB 2B: EBOOKS MANAGEMENT -->
            <div x-show="currentTab === 'ebooks'" class="space-y-6" style="display: none;">
                <!-- Filter controls block -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl transition-colors shadow-sm">
                    <div class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative flex-1 max-w-sm">
                            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                            <input 
                                type="text" 
                                placeholder="Cari judul, penulis, ISBN..." 
                                x-model="bookSearch"
                                class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="filter" class="w-4 h-4 text-slate-400"></i>
                            <select 
                                x-model="bookCategoryFilter"
                                class="rounded-xl text-xs py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 text-slate-700 dark:text-slate-200"
                            >
                                <option value="Semua">Semua Kategori</option>
                                <template x-for="cat in categories" :key="cat">
                                    <option :value="cat" x-text="cat"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button 
                            @click="simulateExport('Katalog-E-Book')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800"
                        >
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span>Download Excel</span>
                        </button>
                        <button 
                            @click="handleOpenAddBook(true)"
                            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md transition-colors w-full md:w-auto justify-center"
                        >
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Tambah E-Book (PDF)</span>
                        </button>
                    </div>
                </div>

                <!-- Books Grid Visual List -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-if="getFilteredEbooks().length === 0">
                        <div class="col-span-full p-12 text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
                            <i data-lucide="file-text" class="w-12 h-12 text-slate-400 mx-auto mb-4"></i>
                            <p class="text-slate-400 font-bold">E-Book tidak ditemukan</p>
                            <p class="text-xs text-slate-505 mt-1">Coba sesuaikan kata kunci pencarian atau filter Anda.</p>
                        </div>
                    </template>
                    <template x-for="book in getFilteredEbooks()" :key="book.id">
                        <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col justify-between hover:border-indigo-500/50 transition-all shadow-md">
                            <div>
                                <!-- Upper Header -->
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <div class="flex gap-3 items-center">
                                        <span :class="isDarkMode ? 'bg-slate-950' : 'bg-slate-100'" class="text-2xl p-2.5 rounded-xl" x-text="book.cover"></span>
                                        <div>
                                            <span class="text-[9px] px-2 py-0.5 font-bold bg-indigo-500/10 text-indigo-500 rounded-full border border-indigo-500/10" x-text="book.category"></span>
                                            <h4 class="font-black text-xs mt-1.5 leading-snug line-clamp-1 text-slate-900 dark:text-white" x-text="book.title"></h4>
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-mono font-bold text-slate-400" x-text="book.id"></span>
                                </div>

                                <!-- Mid Details -->
                                <div class="space-y-2 text-xs text-slate-400 mb-6">
                                    <div class="flex justify-between">
                                        <span>Penulis:</span>
                                        <span class="font-bold text-slate-700 dark:text-slate-200" x-text="book.author"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>ISBN:</span>
                                        <span class="font-mono text-[10px] text-slate-700 dark:text-slate-200" x-text="book.isbn"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Ukuran Berkas:</span>
                                        <span class="text-sky-500 font-bold" x-text="book.file_size + ' (' + book.pages + ' hlm)'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action buttons -->
                            <div class="flex gap-1.5 pt-4 border-t border-slate-100 dark:border-slate-850">
                                <button 
                                    @click="openPdfReader(book)"
                                    class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-500 hover:text-white rounded-xl text-[11px] font-bold transition-all flex items-center gap-1"
                                    title="Simulasikan Membaca Berkas PDF"
                                >
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Baca</span>
                                </button>
                                <button 
                                    @click="handleOpenEditBook(book)"
                                    :class="isDarkMode ? 'bg-slate-900 hover:bg-slate-800 border-slate-800 text-slate-200' : 'bg-slate-100 hover:bg-slate-200 border-slate-250 text-slate-700'"
                                    class="flex-1 flex justify-center items-center gap-1 py-1.5 rounded-xl text-[11px] font-bold transition-colors border"
                                >
                                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                    <span>Ubah</span>
                                </button>
                                <button 
                                    @click="handleDeleteBook(book.id, book.title)"
                                    class="p-2 bg-red-600/10 hover:bg-red-650 hover:text-white text-red-500 rounded-xl text-xs transition-colors border border-red-500/10"
                                    title="Hapus E-Book"
                                >
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB 3: MEMBERS MANAGEMENT (Kelola Anggota) -->
            <div x-show="currentTab === 'members'" class="space-y-6" style="display: none;">
                <!-- Member filters bar -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl transition-colors shadow-sm">
                    <div class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative flex-1 max-w-sm">
                            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                            <input 
                                type="text" 
                                placeholder="Cari nama, email, ID anggota..." 
                                x-model="memberSearch"
                                class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 text-slate-700 dark:text-slate-200 transition-colors"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="filter" class="w-4 h-4 text-slate-400"></i>
                            <select 
                                x-model="memberStatusFilter"
                                class="rounded-xl text-xs py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 text-slate-700 dark:text-slate-200"
                            >
                                <option value="Semua">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Ditangguhkan">Ditangguhkan</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button 
                            @click="simulateExport('Daftar-Anggota')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800"
                        >
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span>Ekspor Member</span>
                        </button>
                        <button 
                            @click="handleOpenAddMember()"
                            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md transition-colors w-full md:w-auto justify-center"
                        >
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            <span>Daftarkan Anggota</span>
                        </button>
                    </div>
                </div>

                <!-- Members Table -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden transition-colors shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b font-bold uppercase tracking-wider text-[10px] bg-slate-50 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-800">
                                    <th class="p-4">ID & Anggota</th>
                                    <th class="p-4">Email Address</th>
                                    <th class="p-4">Tanggal Bergabung</th>
                                    <th class="p-4">Buku Dipinjam</th>
                                    <th class="p-4">Status Akun</th>
                                    <th class="p-4 text-right">Manajemen Hak Akses</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                <template x-if="getFilteredMembers().length === 0">
                                    <tr>
                                        <td colSpan="6" class="p-8 text-center text-slate-500">Tidak ada anggota terdaftar yang sesuai filter.</td>
                                    </tr>
                                </template>
                                <template x-for="member in getFilteredMembers()" :key="member.id">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center font-black text-indigo-500 text-xs shrink-0" x-text="member.name.substring(0,2).toUpperCase()"></div>
                                                <div>
                                                    <h5 class="font-black text-xs text-slate-900 dark:text-white" x-text="member.name"></h5>
                                                    <span class="text-[10px] text-slate-500 font-mono block" x-text="member.id"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-slate-400 font-medium" x-text="member.email"></td>
                                        <td class="p-4 text-slate-400 font-medium" x-text="member.joinedDate"></td>
                                        <td class="p-4">
                                            <span :class="isDarkMode ? 'bg-slate-950 text-slate-300' : 'bg-slate-100 text-slate-650'" class="px-2 py-1 rounded-lg text-xs font-bold" x-text="member.borrowedCount + ' Buku'"></span>
                                        </td>
                                        <td class="p-4">
                                            <span 
                                                :class="member.status === 'Aktif' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20'"
                                                class="px-2.5 py-0.5 text-[9px] font-black rounded-full border" 
                                                x-text="member.status"
                                            ></span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button 
                                                    @click="toggleMemberStatus(member.id)"
                                                    :class="member.status === 'Aktif' ? 'bg-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-white border-amber-500/20' : 'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white border-emerald-500/20'"
                                                    class="px-2.5 py-1 text-[10px] font-bold rounded-lg transition-colors border"
                                                    x-text="member.status === 'Aktif' ? 'Tangguhkan' : 'Aktifkan'"
                                                ></button>
                                                <button 
                                                    @click="handleOpenEditMember(member)"
                                                    :class="isDarkMode ? 'bg-slate-900 hover:bg-slate-800 border-slate-800 text-slate-200' : 'bg-slate-100 hover:bg-slate-200 border-slate-250 text-slate-700'"
                                                    class="flex-1 flex justify-center items-center gap-1 py-1.5 rounded-xl text-[11px] font-bold transition-colors border"
                                                    title="Edit Profil"
                                                >
                                                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                                    <span>Ubah</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: TRANSACTIONS / CIRCULATION VIEW (Transaksi Sirkulasi) -->
            <div x-show="currentTab === 'transactions'" class="space-y-6" style="display: none;">
                <!-- Circulation controls -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl transition-colors shadow-sm">
                    <div class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative flex-1 max-w-sm">
                            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                            <input 
                                type="text" 
                                placeholder="Cari ID, peminjaman, atau judul buku..." 
                                x-model="txSearch"
                                class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 text-slate-700 dark:text-slate-200 transition-colors"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="filter" class="w-4 h-4 text-slate-400"></i>
                            <select 
                                x-model="txStatusFilter"
                                class="rounded-xl text-xs py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 text-slate-700 dark:text-slate-200"
                            >
                                <option value="Semua">Semua Transaksi</option>
                                <option value="menunggu">Menunggu Persetujuan</option>
                                <option value="Dipinjam">Sedang Dipinjam</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Kembali">Sudah Kembali</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button 
                            @click="simulateExport('Riwayat-Sirkulasi')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800"
                        >
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span>Laporan Excel</span>
                        </button>
                        <button 
                            @click="handleOpenBorrow()"
                            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md transition-colors w-full md:w-auto justify-center"
                        >
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Proses Pinjam Baru</span>
                        </button>
                    </div>
                </div>

                <!-- Transactions Log Grid Table -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden transition-colors shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b font-bold uppercase tracking-wider text-[10px] bg-slate-50 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-800">
                                    <th class="p-4">ID Transaksi</th>
                                    <th class="p-4">Anggota</th>
                                    <th class="p-4">Buku Dipinjam</th>
                                    <th class="p-4">Tgl Pinjam</th>
                                    <th class="p-4">Batas Kembali</th>
                                    <th class="p-4">Tgl Kembali</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                <template x-if="getFilteredTransactions().length === 0">
                                    <tr>
                                        <td colSpan="8" class="p-8 text-center text-slate-500">Data sirkulasi kosong atau tidak sesuai kriteria filter.</td>
                                    </tr>
                                </template>
                                <template x-for="tx in getFilteredTransactions()" :key="tx.id">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                        <td class="p-4 font-mono font-black text-indigo-500" x-text="tx.id"></td>
                                        <td class="p-4">
                                            <div>
                                                <p class="font-bold text-slate-850 dark:text-slate-200" x-text="tx.memberName"></p>
                                                <span class="text-[10px] text-slate-500 font-medium" x-text="tx.memberId"></span>
                                            </div>
                                        </td>
                                        <td class="p-4 font-medium text-slate-800 dark:text-slate-200" x-text="tx.bookTitle"></td>
                                        <td class="p-4 text-slate-500 dark:text-slate-400 font-medium" x-text="tx.borrowDate"></td>
                                        <td class="p-4 text-slate-500 dark:text-slate-400 font-black" x-text="tx.dueDate"></td>
                                        <td class="p-4 text-slate-500 dark:text-slate-400 font-medium" x-text="tx.returnDate || '-'"></td>
                                        <td class="p-4">
                                            <span 
                                                :class="tx.status === 'Kembali' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : tx.status === 'Terlambat' ? 'bg-red-500/10 text-red-500 border-red-500/20' : tx.status === 'menunggu' ? 'bg-amber-500/10 text-amber-500 border-amber-500/20' : 'bg-sky-500/10 text-sky-500 border-sky-500/20'"
                                                class="px-2.5 py-0.5 text-[9px] font-black rounded-full border" 
                                                x-text="tx.status"
                                            ></span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <template x-if="tx.status === 'menunggu'">
                                                <div class="flex justify-end gap-1.5">
                                                    <button 
                                                        @click="handleApproveBook(tx.id)"
                                                        class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[10px] font-bold transition-colors"
                                                    >
                                                        Setujui
                                                    </button>
                                                </div>
                                            </template>
                                            <template x-if="tx.status !== 'Kembali' && tx.status !== 'menunggu'">
                                                <div class="flex justify-end gap-1.5">
                                                    <button 
                                                        @click="handleExtendBook(tx.id)"
                                                        class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-[10px] font-bold transition-colors"
                                                    >
                                                        Perpanjang
                                                    </button>
                                                    <button 
                                                        @click="handleReturnBook(tx.id)"
                                                        class="px-3 py-1 bg-emerald-600 hover:bg-emerald-555 text-white rounded-xl text-[10px] font-bold transition-colors"
                                                    >
                                                        Kembalikan
                                                    </button>
                                                </div>
                                            </template>
                                            <template x-if="tx.status === 'Kembali'">
                                                <span class="text-slate-500 font-medium text-xs">Selesai</span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 5: CIRCULATION FINES VIEW (Sirkulasi Denda) -->
            <div x-show="currentTab === 'categories'" class="space-y-6" style="display: none;">
                <!-- Fines filter controls -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl transition-colors shadow-sm">
                    <div class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative flex-1 max-w-sm">
                            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                            <input 
                                type="text" 
                                placeholder="Cari ID, nama anggota, atau judul buku..." 
                                x-model="fineSearch"
                                class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 text-slate-700 dark:text-slate-200 transition-colors"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="filter" class="w-4 h-4 text-slate-400"></i>
                            <select 
                                x-model="fineStatusFilter"
                                class="rounded-xl text-xs py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-slate-950 border border-slate-250 dark:border-slate-800 text-slate-700 dark:text-slate-200"
                            >
                                <option value="Semua">Semua Pembayaran</option>
                                <option value="pending">Menunggu Verifikasi</option>
                                <option value="unpaid">Belum Dibayar</option>
                                <option value="lunas">Lunas</option>
                            </select>
                        </div>
                    </div>

                    <button 
                        @click="simulateExport('Laporan-Sanksi-Denda')"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800"
                    >
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>Ekspor Laporan</span>
                    </button>
                </div>

                <!-- Fines Grid Table -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden transition-colors shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b font-bold uppercase tracking-wider text-[10px] bg-slate-50 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-800">
                                    <th class="p-4">ID Transaksi</th>
                                    <th class="p-4">Anggota</th>
                                    <th class="p-4">Buku</th>
                                    <th class="p-4">Tanggal Kembali</th>
                                    <th class="p-4">Tagihan Denda</th>
                                    <th class="p-4">Bukti Upload</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                <template x-if="getFilteredFines().length === 0">
                                    <tr>
                                        <td colSpan="8" class="p-8 text-center text-slate-500">Tidak ada sirkulasi denda yang sesuai kriteria.</td>
                                    </tr>
                                </template>
                                <template x-for="tx in getFilteredFines()" :key="tx.id">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                        <td class="p-4 font-mono font-black text-indigo-500" x-text="tx.id"></td>
                                        <td class="p-4">
                                            <div>
                                                <p class="font-bold text-slate-800 dark:text-slate-200" x-text="tx.memberName"></p>
                                                <span class="text-[10px] text-slate-500 font-medium" x-text="tx.memberId"></span>
                                            </div>
                                        </td>
                                        <td class="p-4 font-medium text-slate-800 dark:text-slate-200" x-text="tx.bookTitle"></td>
                                        <td class="p-4 text-slate-500 dark:text-slate-400 font-medium" x-text="tx.returnDate || 'Belum Kembali'"></td>
                                        <td class="p-4 text-red-500 font-black" x-text="'Rp ' + (tx.fine || 0).toLocaleString('id-ID')"></td>
                                        <td class="p-4">
                                            <template x-if="tx.paymentProof === 'cash_payment'">
                                                <span class="text-emerald-600 font-bold">Tunai</span>
                                            </template>
                                            <template x-if="tx.paymentProof && tx.paymentProof !== 'cash_payment'">
                                                <button 
                                                    @click="openFineModal(tx)"
                                                    class="flex items-center gap-1.5 px-2 py-1 bg-indigo-500/10 text-indigo-500 hover:bg-indigo-500 hover:text-white rounded-lg text-[10px] font-bold transition-all border border-indigo-500/10"
                                                >
                                                    <i class="fa-solid fa-image"></i> Lihat Bukti
                                                </button>
                                            </template>
                                            <template x-if="!tx.paymentProof">
                                                <span class="text-slate-400 italic">Belum Upload</span>
                                            </template>
                                        </td>
                                        <td class="p-4">
                                            <span 
                                                :class="tx.finePaid ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : tx.paymentStatus === 'pending' ? 'bg-amber-500/10 text-amber-500 border-amber-500/20' : tx.paymentStatus === 'rejected' ? 'bg-red-500/10 text-red-500 border-red-500/20' : 'bg-slate-500/10 text-slate-500 dark:text-slate-400 border-slate-500/20'"
                                                class="px-2.5 py-0.5 text-[9px] font-black rounded-full border" 
                                                x-text="tx.finePaid ? 'Lunas' : tx.paymentStatus === 'pending' ? 'Pending Verifikasi' : tx.paymentStatus === 'rejected' ? 'Ditolak' : 'Belum Bayar'"
                                            ></span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <div class="flex justify-end gap-1.5">
                                                <button 
                                                    @click="openFineModal(tx)"
                                                    class="px-3 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-[10px] font-bold transition-colors"
                                                >
                                                    Detail
                                                </button>
                                                <template x-if="!tx.finePaid">
                                                    <button 
                                                        @click="handleApproveFinePayment(tx.id)"
                                                        class="px-3 py-1 bg-emerald-600 hover:bg-emerald-555 text-white rounded-xl text-[10px] font-bold transition-colors"
                                                    >
                                                        Lunas
                                                    </button>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- BOOK MODAL (ADD / EDIT) -->
    <div 
        x-show="isBookModalOpen"
        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            @click.away="isBookModalOpen = false"
            class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl p-6 space-y-6 shadow-2xl relative border border-slate-100 dark:border-slate-800"
        >
            <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="font-black text-sm text-slate-900 dark:text-white" x-text="isEditingBook ? 'Edit Data Buku' : 'Tambah Buku Baru'"></h3>
                <button @click="isBookModalOpen = false" class="text-slate-400 hover:text-slate-500"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form @submit.prevent="handleSaveBook()" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">Judul Buku</label>
                        <input type="text" x-model="bookForm.title" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1.5 focus:ring-indigo-500" />
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">Penulis</label>
                        <input type="text" x-model="bookForm.author" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1.5 focus:ring-indigo-500" />
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">Kategori</label>
                        <select x-model="bookForm.category" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1.5 focus:ring-indigo-500">
                            <template x-for="cat in categories" :key="cat">
                                <option :value="cat" x-text="cat" :selected="bookForm.category === cat"></option>
                            </template>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">ISBN</label>
                        <input type="text" x-model="bookForm.isbn" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1.5 focus:ring-indigo-500" />
                    </div>
                    <div x-show="!bookForm.is_ebook" class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">Total Stok</label>
                        <input type="number" min="0" x-model.number="bookForm.stock" :required="!bookForm.is_ebook" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1.5 focus:ring-indigo-500" />
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">Halaman Buku</label>
                        <input type="number" min="1" x-model.number="bookForm.pages" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1.5 focus:ring-indigo-500" />
                    </div>
                </div>

                <div x-show="bookForm.is_ebook" class="space-y-1.5" style="display: none;">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Berkas PDF E-Book (URL atau File)</label>
                    <input type="text" x-model="bookForm.pdf_url" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1.5 focus:ring-indigo-500 mb-2" placeholder="e.g. https://example.com/book.pdf" />
                    <input type="file" accept="application/pdf" @change="handlePdfUpload" class="w-full text-xs text-slate-700 dark:text-slate-200 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-500/10 dark:file:bg-indigo-500/20 file:text-indigo-600 dark:file:text-indigo-400 hover:file:bg-indigo-600 hover:file:text-white" />
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="isBookModalOpen = false" class="px-4 py-2.5 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-xs hover:bg-slate-50 dark:hover:bg-slate-850">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-550 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MEMBER MODAL (ADD / EDIT) -->
    <div 
        x-show="isMemberModalOpen"
        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            @click.away="isMemberModalOpen = false"
            class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-2xl p-6 space-y-6 shadow-2xl relative border border-slate-100 dark:border-slate-800"
        >
            <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="font-black text-sm text-slate-900 dark:text-white" x-text="isEditingMember ? 'Ubah Profil Anggota' : 'Daftarkan Anggota Baru'"></h3>
                <button @click="isMemberModalOpen = false" class="text-slate-400 hover:text-slate-500"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form @submit.prevent="handleSaveMember()" class="space-y-4">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Nama Lengkap</label>
                    <input type="text" x-model="memberForm.name" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-indigo-500" />
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Alamat Email</label>
                    <input type="email" x-model="memberForm.email" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-indigo-500" />
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Status Anggota</label>
                    <select x-model="memberForm.status" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-indigo-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Ditangguhkan">Ditangguhkan</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="isMemberModalOpen = false" class="px-4 py-2.5 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-xs hover:bg-slate-50 dark:hover:bg-slate-855">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-550 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10">Simpan Anggota</button>
                </div>
            </form>
        </div>
    </div>

    <!-- BORROW MODAL (NEW CIRCULATION) -->
    <div 
        x-show="isBorrowModalOpen"
        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            @click.away="isBorrowModalOpen = false"
            class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-2xl p-6 space-y-6 shadow-2xl relative border border-slate-100 dark:border-slate-800"
        >
            <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="font-black text-sm text-slate-900 dark:text-white">Proses Peminjaman Baru</h3>
                <button @click="isBorrowModalOpen = false" class="text-slate-400 hover:text-slate-500"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form @submit.prevent="handleSaveBorrow()" class="space-y-4">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Pilih Anggota</label>
                    <select x-model="borrowForm.memberId" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-indigo-500">
                        <option value="">-- Pilih Peminjam --</option>
                        <template x-for="m in members" :key="m.id">
                            <option :value="m.id" x-text="m.name + ' (' + m.id + ')'"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Pilih Buku Fisik</label>
                    <select x-model="borrowForm.bookId" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-indigo-500">
                        <option value="">-- Pilih Buku --</option>
                        <template x-for="b in books" :key="b.id">
                            <option :value="b.id" x-text="b.title + ' (' + b.available + ' Tersedia)'" :disabled="b.available <= 0"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Durasi Peminjaman (Hari)</label>
                    <select x-model.number="borrowForm.borrowDays" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-indigo-500">
                        <option value="3">3 Hari</option>
                        <option value="7">7 Hari (Standar)</option>
                        <option value="14">14 Hari</option>
                        <option value="30">30 Hari</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="isBorrowModalOpen = false" class="px-4 py-2.5 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-xs hover:bg-slate-50 dark:hover:bg-slate-850">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-550 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10">Proses Peminjaman</button>
                </div>
            </form>
        </div>
    </div>

    <!-- PDF E-BOOK READER MODAL -->
    <div 
        x-show="activeReaderBook"
        class="fixed inset-0 z-50 bg-slate-950 flex flex-col"
        style="display: none;"
    >
        <!-- Header Controls -->
        <header class="bg-slate-900 border-b border-slate-800 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl" x-text="activeReaderBook?.cover"></span>
                <div>
                    <h4 class="font-black text-sm text-white" x-text="activeReaderBook?.title"></h4>
                    <p class="text-[10px] text-indigo-500 font-semibold" x-text="'Simulator Pembaca Digital Berkas • ' + activeReaderBook?.fileSize"></p>
                </div>
            </div>

            <!-- Page indicators -->
            <div class="flex items-center gap-2 bg-slate-950 border border-slate-855 rounded-full px-3 py-1 text-xs font-semibold text-white">
                <button 
                    @click="if (readerPage > 1) readerPage--"
                    :disabled="readerPage === 1"
                    class="w-6 h-6 rounded-full flex items-center justify-center hover:bg-slate-800 disabled:opacity-30"
                >
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </button>
                <span x-text="'Halaman ' + readerPage + ' dari ' + (activeReaderBook?.pages || 100)"></span>
                <button 
                    @click="if (readerPage < (activeReaderBook?.pages || 100)) readerPage++"
                    :disabled="readerPage >= (activeReaderBook?.pages || 100)"
                    class="w-6 h-6 rounded-full flex items-center justify-center hover:bg-slate-800 disabled:opacity-30"
                >
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>

            <div class="flex items-center gap-3.5">
                <button 
                    @click="downloadOriginalPdf()"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition-all"
                >
                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                    <span>Unduh PDF</span>
                </button>
                <button 
                    @click="activeReaderBook = null"
                    class="px-3.5 py-1.5 bg-red-600 hover:bg-red-500 text-white rounded-lg text-xs font-bold transition-all"
                >
                    Keluar
                </button>
            </div>
        </header>

        <!-- Reader Page Container Body -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Simulated Content / Iframe PDF -->
            <div class="flex-grow flex-shrink bg-slate-800 overflow-y-auto p-6 flex justify-center items-start">
                <template x-if="activeReaderBook?.pdf_url">
                    <iframe :src="activeReaderBook.pdf_url" class="w-full h-full min-h-[600px] border-0 rounded-xl shadow-2xl bg-slate-900"></iframe>
                </template>
                <template x-if="!activeReaderBook?.pdf_url">
                    <div class="bg-white text-slate-800 w-full max-w-2xl rounded-xl shadow-2xl p-8 min-h-[600px] flex flex-col justify-between select-none">
                    <div>
                        <!-- Title Sheet -->
                        <div class="flex justify-between items-center border-b pb-4 mb-6">
                            <span class="text-xs uppercase font-extrabold tracking-widest text-indigo-500" x-text="activeReaderBook?.category"></span>
                            <span class="text-xs text-slate-400 font-bold" x-text="activeReaderBook?.title"></span>
                        </div>

                        <!-- Content Mock page text -->
                        <div class="space-y-4 text-sm leading-relaxed text-slate-700">
                            <!-- Page 1 Intro -->
                            <template x-if="readerPage === 1">
                                <div>
                                    <h2 class="text-xl font-extrabold text-slate-900 mb-4">Pengantar & Prakata</h2>
                                    <p>Selamat datang di edisi digital dari karya legendaris yang berjudul <strong x-text="'&ldquo;' + activeReaderBook?.title + '&rdquo;'"></strong>. Dokumen ini merupakan representasi arsip digital berkualitas tinggi dengan format orisinal yang dioptimalkan untuk performa perangkat lunak e-reader masa kini.</p>
                                    <p class="mt-3">Membaca merupakan jembatan emas pengetahuan. Di era transformasi digital ini, perpustakaan berkomitmen menyediakan aksesibilitas informasi yang luas dan terstandarisasi untuk semua pembelajar.</p>
                                </div>
                            </template>

                            <!-- Page 2 and above Simulated pages when logged in/unlocked -->
                            <template x-if="readerPage > 1 && (readerPage < 5 || isReaderLoggedIn)">
                                <div>
                                    <h2 class="text-xl font-extrabold text-slate-900 mb-4" x-text="'Bab ' + (readerPage - 1)"></h2>
                                    <p>Dalam bagian ini, penulis menguraikan konsep dasar mengenai topik utama yang dibahas. Penjabaran materi dilakukan secara komprehensif didukung data empiris serta studi kasus relevan.</p>
                                    <p class="mt-3">Secara teoritis, integrasi sistem perpustakaan digital terbukti mempercepat proses pencarian literatur referensi ilmiah hingga 70% dibandingkan metode pengatalogan manual konvensional.</p>
                                    <p class="mt-3">Analisis mendalam pada halaman <span x-text="readerPage"></span> menunjukkan bahwa implementasi teknologi cloud pada pangkalan data e-book secara signifikan menekan latensi pembacaan file besar.</p>
                                </div>
                            </template>

                            <!-- Page 5 Locked Page (Simulated Login wall / Premium wall) -->
                            <template x-if="readerPage >= 5 && !isReaderLoggedIn">
                                <div class="text-center p-8 space-y-6">
                                    <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xl mx-auto">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                    <div class="space-y-2">
                                        <h3 class="font-extrabold text-lg text-slate-900">Batas Pratinjau Terbatas Tercapai</h3>
                                        <p class="text-xs text-slate-505 max-w-sm mx-auto leading-relaxed">
                                            Anda sedang masuk sebagai tamu / simulator admin. Silakan masuk dengan akun anggota premium perpustakaan Anda untuk membuka seluruh sisa bab buku ini.
                                        </p>
                                    </div>

                                    <!-- Simulated Login inside simulator -->
                                    <div x-show="!isReaderLoggedIn" class="max-w-xs mx-auto p-4 border rounded-xl space-y-3.5 bg-slate-50 text-left">
                                        <h4 class="font-bold text-xs text-slate-700">Verifikasi Masuk Anggota</h4>
                                        <div class="space-y-1">
                                            <input type="email" placeholder="Email Anggota" x-model="readerLoginEmail" class="w-full px-3 py-2 border rounded-lg text-xs" />
                                        </div>
                                        <div class="space-y-1">
                                            <input type="password" placeholder="Password" x-model="readerLoginPassword" class="w-full px-3 py-2 border rounded-lg text-xs" />
                                        </div>
                                        <button 
                                            @click="verifyReaderLogin()"
                                            class="w-full py-2 bg-indigo-600 hover:bg-indigo-550 text-white font-bold text-xs rounded-lg transition-colors"
                                        >
                                            Verifikasi & Baca Penuh
                                        </button>
                                    </div>
                                    <div x-show="isReaderLoggedIn" class="p-4 border rounded-xl bg-emerald-50 border-emerald-250 text-emerald-800 text-xs font-semibold max-w-xs mx-auto">
                                        <i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i>
                                        Terima kasih telah melakukan verifikasi masuk! Anda sekarang dapat menikmati keseluruhan halaman eksklusif dari buku ini tanpa batas.
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Footer indicators -->
                    <div class="border-t pt-4 flex justify-between items-center text-[10px] text-slate-400 font-bold">
                        <span x-text="'PENERBIT: LITERASIKU DIGITAL ACADEMY'"></span>
                        <span x-text="'HALAMAN ' + readerPage + ' / ' + (activeReaderBook?.pages || 100)"></span>
                    </div>
                </div>
                </template>
            </div>
        </div>
    </div>

    <!-- FINE PROOF MODAL (VERIFICATION) -->
    <div 
        x-show="isFineModalOpen"
        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            @click.away="isFineModalOpen = false"
            class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl relative border-2 border-indigo-600 animate-fadeIn"
        >
            <!-- Card Header -->
            <div class="bg-indigo-900 text-white p-4 px-6 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-sm">Verifikasi Pembayaran Denda</h3>
                    <p class="text-[9px] opacity-80" x-text="'Transaksi #' + selectedFineTx?.id"></p>
                </div>
                <button @click="isFineModalOpen = false" class="text-white opacity-80 hover:opacity-100"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <!-- Card Content (Two Columns like pay_fine.blade.php) -->
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Sisi Kiri: Detail Denda -->
                <div class="p-6 border-r border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 space-y-4">
                    <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 p-4 rounded-xl space-y-3 shadow-sm">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400 font-bold text-[9px] uppercase tracking-wider">Anggota</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedFineTx?.memberName"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs border-t border-slate-100 dark:border-slate-850 pt-2">
                            <span class="text-slate-400 font-bold text-[9px] uppercase tracking-wider">Buku</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 truncate max-w-[180px]" x-text="selectedFineTx?.bookTitle"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs border-t border-slate-100 dark:border-slate-850 pt-2">
                            <span class="text-slate-400 font-bold text-[9px] uppercase tracking-wider">Tgl Kembali</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedFineTx?.returnDate || 'Belum Kembali'"></span>
                        </div>
                        <div class="border-t border-dashed border-indigo-200 dark:border-indigo-900 pt-3 mt-3 flex justify-between items-center">
                            <span class="text-red-500 font-bold text-[10px] uppercase tracking-wider">Total Denda</span>
                            <span class="text-red-500 font-black text-lg" x-text="'Rp ' + (selectedFineTx?.fine || 0).toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <!-- Status Display -->
                    <div class="p-4 rounded-xl border flex items-center gap-3 text-xs font-semibold"
                        :class="selectedFineTx?.finePaid ? 'bg-emerald-50 dark:bg-emerald-955/20 border-emerald-200 text-emerald-800 dark:text-emerald-400' : selectedFineTx?.paymentStatus === 'pending' ? 'bg-amber-50 dark:bg-amber-955/20 border-amber-200 text-amber-800 dark:text-amber-400' : 'bg-slate-50 dark:bg-slate-900 border-slate-200 text-slate-700 dark:text-slate-400'"
                    >
                        <template x-if="selectedFineTx?.finePaid">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Pembayaran Terverifikasi (Lunas)</div>
                        </template>
                        <template x-if="!selectedFineTx?.finePaid && selectedFineTx?.paymentStatus === 'pending'">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-circle-info text-amber-500"></i> Menunggu Konfirmasi Admin</div>
                        </template>
                        <template x-if="!selectedFineTx?.finePaid && selectedFineTx?.paymentStatus === 'rejected'">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-circle-xmark text-red-500"></i> Bukti Transfer Ditolak</div>
                        </template>
                        <template x-if="!selectedFineTx?.finePaid && selectedFineTx?.paymentStatus !== 'pending' && selectedFineTx?.paymentStatus !== 'rejected'">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-circle-question text-slate-500"></i> Belum Mengunggah Bukti</div>
                        </template>
                    </div>
                </div>

                <!-- Sisi Kanan: Bukti Pembayaran -->
                <div class="p-6 flex flex-col justify-between space-y-4">
                    <div class="space-y-2 flex-grow flex flex-col">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bukti Transfer Anggota</label>
                        
                        <div class="flex-grow border-2 border-dashed border-slate-250 dark:border-slate-800 rounded-2xl bg-slate-50 dark:bg-slate-950/30 overflow-hidden relative min-h-[160px] flex items-center justify-center">
                                <template x-if="selectedFineTx?.paymentProof === 'cash_payment'">
                                    <div class="text-center p-4 space-y-2">
                                        <i class="fa-solid fa-money-bill-wave text-emerald-500 text-3xl"></i>
                                        <p class="text-xs text-emerald-600 font-bold">Pembayaran Tunai (Cash)</p>
                                    </div>
                                </template>
                                <template x-if="selectedFineTx?.paymentProof && selectedFineTx?.paymentProof !== 'cash_payment'">
                                    <img :src="'/storage/' + selectedFineTx.paymentProof" alt="Bukti Transfer" class="w-full h-full object-contain" />
                                </template>
                                <template x-if="!selectedFineTx?.paymentProof">
                                    <div class="text-center p-4 space-y-2">
                                        <i data-lucide="image-off" class="w-8 h-8 text-slate-400 mx-auto"></i>
                                        <p class="text-xs text-slate-400 italic">Bukti transfer belum diunggah</p>
                                    </div>
                                </template>
                            </div>
                    </div>

                    <!-- Verification Actions -->
                    <template x-if="!selectedFineTx?.finePaid && selectedFineTx?.paymentProof">
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <button 
                                @click="handleRejectFinePayment(selectedFineTx.id)"
                                class="py-2.5 bg-red-600 hover:bg-red-550 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center justify-center gap-1.5"
                            >
                                <i class="fa-solid fa-circle-xmark"></i> Tolak Bukti
                            </button>
                            <button 
                                @click="handleApproveFinePayment(selectedFineTx.id)"
                                class="py-2.5 bg-emerald-600 hover:bg-emerald-555 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center justify-center gap-1.5"
                            >
                                <i class="fa-solid fa-circle-check"></i> Konfirmasi Lunas
                            </button>
                        </div>
                    </template>
                    <template x-if="!selectedFineTx?.finePaid && !selectedFineTx?.paymentProof">
                        <button 
                            @click="handleApproveFinePayment(selectedFineTx?.id)"
                            class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-550 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center justify-center gap-1.5 mt-4"
                        >
                            <i class="fa-solid fa-circle-check"></i> Tandai Lunas Manual
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- APP JAVASCRIPT CONTROLS -->
    <script>
        function adminApp() {
            return {
                csrfToken: '{{ csrf_token() }}',
                isDarkMode: true,
                currentTab: 'dashboard',
                toast: null,
                showNotifications: false,
                categoryInput: '',
                bookSearch: '',
                bookCategoryFilter: 'Semua',
                memberSearch: '',
                memberStatusFilter: 'Semua',
                txSearch: '',
                txStatusFilter: 'Semua',
                fineSearch: '',
                fineStatusFilter: 'Semua',
                isFineModalOpen: false,
                selectedFineTx: null,

                // Reader Simulator State
                activeReaderBook: null,
                readerPage: 1,
                isReaderLoggedIn: false,
                readerLoginEmail: '',
                readerLoginPassword: '',

                // Forms State
                isBookModalOpen: false,
                isEditingBook: false,
                bookForm: {
                    id: '', title: '', author: '', category: '', isbn: '', stock: 1, available: 1, cover: '📚', file_size: '2.0 MB', pages: 100, pdf_url: '', pdf_file: null
                },

                isMemberModalOpen: false,
                isEditingMember: false,
                memberForm: {
                    id: '', name: '', email: '', status: 'Aktif'
                },

                isBorrowModalOpen: false,
                borrowForm: {
                    memberId: '', bookId: '', borrowDays: 7
                },

                // Real Database Arrays injected via Blade
                categories: @json($categories),
                books: @json($books),
                members: @json($members),
                transactions: @json($transactions),

                notifications: [
                    { id: 1, text: 'Sistem database MySQL aktif dan terhubung.', type: 'success', time: 'Baru saja' }
                ],

                // Charts Reference
                circulationChartInstance: null,
                categoriesChartInstance: null,

                init() {
                    this.calculateOverdueFines();
                    this.$nextTick(() => {
                        this.initCharts();
                        lucide.createIcons();
                    });
                },

                triggerToast(message, type = 'success') {
                    this.toast = { message, type };
                    setTimeout(() => {
                        this.toast = null;
                    }, 4000);
                },

                toggleTheme() {
                    this.isDarkMode = !this.isDarkMode;
                    this.triggerToast(`Berhasil beralih ke Mode ${this.isDarkMode ? 'Gelap' : 'Terang'}`);
                    this.$nextTick(() => this.initCharts());
                },

                switchTab(tabName) {
                    this.currentTab = tabName;
                    this.showNotifications = false;
                    this.$nextTick(() => {
                        if (tabName === 'dashboard') {
                            this.initCharts();
                        }
                        lucide.createIcons();
                    });
                },

                getOverdueCount() {
                    return this.transactions.filter(t => t.status === 'Terlambat').length;
                },

                calculateOverdueFines() {
                    const today = new Date();
                    this.transactions = this.transactions.map(tx => {
                        if (tx.status === 'Dipinjam') {
                            const dueDate = new Date(tx.dueDate);
                            if (today > dueDate) {
                                const diffTime = Math.abs(today - dueDate);
                                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                const calculatedFine = diffDays * 2000;
                                return { ...tx, status: 'Terlambat', fine: calculatedFine };
                            }
                        }
                        return tx;
                    });
                },

                getStats() {
                    const totalPhysicalCount = this.books.filter(b => !b.is_ebook).length;
                    const totalEbookCount = this.books.filter(b => b.is_ebook).length;
                    const totalBooksCount = totalPhysicalCount + totalEbookCount;
                    const activeMembersCount = this.members.filter(m => m.status === 'Aktif').length;
                    const borrowedBooksCount = this.transactions.filter(t => t.status === 'Dipinjam' || t.status === 'Terlambat').length;
                    const overdueCount = this.getOverdueCount();
                    const unpaidFines = this.transactions
                        .filter(t => !t.finePaid)
                        .reduce((sum, t) => sum + (t.fine || 0), 0);

                    return {
                        totalBooks: totalBooksCount,
                        activeMembers: activeMembersCount,
                        borrowedBooks: borrowedBooksCount,
                        overdue: overdueCount,
                        fines: unpaidFines
                    };
                },

                clearNotifications() {
                    this.notifications = [];
                    this.triggerToast('Semua notifikasi dibersihkan');
                },

                // Filters logic
                getFilteredBooks() {
                    const search = this.bookSearch.toLowerCase().trim();
                    return this.books.filter(b => {
                        const matchSearch = b.title.toLowerCase().includes(search) || 
                                            b.author.toLowerCase().includes(search) || 
                                            b.isbn.includes(search);
                        const matchCat = this.bookCategoryFilter === 'Semua' || b.category === this.bookCategoryFilter;
                        return matchSearch && matchCat;
                    });
                },

                getFilteredPhysicalBooks() {
                    return this.getFilteredBooks().filter(b => !b.is_ebook);
                },

                getFilteredEbooks() {
                    return this.getFilteredBooks().filter(b => b.is_ebook);
                },

                getFilteredMembers() {
                    const search = this.memberSearch.toLowerCase().trim();
                    return this.members.filter(m => {
                        const matchSearch = m.name.toLowerCase().includes(search) || 
                                            m.email.toLowerCase().includes(search) || 
                                            m.id.includes(search);
                        const matchStatus = this.memberStatusFilter === 'Semua' || m.status === this.memberStatusFilter;
                        return matchSearch && matchStatus;
                    });
                },

                getFilteredTransactions() {
                    const search = this.txSearch.toLowerCase().trim();
                    return this.transactions.filter(t => {
                        const matchSearch = t.memberName.toLowerCase().includes(search) || 
                                            t.bookTitle.toLowerCase().includes(search) || 
                                            t.id.toLowerCase().includes(search) || 
                                            t.memberId.toLowerCase().includes(search);
                        const matchStatus = this.txStatusFilter === 'Semua' || t.status === this.txStatusFilter;
                        return matchSearch && matchStatus;
                    });
                },

                getFilteredFines() {
                    const search = this.fineSearch.toLowerCase().trim();
                    return this.transactions.filter(t => t.fine > 0).filter(t => {
                        const matchSearch = t.id.toLowerCase().includes(search) || 
                                            t.memberName.toLowerCase().includes(search) || 
                                            t.bookTitle.toLowerCase().includes(search) || 
                                            t.memberId.toLowerCase().includes(search);
                        let matchStatus = true;
                        if (this.fineStatusFilter === 'pending') {
                            matchStatus = !t.finePaid && t.paymentStatus === 'pending';
                        } else if (this.fineStatusFilter === 'unpaid') {
                            matchStatus = !t.finePaid && t.paymentStatus !== 'pending';
                        } else if (this.fineStatusFilter === 'lunas') {
                            matchStatus = !!t.finePaid;
                        }
                        return matchSearch && matchStatus;
                    });
                },

                generateUniqueBookId() {
                    let nextNum = 1;
                    this.books.forEach(b => {
                        if (b.id && b.id.startsWith('B-')) {
                            const num = parseInt(b.id.substring(2));
                            if (!isNaN(num) && num >= nextNum) {
                                nextNum = num + 1;
                            }
                        }
                    });
                    
                    let candidateId;
                    do {
                        candidateId = 'B-' + String(nextNum).padStart(3, '0');
                        const candidateId4 = 'B-' + String(nextNum).padStart(4, '0');
                        
                        const exists = this.books.some(b => b.id === candidateId || b.id === candidateId4);
                        if (!exists) {
                            return candidateId4;
                        }
                        nextNum++;
                    } while (true);
                },

                // Books actions
                handleOpenAddBook(isEbook = false) {
                    this.bookForm = {
                        id: this.generateUniqueBookId(),
                        title: '',
                        author: '',
                        category: this.categories[0] || 'Novel',
                        isbn: '',
                        stock: isEbook ? 9999 : 1,
                        available: isEbook ? 9999 : 1,
                        cover: '📚',
                        file_size: isEbook ? '1.5 MB' : '-',
                        pages: 150,
                        pdf_url: '',
                        is_ebook: isEbook,
                        pdf_file: null
                    };
                    this.isEditingBook = false;
                    this.isBookModalOpen = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                handleOpenEditBook(book) {
                    this.bookForm = {
                        id: book.id,
                        title: book.title,
                        author: book.author,
                        category: book.category,
                        isbn: book.isbn,
                        stock: book.stock,
                        available: book.available,
                        cover: book.cover,
                        file_size: book.file_size || (book.is_ebook ? '1.5 MB' : '-'),
                        pages: book.pages,
                        pdf_url: book.pdf_url || '',
                        is_ebook: book.is_ebook,
                        pdf_file: null
                    };
                    this.isEditingBook = true;
                    this.isBookModalOpen = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                handlePdfUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.bookForm.file_size = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                        this.bookForm.pdf_file = file; // Store the actual file object!
                        this.bookForm.pdf_url = '/uploads/' + file.name; // Simulative URL for database path
                        this.triggerToast(`File PDF "${file.name}" terpilih`);
                    }
                },

                handleSaveBook() {
                    const url = this.isEditingBook ? `/admin/books/${this.bookForm.id}` : '/admin/books';
                    
                    const formData = new FormData();
                    formData.append('id', this.bookForm.id);
                    formData.append('title', this.bookForm.title);
                    formData.append('author', this.bookForm.author);
                    formData.append('category', this.bookForm.category);
                    formData.append('isbn', this.bookForm.isbn);
                    formData.append('stock', this.bookForm.stock);
                    formData.append('available', this.bookForm.available);
                    formData.append('cover', this.bookForm.cover);
                    formData.append('file_size', this.bookForm.file_size);
                    formData.append('pages', this.bookForm.pages);
                    
                    if (this.bookForm.pdf_file) {
                        formData.append('pdf_file', this.bookForm.pdf_file);
                    } else if (this.bookForm.pdf_url) {
                        formData.append('pdf_url', this.bookForm.pdf_url);
                    }
                    
                    if (this.isEditingBook) {
                        formData.append('_method', 'PUT');
                    }

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => {
                        if (!res.ok) {
                            return res.json().then(err => {
                                let errorMsg = err.message || 'Gagal menyimpan data buku.';
                                if (err.errors) {
                                    const details = Object.values(err.errors).flat().join('\n');
                                    errorMsg += '\n' + details;
                                }
                                throw new Error(errorMsg);
                            });
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            if (this.isEditingBook) {
                                this.books = this.books.map(b => b.id === this.bookForm.id ? data.book : b);
                                this.triggerToast(`Buku "${this.bookForm.title}" berhasil diperbarui`);
                            } else {
                                this.books.push(data.book);
                                this.triggerToast(`Buku "${this.bookForm.title}" berhasil ditambahkan`);
                            }
                            this.isBookModalOpen = false;
                            this.$nextTick(() => this.initCharts());
                        } else {
                            alert('Gagal menyimpan data buku.');
                        }
                    })
                    .catch(err => {
                        alert(err.message);
                    });
                },

                handleDeleteBook(id, title) {
                    if (confirm(`Apakah Anda yakin ingin menghapus buku "${title}"?`)) {
                        fetch(`/admin/books/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.books = this.books.filter(b => b.id !== id);
                                this.triggerToast(`Buku "${title}" berhasil dihapus`, 'error');
                                this.$nextTick(() => this.initCharts());
                            }
                        });
                    }
                },

                // Members actions
                handleOpenAddMember() {
                    this.memberForm = {
                        id: `M-00${this.members.length + 1}`,
                        name: '',
                        email: '',
                        status: 'Aktif'
                    };
                    this.isEditingMember = false;
                    this.isMemberModalOpen = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                handleOpenEditMember(member) {
                    this.memberForm = {
                        id: member.id,
                        name: member.name,
                        email: member.email,
                        status: member.status
                    };
                    this.isEditingMember = true;
                    this.isMemberModalOpen = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                handleSaveMember() {
                    const url = this.isEditingMember ? `/admin/members/${this.memberForm.id}` : '/admin/members';
                    const method = this.isEditingMember ? 'PUT' : 'POST';

                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify(this.memberForm)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (this.isEditingMember) {
                                this.members = this.members.map(m => m.id === this.memberForm.id ? { ...m, ...this.memberForm } : m);
                                this.triggerToast(`Profil "${this.memberForm.name}" berhasil diubah`);
                            } else {
                                this.members.push(data.member);
                                this.triggerToast(`Anggota "${this.memberForm.name}" berhasil didaftarkan`);
                            }
                            this.isMemberModalOpen = false;
                        } else {
                            alert('Gagal menyimpan data anggota.');
                        }
                    });
                },

                toggleMemberStatus(id) {
                    fetch(`/admin/members/${id}/toggle`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.members = this.members.map(m => {
                                if (m.id === id) {
                                    this.triggerToast(`Status akun "${m.name}" diubah menjadi ${data.status}`, data.status === 'Aktif' ? 'success' : 'error');
                                    return { ...m, status: data.status };
                                }
                                return m;
                            });
                        }
                    });
                },

                // Borrow / Circulation actions
                handleOpenBorrow() {
                    this.borrowForm = {
                        memberId: '',
                        bookId: '',
                        borrowDays: 7
                    };
                    this.isBorrowModalOpen = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                handleSaveBorrow() {
                    const member = this.members.find(m => m.id === this.borrowForm.memberId);
                    const book = this.books.find(b => b.id === this.borrowForm.bookId);

                    if (!member || !book) return;

                    if (member.status !== 'Aktif') {
                        alert('Peminjam sedang ditangguhkan. Silakan aktifkan kembali status anggota.');
                        return;
                    }

                    if (book.available <= 0) {
                        alert('Stok buku kosong/tidak tersedia saat ini.');
                        return;
                    }

                    fetch('/admin/loans', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify(this.borrowForm)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const tx = data.transaction;
                            this.transactions.push(tx);

                            // update local stocks
                            this.books = this.books.map(b => b.id === book.id ? { ...b, available: b.available - 1 } : b);
                            // update local borrowed counts
                            this.members = this.members.map(m => m.id === member.id ? { ...m, borrowedCount: m.borrowedCount + 1 } : m);

                            this.triggerToast(`Transaksi peminjaman "${book.title}" berhasil diproses`);
                            this.isBorrowModalOpen = false;
                        } else {
                            alert(data.message || 'Gagal memproses peminjaman.');
                        }
                    });
                },

                handleExtendBook(txId) {
                    fetch(`/admin/loans/${txId}/extend`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.transactions = this.transactions.map(tx => {
                                if (tx.id === txId) {
                                    this.triggerToast(`Masa peminjaman buku "${tx.bookTitle}" diperpanjang 7 hari`);
                                    return { ...tx, dueDate: data.dueDate, status: 'Dipinjam', fine: 0 };
                                }
                                return tx;
                            });
                        }
                    });
                },

                handleReturnBook(txId) {
                    fetch(`/admin/loans/${txId}/return`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.transactions = this.transactions.map(tx => {
                                if (tx.id === txId) {
                                    this.books = this.books.map(b => b.id === tx.bookId ? { ...b, available: b.available + 1 } : b);
                                    this.members = this.members.map(m => m.id === tx.memberId ? { ...m, borrowedCount: Math.max(0, m.borrowedCount - 1) } : m);
                                    this.triggerToast(`Buku "${tx.bookTitle}" berhasil dikembalikan`);
                                    return { ...tx, returnDate: data.returnDate, status: 'Kembali' };
                                }
                                return tx;
                            });
                        }
                    });
                },

                handleApproveBook(txId) {
                    fetch(`/admin/loans/${txId}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.transactions = this.transactions.map(tx => {
                                if (tx.id === txId) {
                                    this.books = this.books.map(b => b.id === tx.bookId ? { ...b, available: b.available - 1 } : b);
                                    this.members = this.members.map(m => m.id === tx.memberId ? { ...m, borrowedCount: m.borrowedCount + 1 } : m);
                                    this.triggerToast(`Peminjaman buku "${tx.bookTitle}" disetujui`);
                                    return { ...tx, borrowDate: data.borrowDate, dueDate: data.dueDate, status: 'Dipinjam' };
                                }
                                return tx;
                            });
                            this.$nextTick(() => this.initCharts());
                        } else {
                            alert(data.message || 'Gagal menyetujui peminjaman.');
                        }
                    });
                },

                openFineModal(tx) {
                    this.selectedFineTx = tx;
                    this.isFineModalOpen = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                handleApproveFinePayment(txId) {
                    fetch(`/admin/loans/${txId}/pay`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.transactions = this.transactions.map(tx => {
                                if (tx.id === txId) {
                                    this.triggerToast(`Denda Rp ${tx.fine.toLocaleString('id-ID')} dikonfirmasi lunas`);
                                    return { ...tx, finePaid: true, paymentStatus: 'approved' };
                                }
                                return tx;
                            });
                            if (this.selectedFineTx && this.selectedFineTx.id === txId) {
                                this.selectedFineTx.finePaid = true;
                                this.selectedFineTx.paymentStatus = 'approved';
                            }
                            this.isFineModalOpen = false;
                        }
                    });
                },

                handleRejectFinePayment(txId) {
                    fetch(`/admin/loans/${txId}/reject-fine`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.transactions = this.transactions.map(tx => {
                                if (tx.id === txId) {
                                    this.triggerToast(`Bukti pembayaran untuk denda ini ditolak`, 'error');
                                    return { ...tx, finePaid: false, paymentStatus: 'rejected' };
                                }
                                return tx;
                            });
                            if (this.selectedFineTx && this.selectedFineTx.id === txId) {
                                this.selectedFineTx.finePaid = false;
                                this.selectedFineTx.paymentStatus = 'rejected';
                            }
                            this.isFineModalOpen = false;
                        }
                    });
                },

                handlePayFine(txId) {
                    fetch(`/admin/loans/${txId}/pay`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.transactions = this.transactions.map(tx => {
                                if (tx.id === txId) {
                                    this.triggerToast(`Denda keterlambatan Rp ${tx.fine.toLocaleString('id-ID')} lunas dibayar`);
                                    return { ...tx, finePaid: true };
                                }
                                return tx;
                            });
                        }
                    });
                },

                // Categories actions
                handleAddCategory() {
                    const name = this.categoryInput.trim();
                    if (!name) return;

                    fetch('/admin/categories', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({ name: name })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.categories.push(name);
                            this.categoryInput = '';
                            this.triggerToast(`Kategori "${name}" berhasil ditambahkan`);
                            this.$nextTick(() => lucide.createIcons());
                        } else {
                            alert('Gagal menambahkan kategori.');
                        }
                    });
                },

                handleDeleteCategory(catName) {
                    if (confirm(`Apakah Anda yakin ingin menghapus kategori "${catName}"?`)) {
                        fetch('/admin/categories', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify({ name: catName })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.categories = this.categories.filter(c => c !== catName);
                                this.triggerToast(`Kategori "${catName}" berhasil dihapus`, 'error');
                                this.$nextTick(() => lucide.createIcons());
                            }
                        });
                    }
                },

                simulateExport(fileName) {
                    this.triggerToast(`Unduhan berkas "${fileName}.xlsx" berhasil diproses`, 'success');
                },

                openPdfReader(book) {
                    this.activeReaderBook = book;
                    this.readerPage = 1;
                    this.isReaderLoggedIn = true;
                    this.readerLoginEmail = '';
                    this.readerLoginPassword = '';
                    this.$nextTick(() => lucide.createIcons());
                },

                downloadOriginalPdf() {
                    if (this.activeReaderBook && this.activeReaderBook.pdf_url) {
                        const link = document.createElement('a');
                        link.href = this.activeReaderBook.pdf_url;
                        link.download = this.activeReaderBook.title + '.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else {
                        this.triggerToast(`Mengunduh berkas asli "${this.activeReaderBook.title}"...`, 'success');
                    }
                },

                verifyReaderLogin() {
                    if (this.readerLoginEmail && this.readerLoginPassword) {
                        this.isReaderLoggedIn = true;
                        this.triggerToast('Verifikasi akun berhasil!', 'success');
                    } else {
                        alert('Lengkapi alamat email dan password verifikasi.');
                    }
                },

                initCharts() {
                    const textCol = this.isDarkMode ? '#94a3b8' : '#64748b';
                    const gridCol = this.isDarkMode ? '#1e293b' : '#e2e8f0';

                    const circCanvas = document.getElementById('circulationChart');
                    if (circCanvas) {
                        if (this.circulationChartInstance) {
                            this.circulationChartInstance.destroy();
                        }
                        
                        // Count dynamic borrow and return dates per month (Jan - Jul)
                        const chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'];
                        const pinjamData = [0, 0, 0, 0, 0, 0, 0];
                        const kembaliData = [0, 0, 0, 0, 0, 0, 0];
                        const ebookCount = this.books.filter(b => b.is_ebook).length;
                        const ebookData = [0, 0, 0, 0, 0, 0, 0].map((_, i) => Math.max(5, ebookCount * (i + 1) + 2));

                        this.transactions.forEach(tx => {
                            if (tx.borrowDate) {
                                const date = new Date(tx.borrowDate);
                                const month = date.getMonth(); // 0-11
                                if (month >= 0 && month < 7) {
                                    pinjamData[month]++;
                                }
                            }
                            if (tx.returnDate) {
                                const date = new Date(tx.returnDate);
                                const month = date.getMonth(); // 0-11
                                if (month >= 0 && month < 7) {
                                    kembaliData[month]++;
                                }
                            }
                        });

                        this.circulationChartInstance = new Chart(circCanvas, {
                            type: 'line',
                            data: {
                                labels: chartLabels,
                                datasets: [
                                    {
                                        label: 'Pinjam',
                                        data: pinjamData,
                                        borderColor: '#6366f1',
                                        backgroundColor: 'rgba(99, 102, 241, 0.05)',
                                        borderWidth: 2.5,
                                        tension: 0.3,
                                        fill: true
                                    },
                                    {
                                        label: 'Kembali',
                                        data: kembaliData,
                                        borderColor: '#10b981',
                                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                                        borderWidth: 2.5,
                                        tension: 0.3,
                                        fill: true
                                    },
                                    {
                                        label: 'E-Book',
                                        data: ebookData,
                                        borderColor: '#0ea5e9',
                                        backgroundColor: 'rgba(14, 165, 233, 0.05)',
                                        borderWidth: 2.5,
                                        tension: 0.3,
                                        fill: true
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: { color: textCol, font: { family: 'Inter', size: 10 } }
                                    },
                                    y: {
                                        grid: { color: gridCol },
                                        ticks: { color: textCol, font: { family: 'Inter', size: 10 } }
                                    }
                                }
                            }
                        });
                    }

                    const catCanvas = document.getElementById('categoriesChart');
                    if (catCanvas) {
                        if (this.categoriesChartInstance) {
                            this.categoriesChartInstance.destroy();
                        }

                        const counts = this.categories.map(cat => {
                            return this.books.filter(b => b.category === cat).reduce((sum, b) => sum + b.stock, 0);
                        });

                        this.categoriesChartInstance = new Chart(catCanvas, {
                            type: 'bar',
                            data: {
                                labels: this.categories,
                                datasets: [{
                                    data: counts,
                                    backgroundColor: this.categories.map((_, i) => i % 2 === 0 ? '#6366f1' : '#0ea5e9'),
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    x: {
                                        grid: { color: gridCol },
                                        ticks: { color: textCol, font: { family: 'Inter', size: 10 } }
                                    },
                                    y: {
                                        grid: { display: false },
                                        ticks: { color: textCol, font: { family: 'Inter', size: 10 } }
                                    }
                                }
                            }
                        });
                    }
                }
            };
        }
    </script>
    <script>
        // Save current admin user to login history
        const adminUser = {
            name: '{{ Auth::user()->name ?? "Admin" }}',
            email: '{{ Auth::user()->email ?? "admin@literasiku.com" }}',
            avatar: 'https://ui-avatars.com/api/?name=' + encodeURIComponent('{{ Auth::user()->name ?? "Admin" }}') + '&background=4f46e5&color=fff',
            lastLogin: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
        };
        let historyList = JSON.parse(localStorage.getItem("loginHistory") || "[]");
        historyList = historyList.filter(item => item.email !== adminUser.email);
        historyList.unshift(adminUser);
        if (historyList.length > 3) historyList.pop();
        localStorage.setItem("loginHistory", JSON.stringify(historyList));
    </script>
</body>
</html>
