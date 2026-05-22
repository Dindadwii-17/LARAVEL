<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sistem Informasi Perpustakaan</title>
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
            padding: 40px 20px;
            color: white;
            overflow-x: hidden;
        }

        .register-container {
            width: 100%;
            max-width: 550px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease-out;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
            animation: float 4s ease-in-out infinite;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 300;
            letter-spacing: 2px;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header h2 {
            font-size: 40px;
            font-weight: 700;
            color: #FFD700;
            letter-spacing: 3px;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .register-card {
            background: white;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 1s ease-out 0.2s both;
            color: #333;
        }

        .form-title {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-size: 22px;
            font-weight: 700;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 13px;
            font-weight: 600;
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
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
        }

        .error-message {
            color: #dc3545;
            font-size: 11px;
            margin-top: 4px;
            display: block;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .register-btn {
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
            box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 102, 204, 0.4);
            filter: brightness(1.1);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #0066cc;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
        }

        @media (max-width: 580px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
            .register-card {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="header">
            <h1>Sistem Informasi</h1>
            <h2>Perpustakaan</h2>
        </div>

        <div class="register-card">
            <h3 class="form-title">Pendaftaran Anggota</h3>

            @if ($errors->any())
                <div class="alert alert-error">
                    Pendaftaran gagal. Silakan periksa kembali data Anda.
                </div>
            @endif

            <form method="POST" action="{{ route('register.process') }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                        @error('name') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="nim">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" id="nim" name="nim" placeholder="Contoh: 220101001" value="{{ old('nim') }}" required>
                        @error('nim') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="budis123" value="{{ old('username') }}" required>
                        @error('username') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="budi@example.com" value="{{ old('email') }}" required>
                        @error('email') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" placeholder="0812xxxx" value="{{ old('phone') }}">
                        @error('phone') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select id="gender" name="gender">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Alamat Lengkap</label>
                        <textarea id="address" name="address" placeholder="Jl. Merdeka No. 123...">{{ old('address') }}</textarea>
                        @error('address') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <button type="button" class="password-toggle" data-target="password">👁</button>
                        </div>
                        @error('password') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                            <button type="button" class="password-toggle" data-target="password_confirmation">👁</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="register-btn">Daftar Sekarang</button>
            </form>

            <div class="login-link">
                Sudah menjadi anggota? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>

        <div class="footer">
            <p>Copyright © Sistem Perpustakaan - 2024</p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input.type === 'password') {
                    input.type = 'text';
                    button.textContent = '🙈';
                } else {
                    input.type = 'password';
                    button.textContent = '👁';
                }
            });
        });
    </script>
</body>
</html>
