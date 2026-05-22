<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Digital Library</title>
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
            padding: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 2.5rem;
            padding: 3.5rem 2.5rem 2.5rem 2.5rem;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            margin-bottom: 0.5rem;
        }

        .logo-text {
            font-size: 2.2rem;
            font-weight: 800;
            color: #2d6a4f;
            margin: 0;
            letter-spacing: -1px;
            animation: fadeInUp 0.8s ease-out both, glow 3s ease-in-out infinite alternate;
        }

        .welcome-text {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
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

        .subtitle-text {
            font-size: 0.85rem;
            color: #4b5563;
            margin-bottom: 2.5rem;
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            width: 18px;
            height: 18px;
        }

        .styled-input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 3rem;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 9999px;
            font-size: 0.95rem;
            color: #374151;
            outline: none;
            transition: all 0.2s ease;
        }

        .styled-input:focus {
            border-color: #2d6a4f;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1);
        }

        .forgot-link {
            display: block;
            text-align: right;
            font-size: 0.75rem;
            color: #2d6a4f;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .toggle-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1rem;
            margin-bottom: 2rem;
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 42px;
            height: 22px;
        }

        .switch input { opacity: 0; width: 0; height: 0; }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #d1d5db;
            transition: .3s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px; width: 16px;
            left: 3px; bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked + .slider { background-color: #2d6a4f; }
        input:checked + .slider:before { transform: translateX(20px); }

        .remember-text {
            font-size: 0.8rem;
            color: #4b5563;
            font-weight: 500;
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
        }

        .btn-submit:hover {
            background: #1b4332;
            transform: translateY(-1px);
            box-shadow: 0 12px 20px -3px rgba(45, 106, 79, 0.4);
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
            margin-top: 3.5rem;
            font-size: 0.7rem;
            color: #9ca3af;
            font-weight: 500;
        }

        .eye-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
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

        <h2 class="welcome-text">Selamat Datang di Digital Library</h2>
        <p class="subtitle-text">Platform Digital untuk Pustakawan Modern.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- Email Input -->
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                       class="styled-input" placeholder="Email atau Username">
            </div>
            @error('email') <p class="text-xs text-red-500 text-left px-4 mb-2">{{ $message }}</p> @enderror

            <!-- Forgot Password Link -->
            <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>

            <!-- Password Input -->
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <input type="password" id="password" name="password" required 
                       class="styled-input" placeholder="Password">
                <button type="button" onclick="togglePass()" class="eye-toggle">
                    <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <button type="submit" class="btn-submit">
                Masuk
            </button>

            <div class="footer-link">
                Pustakawan Baru? <a href="{{ route('register') }}">[Daftar di Sini]</a>
            </div>
        </form>

        <div class="copyright">
            © 2026 Sistem Informasi Perpustakaan.
        </div>
    </div>

    <script>
        function togglePass() {
            const x = document.getElementById("password");
            const icon = document.getElementById("eye-icon");
            if (x.type === "password") {
                x.type = "text";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>';
            } else {
                x.type = "password";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</body>
</html>
