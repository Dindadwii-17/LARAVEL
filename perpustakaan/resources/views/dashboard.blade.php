<x-app-layout>
    <!-- Top Header Bar -->
    <div class="bg-white/50 backdrop-blur-md rounded-2xl p-4 mb-8 flex items-center justify-between shadow-sm border border-white/20">
        <!-- Search Bar -->
        <div class="relative w-96">
            <input type="text" placeholder="Search..." class="w-full bg-white/80 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm focus:ring-2 focus:ring-[bg1.jpeg]/20 placeholder:text-slate-400">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Icons Section -->
        <div class="flex items-center gap-6">
            <button class="text-slate-600 hover:text-[#1b4332] transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </button>
            <div class="w-10 h-10 rounded-full bg-[#0F1A13] border-2 border-white overflow-hidden shadow-sm">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0F1A13&color=fff" class="w-full h-full object-cover">
            </div>
        </div>
    </div>

    <!-- Statistik Pustaka Section -->
    <div class="mb-10">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Statistik Pustaka</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Buku Dipinjam Card -->
            <div style="background-color: #4ADE80;" class="rounded-3xl p-8 flex flex-col items-center text-center shadow-lg shadow-emerald-500/20 transition hover:scale-[1.02]">
                <h3 class="text-lg font-bold text-[#1b4332]/80 mb-4">Buku Dipinjam</h3>
                <div class="text-[#1b4332] mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="text-4xl font-black text-[#1b4332] mb-1">125</div>
                <p class="text-sm text-[#1b4332]/60 font-medium">Buku Dipinjam</p>
            </div>

            <!-- Koleksi Favorit Card -->
            <div style="background-color: #4ADE80;" class="rounded-3xl p-8 flex flex-col items-center text-center shadow-lg shadow-emerald-500/20 transition hover:scale-[1.02]">
                <h3 class="text-lg font-bold text-[#1b4332]/80 mb-4">Koleksi Favorit</h3>
                <div class="text-[#1b4332] mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-black text-[#1b4332] mb-1">34</div>
                <p class="text-sm text-[#1b4332]/60 font-medium">Koleksi Favorit</p>
            </div>

            <!-- Tenggat Waktu Dekat Card -->
            <div style="background-color: #4ADE80;" class="rounded-3xl p-8 flex flex-col items-center text-center shadow-lg shadow-emerald-500/20 transition hover:scale-[1.02]">
                <h3 class="text-lg font-bold text-[#1b4332]/80 mb-4">Tenggat Waktu Dekat</h3>
                <div class="text-[#1b4332] mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-black text-[#1b4332] mb-1">5</div>
                <p class="text-sm text-[#1b4332]/60 font-medium">Tenggat Dekat</p>
            </div>
        </div>
    </div>

</x-app-layout>