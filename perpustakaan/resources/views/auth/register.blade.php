<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Digital Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-image: url('{{ asset('bg.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 40px 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 2.5rem;
            padding: 3rem 2.5rem 2.5rem 2.5rem;
            width: 100%;
            max-width: 650px; /* Lebar lebih besar untuk form pendaftaran */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 0.5rem;
        }

        .logo-text {
            font-size: 2rem;
            font-weight: 800;
            color: #2d6a4f;
            margin: 0;
            letter-spacing: -1px;
            animation: fadeInUp 0.8s ease-out both, glow 3s ease-in-out infinite alternate;
        }

        .welcome-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 5px rgba(45, 106, 79, 0.1);
            }
            to {
                text-shadow: 0 0 15px rgba(45, 106, 79, 0.3);
            }
        }

        .input-group-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            text-align: left;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 1rem;
            text-align: left;
        }

        .input-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #4b5563;
            margin-bottom: 0.4rem;
            margin-left: 0.5rem;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 38px; /* Disesuaikan karena ada label */
            color: #9ca3af;
            width: 16px;
            height: 16px;
        }

        .styled-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 9999px;
            font-size: 0.9rem;
            color: #374151;
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .styled-textarea {
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 1.5rem;
            font-size: 0.9rem;
            color: #374151;
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
            resize: none;
        }

        .styled-input:focus, .styled-textarea:focus {
            border-color: #2d6a4f;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1);
        }

        .btn-submit {
            width: 100%;
            background: #2d6a4f;
            color: white;
            padding: 0.9rem;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(45, 106, 79, 0.3);
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: #1b4332;
            transform: translateY(-1px);
        }

        .footer-link {
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .footer-link a {
            color: #2d6a4f;
            font-weight: 700;
            text-decoration: none;
        }

        .copyright {
            margin-top: 2.5rem;
            font-size: 0.7rem;
            color: #9ca3af;
            font-weight: 500;
        }

        .error-msg {
            color: #ef4444;
            font-size: 0.7rem;
            margin-top: 0.25rem;
            margin-left: 1rem;
        }

        @media (max-width: 640px) {
            .input-group-grid { grid-template-columns: 1fr; gap: 0; }
            .glass-card { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="glass-card">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-icon">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 25C20 22.2386 22.2386 20 25 20H48V80H25C22.2386 80 20 77.7614 20 75V25Z" fill="#2d6a4f" fill-opacity="0.2"/>
                    <path d="M52 20H75C77.7614 20 80 22.2386 80 25V75C80 77.7614 77.7614 80 75 80H52V20Z" fill="#2d6a4f"/>
                    <rect x="25" y="30" width="15" height="2" rx="1" fill="#2d6a4f"/>
                    <rect x="25" y="40" width="15" height="2" rx="1" fill="#2d6a4f"/>
                    <rect x="25" y="50" width="15" height="2" rx="1" fill="#2d6a4f"/>
                    <path d="M58 35L65 35" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <path d="M58 45L72 45" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <path d="M58 55L68 55" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="72" cy="35" r="2" fill="white"/>
                    <circle cx="72" cy="55" r="2" fill="white"/>
                    <path d="M48 20V80" stroke="#2d6a4f" stroke-width="2"/>
                </svg>
            </div>
            <h1 class="logo-text">Digital Library</h1>
        </div>

        <h2 class="welcome-text">Daftar Akun Baru</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="input-group-grid">
                <!-- Nama Lengkap -->
                <div class="input-wrapper">
                    <label class="input-label">Nama Lengkap</label>
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="styled-input" placeholder="Nama Lengkap">
                    @error('name') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- NIM -->
                <div class="input-wrapper">
                    <label class="input-label">NIM</label>
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <input type="text" name="nim" value="{{ old('nim') }}" required class="styled-input" placeholder="Nomor Induk Mahasiswa">
                    @error('nim') <p class="error-msg">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="input-group-grid">
                <!-- Email -->
                <div class="input-wrapper">
                    <label class="input-label">Email</label>
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <input type="email" name="email" value="{{ old('email') }}" required class="styled-input" placeholder="nama@email.com">
                    @error('email') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- No Telp -->
                <div class="input-wrapper">
                    <label class="input-label">No. Telepon</label>
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="styled-input" placeholder="08xxxxxxxxx">
                    @error('phone') <p class="error-msg">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div class="input-wrapper">
                <label class="input-label">Alamat Lengkap</label>
                <textarea name="address" rows="2" required class="styled-textarea" placeholder="Masukkan alamat lengkap Anda...">{{ old('address') }}</textarea>
                @error('address') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="input-group-grid">
                <!-- Password -->
                <div class="input-wrapper">
                    <label class="input-label">Password</label>
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <input type="password" name="password" required class="styled-input" placeholder="••••••••">
                    @error('password') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="input-wrapper">
                    <label class="input-label">Ulangi Password</label>
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <input type="password" name="password_confirmation" required class="styled-input" placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Daftar Sekarang
            </button>

            <div class="footer-link">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di Sini</a>
            </div>
        </form>

        <div class="copyright">
            © 2026 Sistem Informasi Perpustakaan.
        </div>
    </div>
</body>
</html>
