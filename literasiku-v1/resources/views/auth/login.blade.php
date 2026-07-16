<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LiterasiKu</title>
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
    </style>
</head>
<body class="bg-[#F1F5F9] text-[#1b1b18] flex flex-col items-center justify-center min-h-screen p-4 py-8 space-y-6">

    <!-- 2 Panel Container -->
    <div class="flex items-center justify-center w-full lg:grow">
        <main class="flex w-full max-w-md lg:max-w-4xl flex-col lg:flex-row shadow-2xl rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#161615]">
            
            <!-- Left Panel: Visual Showcase -->
            <div class="bg-[#2563EB] dark:bg-[#1E3A8A] relative lg:-mr-px -mb-px lg:mb-0 rounded-t-2xl lg:rounded-t-none lg:rounded-l-2xl aspect-[335/250] lg:aspect-auto w-full lg:w-[400px] shrink-0 overflow-hidden flex flex-col justify-between items-center p-8 text-center select-none text-white py-8">
                
                <!-- Back Button to Preview Mode -->
                <a href="/" class="absolute top-6 left-6 flex items-center text-xs font-bold text-blue-200 hover:text-white transition-colors z-20" title="Kembali ke Mode Tamu">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
                </a>
                
                <!-- Top Spacer -->
                <div></div>
                
                <!-- Center Content: Logo, Title & Description -->
                <div class="flex flex-col items-center">
                    <!-- Main Graphic icon (Logo) -->
                    <div class="relative z-10 w-20 h-20 lg:w-24 lg:w-24 bg-white rounded-3xl flex items-center justify-center mb-5 border border-white/20 shadow-xl overflow-hidden">
                        <img src="/logo-literasiku.jpg" alt="Logo LiterasiKu" class="w-full h-full object-cover">
                    </div>

                    <div class="relative z-10 max-w-[280px]">
                        <h2 class="text-xl lg:text-2xl font-black tracking-tight text-white mb-2">LiterasiKu</h2>
                    </div>
                </div>

                <!-- Spacer -->
                <div></div>
            </div>

            <!-- Right Panel: Login Form -->
            <div class="text-[13px] leading-[20px] flex-1 p-6 sm:p-8 lg:p-16 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-b-2xl lg:rounded-r-2xl lg:rounded-l-none">
                
                <!-- Logo & Subtitle -->
                <div class="mb-8">
                    <h1 class="text-2xl font-extrabold text-[#1b1b18] dark:text-white mb-1">Selamat Datang Kembali</h1>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Silakan masuk untuk mengakses sirkulasi buku fisik dan digital Anda.</p>
                </div>

                <!-- Alert Notifications -->
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-3.5 mb-4 text-xs font-semibold flex items-center space-x-2 dark:bg-rose-950/30 dark:border-rose-900 dark:text-rose-300">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-3.5 mb-4 text-xs font-semibold flex items-center space-x-2 dark:bg-emerald-950/30 dark:border-emerald-900 dark:text-emerald-300">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif



                <!-- Login Form -->
                <form method="POST" action="{{ route('login.process') }}" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-1.5">
                        <label for="username" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Username atau Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                placeholder="masukkan username atau email" 
                                value="{{ old('username') }}"
                                required
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                            >
                        </div>
                        @error('username')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="••••••" 
                                required
                                class="w-full pl-10 pr-11 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                            >
                            <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-600 dark:text-slate-500 dark:hover:text-blue-400 transition-colors" data-target="password" aria-label="Tampilkan password" title="Tampilkan password">
                                <i class="fa-regular fa-eye text-base"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/10 transition-colors flex items-center justify-center space-x-2 mt-4 cursor-pointer">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Sign In</span>
                    </button>
                </form>

                <!-- Footer Links -->
                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 text-center">
                    <span class="text-slate-550 dark:text-slate-400">Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline ml-1">Daftar di sini</a>
                </div>
            </div>

        </main>
    </div>

    <!-- Bottom Copyright Info -->
    <footer class="text-xs text-slate-400 font-medium tracking-wide text-center">
        © 2026 LiterasiKu. Hak Cipta Dilindungi.
    </footer>

    <script>
        // Force light mode only
        document.documentElement.classList.remove('dark');

        // Toggle Password Visibility
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = button.querySelector('i');

                if (!input || !icon) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'fa-regular fa-eye-slash text-base';
                    button.setAttribute('aria-label', 'Sembunyikan password');
                    button.setAttribute('title', 'Sembunyikan password');
                } else {
                    input.type = 'password';
                    icon.className = 'fa-regular fa-eye text-base';
                    button.setAttribute('aria-label', 'Tampilkan password');
                    button.setAttribute('title', 'Tampilkan password');
                }
            });
        });

        // Load Login History
        document.addEventListener("DOMContentLoaded", () => {
            const historyList = JSON.parse(localStorage.getItem("loginHistory") || "[]");
            const section = document.getElementById("login-history-section");
            const container = document.getElementById("login-history-list");

            if (historyList.length > 0 && section && container) {
                section.classList.remove("hidden");
                container.innerHTML = historyList.map(item => `
                    <div onclick="selectHistoryAccount('${item.email}')" class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 cursor-pointer transition-all hover:bg-slate-100 dark:hover:bg-slate-900/20">
                        <div class="flex items-center space-x-3 min-w-0">
                            <img src="${item.avatar}" alt="${item.name}" class="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-700">
                            <div class="min-w-0">
                                <span class="block text-xs font-bold text-slate-800 dark:text-slate-200 truncate">${item.name}</span>
                                <span class="block text-[10px] text-slate-400 truncate">${item.email}</span>
                            </div>
                        </div>
                        <span class="text-[9px] text-slate-400 font-semibold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-900">Terakhir: ${item.lastLogin}</span>
                    </div>
                `).join("");
            }
        });

        function selectHistoryAccount(email) {
            const usernameInput = document.getElementById("username");
            const passwordInput = document.getElementById("password");
            if (usernameInput) {
                usernameInput.value = email;
                if (passwordInput) passwordInput.focus();
            }
        }
    </script>
</body>
</html>
