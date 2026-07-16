<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota - LiterasiKu</title>
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
            <div class="bg-[#2563EB] dark:bg-[#1E3A8A] relative lg:-mr-px -mb-px lg:mb-0 rounded-t-2xl lg:rounded-t-none lg:rounded-l-2xl w-full lg:w-[280px] shrink-0 overflow-hidden flex flex-col justify-between items-center p-6 text-center select-none text-white py-8">
                
                <!-- Back Button to Preview Mode -->
                <a href="/" class="absolute top-4 left-4 flex items-center text-[11px] font-bold text-blue-200 hover:text-white transition-colors z-20" title="Kembali ke Mode Tamu">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                </a>

                <!-- Top Spacer -->
                <div></div>
                
                <!-- Center Content: Logo, Title & Description -->
                <div class="flex flex-col items-center">
                    <!-- Main Graphic icon (Logo) -->
                    <div class="relative z-10 w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-4 border border-white/20 shadow-lg overflow-hidden">
                        <img src="/logo-literasiku.jpg" alt="Logo LiterasiKu" class="w-full h-full object-cover">
                    </div>

                    <div class="relative z-10 max-w-[220px]">
                        <h2 class="text-2xl font-black tracking-tight text-white mb-1.5">LiterasiKu</h2>
                        <p class="text-[11px] text-blue-100 leading-relaxed">Akses ribuan koleksi buku, catat perjalanan membaca, dan kelola ruang literasi pribadimu dalam satu platform digital yang hangat dan terintegrasi.</p>
                    </div>
                </div>

                <!-- Spacer -->
                <div></div>
            </div>

            <!-- Right Panel: Registration Form -->
            <div class="text-[13px] leading-[18px] flex-1 p-5 lg:p-8 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-b-2xl lg:rounded-r-2xl lg:rounded-l-none">
                
                <!-- Title & Subtitle -->
                <div class="mb-4">
                    <h1 class="text-xl font-extrabold text-[#1b1b18] dark:text-white mb-0.5">Pendaftaran Anggota</h1>
                    <p class="text-[11px] text-[#706f6c] dark:text-[#A1A09A]">Isi data diri singkat untuk membuat akun perpustakaan baru.</p>
                </div>

                <!-- Alert Notifications -->
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-2.5 mb-3 text-[11px] font-semibold flex items-center space-x-1.5 dark:bg-rose-950/30 dark:border-rose-900 dark:text-rose-300">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500 text-xs"></i>
                        <span>Pendaftaran gagal. Silakan periksa kembali data Anda.</span>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('register.process') }}" class="space-y-3">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        
                        <!-- Nama Lengkap -->
                        <div class="space-y-1">
                            <label for="name" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                    <i class="fa-solid fa-user-tag text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    placeholder="Budi Santoso" 
                                    value="{{ old('name') }}" 
                                    required
                                    class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                                >
                            </div>
                            @error('name') <span class="text-[10px] text-rose-600 dark:text-rose-455 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- NIM -->
                        <div class="space-y-1">
                            <label for="nim" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">NIM</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                    <i class="fa-solid fa-id-card text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    id="nim" 
                                    name="nim" 
                                    placeholder="220101001" 
                                    value="{{ old('nim') }}" 
                                    required
                                    class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                                >
                            </div>
                            @error('nim') <span class="text-[10px] text-rose-600 dark:text-rose-455 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Username -->
                        <div class="space-y-1">
                            <label for="username" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Username</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    id="username" 
                                    name="username" 
                                    placeholder="budis123" 
                                    value="{{ old('username') }}" 
                                    required
                                    class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                                >
                            </div>
                            @error('username') <span class="text-[10px] text-rose-600 dark:text-rose-455 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-1">
                            <label for="email" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                    <i class="fa-solid fa-envelope text-xs"></i>
                                </span>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    placeholder="budi@example.com" 
                                    value="{{ old('email') }}" 
                                    required
                                    class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                                >
                            </div>
                            @error('email') <span class="text-[10px] text-rose-600 dark:text-rose-455 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-1">
                            <label for="phone" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Nomor Telepon</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                    <i class="fa-solid fa-phone text-xs"></i>
                                </span>
                                <input 
                                    type="text" 
                                    id="phone" 
                                    name="phone" 
                                    placeholder="0812xxxx" 
                                    value="{{ old('phone') }}"
                                    class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                                >
                            </div>
                            @error('phone') <span class="text-[10px] text-rose-600 dark:text-rose-455 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Gender -->
                        <div class="space-y-1">
                            <label for="gender" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Jenis Kelamin</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-blue-600/80 dark:text-blue-400/80 z-10">
                                    <i class="fa-solid fa-venus-mars text-xs"></i>
                                </span>
                                <select 
                                    id="gender" 
                                    name="gender"
                                    class="w-full pl-8 pr-8 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900 appearance-none cursor-pointer"
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 dark:text-slate-500">
                                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                </span>
                            </div>
                            @error('gender') <span class="text-[10px] text-rose-600 dark:text-rose-455 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="space-y-1 sm:col-span-2">
                            <label for="address" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Alamat Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                    <i class="fa-solid fa-map-marker-alt text-xs"></i>
                                </span>
                                <input 
                                    type="text"
                                    id="address" 
                                    name="address" 
                                    placeholder="Jl. Merdeka No. 123..."
                                    value="{{ old('address') }}"
                                    class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                                >
                            </div>
                            @error('address') <span class="text-[10px] text-rose-600 dark:text-rose-455 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-1">
                            <label for="password" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </span>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    placeholder="••••••••" 
                                    required
                                    class="w-full pl-8 pr-9 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                                >
                                <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-600 dark:text-slate-500 dark:hover:text-blue-400 transition-colors" data-target="password">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                            @error('password') <span class="text-[10px] text-rose-600 dark:text-rose-455 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">Konfirmasi Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-blue-600/80 dark:text-blue-400/80">
                                    <i class="fa-solid fa-key text-xs"></i>
                                </span>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    placeholder="••••••••" 
                                    required
                                    class="w-full pl-8 pr-9 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:bg-white transition-all dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"
                                >
                                <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-600 dark:text-slate-500 dark:hover:text-blue-400 transition-colors" data-target="password_confirmation">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="w-full py-2 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/10 transition-colors flex items-center justify-center space-x-1.5 mt-3 cursor-pointer">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        <span>Daftar Sekarang</span>
                    </button>
                </form>

                <!-- Footer Links -->
                <div class="mt-4 pt-3 border-t border-slate-150 dark:border-slate-800 text-center text-xs">
                    <span class="text-slate-550 dark:text-slate-400">Sudah menjadi anggota?</span>
                    <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline ml-1">Login di sini</a>
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
                    icon.className = 'fa-regular fa-eye-slash text-xs';
                    button.setAttribute('aria-label', 'Sembunyikan password');
                    button.setAttribute('title', 'Sembunyikan password');
                } else {
                    input.type = 'password';
                    icon.className = 'fa-regular fa-eye text-xs';
                    button.setAttribute('aria-label', 'Tampilkan password');
                    button.setAttribute('title', 'Tampilkan password');
                }
            });
        });
    </script>
</body>
</html>
