<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PerpusKu') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                background-color: #E8F0E9; /* Light Sage Green from reference */
                background-image: url('/bg.jpeg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                background-blend-mode: overlay;
                font-family: 'Inter', sans-serif;
            }
            .sidebar {
                background-color: #1b4332; /* Requested Dark Green */
                color: #ffffff;
                width: 280px;
                padding: 2rem 1.5rem;
                box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            }
            .sidebar-link {
                color: rgba(255, 255, 255, 0.7);
                transition: all 0.2s ease;
                border-radius: 12px;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 0.85rem 1.25rem;
                margin-bottom: 0.5rem;
                font-size: 1.1rem;
            }
            .sidebar-link:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: #ffffff;
            }
            .sidebar-link.active {
                background-color: #ffffff;
                color: #1b4332;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
            .sidebar-link svg {
                width: 24px;
                height: 24px;
            }
            .nav-separator {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                margin: 2rem 0;
            }
            .header-gradient {
                background: linear-gradient(135deg, #2D5A47 0%, #1A3A2F 100%);
            }
            .card-shadow {
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
            }
        </style>
    </head>
    <body class="antialiased text-slate-700">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside class="sidebar w-64 flex-shrink-0 flex flex-col z-20">
                @include('layouts.navigation')
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen">
                <!-- Blue Gradient Header -->
                <header class="header-gradient px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-black tracking-tight">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
                            <p class="text-emerald-100/80 text-sm mt-1 font-medium">Senang melihat Anda kembali. Mari kelola perpustakaan hari ini.</p>
                        </div>

                        <div class="flex items-center gap-8">
                            <!-- Search Bar -->
                            <div class="relative w-72 hidden md:block">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-white/50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" placeholder="Cari buku atau anggota..." class="w-full bg-white/10 border border-white/20 py-2.5 pl-11 pr-4 rounded-2xl text-sm placeholder:text-white/40 outline-none focus:bg-white/20 focus:border-white/40 transition-all backdrop-blur-md">
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-8 -mt-6">
                    <div class="bg-transparent">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
