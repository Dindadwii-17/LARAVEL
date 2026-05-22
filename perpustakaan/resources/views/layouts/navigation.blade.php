<nav class="flex flex-col h-full">
    <!-- Logo Section -->
    <div class="flex items-center gap-4 px-2 mb-10">
        <div class="w-[45px] h-[45px] flex-shrink-0">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                <!-- Left Page (Lighter Grey for contrast) -->
                <path d="M20 25C20 22.2386 22.2386 20 25 20H48V80H25C22.2386 80 20 77.7614 20 75V25Z" fill="#F1F5F9"/>
                <!-- Right Page (Vibrant Green for contrast) -->
                <path d="M52 20H75C77.7614 20 80 22.2386 80 25V75C80 77.7614 77.7614 80 75 80H52V20Z" fill="#4ADE80"/>
                <!-- Left Page Lines (Dark Green) -->
                <rect x="28" y="35" width="12" height="2" rx="1" fill="#1b4332"/>
                <rect x="28" y="45" width="12" height="2" rx="1" fill="#1b4332"/>
                <rect x="28" y="55" width="12" height="2" rx="1" fill="#1b4332"/>
                <!-- Right Page Lines & Dots (White) -->
                <rect x="60" y="35" width="8" height="2" rx="1" fill="white"/>
                <circle cx="72" cy="36" r="2" fill="white"/>
                <rect x="60" y="45" width="10" height="2" rx="1" fill="white"/>
                <rect x="60" y="55" width="8" height="2" rx="1" fill="white"/>
                <circle cx="72" cy="56" r="2" fill="white"/>
                <!-- Center Separator -->
                <path d="M48 20V80" stroke="#F1F5F9" stroke-width="1.5" stroke-opacity="0.5"/>
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="text-xl font-black text-white leading-tight uppercase tracking-widest">Pustaka</span>
            <span class="text-xl font-black text-white leading-tight uppercase tracking-widest">Digital</span>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Manajemen Buku (formerly Katalog) -->
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.books.index') : route('member.books.index') }}" class="sidebar-link {{ request()->routeIs('*books.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1" stroke-linecap="round" stroke-linejoin="round"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1" stroke-linecap="round" stroke-linejoin="round"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1" stroke-linecap="round" stroke-linejoin="round"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1" stroke-linecap="round" stroke-linejoin="round"></rect>
            </svg>
            <span>Manajemen Buku</span>
        </a>

        <!-- Denda (formerly Rak Saya) -->
        <a href="#" class="sidebar-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Denda</span>
        </a>

        <!-- Peminjaman -->
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.borrowings.index') : route('member.borrowings.index') }}" class="sidebar-link {{ request()->routeIs('*borrowings.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                <circle cx="12" cy="13" r="1" fill="currentColor"></circle>
            </svg>
            <span>Peminjaman</span>
        </a>

        <!-- Anggota (formerly Akun) -->
        <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Anggota</span>
        </a>

        <!-- Laporan (New) -->
        <a href="#" class="sidebar-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Laporan</span>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link w-full text-left">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <!-- Bottom Profile Section -->
    <div class="mt-auto">
        <div class="nav-separator"></div>
        <div class="flex items-center justify-between px-2">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#0F1A13] flex items-center justify-center text-white text-[10px] font-bold border-2 border-white/20">
                    {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('') }}
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-white">{{ explode(' ', auth()->user()->name)[0] }}</span>
                </div>
            </div>
            <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>
</nav>