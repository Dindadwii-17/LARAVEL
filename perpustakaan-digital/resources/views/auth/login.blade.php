<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Perpustakaan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, rgba(0,0,0,0.2), rgba(0,0,0,0.4)), url('/background.jpg') center/cover no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: white;
            overflow-x: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease-out;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
            animation: float 4s ease-in-out infinite;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 300;
            letter-spacing: 2px;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header h2 {
            font-size: 48px;
            font-weight: 700;
            color: #FFD700;
            letter-spacing: 3px;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .login-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .form-group {
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .form-group:nth-child(2) { animation-delay: 0.4s; }
        .form-group:nth-child(3) { animation-delay: 0.5s; }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
            transform: translateY(-2px);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 0;
            height: 100%;
            width: 38px;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #0066cc;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: block;
            animation: fadeInDown 0.3s ease-out;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: fadeInDown 0.5s ease-out;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0066cc, #004a99);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            opacity: 0;
            animation: fadeInUp 0.5s ease-out 0.6s forwards;
            box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 102, 204, 0.4);
            filter: brightness(1.1);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 14px;
            opacity: 0;
            animation: fadeInUp 0.5s ease-out 0.8s forwards;
        }

        .register-link a {
            color: #0066cc;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .register-link a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #0066cc;
            transition: width 0.3s ease;
        }

        .register-link a:hover::after {
            width: 100%;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0;
            animation: fadeInUp 0.5s ease-out 1s forwards;
        }

        .footer p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 480px) {
            .header h1 { font-size: 18px; }
            .header h2 { font-size: 36px; }
            .login-card { padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="header">
            <h1>Sistem Informasi</h1>
            <h2>Perpustakaan</h2>
        </div>

        <div class="login-card">
            @if ($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}">
                @csrf
                
                <div class="form-group">
                    <label for="username">Username atau Email</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="masukkan username atau email" 
                        value="{{ old('username') }}"
                        required
                    >
                    @error('username')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group password-wrapper">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••" 
                        required
                    >
                    <button type="button" class="password-toggle" data-target="password" aria-label="Tampilkan password" title="Tampilkan password">
                        👁
                    </button>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="login-btn">Sign In</button>
            </form>

            <div class="register-link">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
        </div>

        <div class="footer">
            <p>Copyright © Sistem Perpustakaan - 2024</p>
            <p>All rights reserved</p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);

                if (!input) {
                    return;
                }

                if (input.type === 'password') {
                    input.type = 'text';
                    button.textContent = '🙈';
                    button.setAttribute('aria-label', 'Sembunyikan password');
                    button.setAttribute('title', 'Sembunyikan password');
                } else {
                    input.type = 'password';
                    button.textContent = '👁';
                    button.setAttribute('aria-label', 'Tampilkan password');
                    button.setAttribute('title', 'Tampilkan password');
                }
            });
        });
    </script>
</body>
</html>
