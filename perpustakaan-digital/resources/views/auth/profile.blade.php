<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Perpustakaan</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('/bg.jpeg') center/cover no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            color: #000000;
            overflow-x: hidden;
        }

        .page {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px 50px;
            animation: fadeIn 0.8s ease-out;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand-group {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .navbar h1 { 
            font-size: 22px; 
            font-weight: 700; 
            white-space: nowrap;
            color: #000000;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .nav-links a {
            color: rgba(0, 0, 0, 0.7);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: 0.3s;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .nav-links a:hover, .nav-links a.active {
            color: #000000;
            background: rgba(0, 0, 0, 0.05);
        }

        .user-section { display: flex; align-items: center; gap: 15px; }
        .user-name { font-weight: 600; font-size: 15px; color: #000000; }
        .logout-btn {
            background: #fee2e2;
            color: #dc3545;
            border: 1px solid #fecaca;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 600;
        }
        .logout-btn:hover { background: #fecaca; }

        .profile-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
        }

        .profile-sidebar {
            text-align: center;
            border-right: 1px solid #f1f5f9;
            padding-right: 40px;
        }

        .avatar-circle {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #0066cc, #004a99);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            color: white;
            box-shadow: 0 10px 20px rgba(0, 102, 204, 0.2);
        }

        .profile-sidebar h2 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #0f172a;
        }

        .profile-sidebar p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .badge-status {
            display: inline-block;
            padding: 6px 16px;
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
        }

        .profile-main h3 {
            font-size: 20px;
            margin-bottom: 25px;
            color: #0066cc;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .info-item {
            margin-bottom: 20px;
        }

        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #0f172a;
        }

        .form-control {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 15px;
            color: #0f172a;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: #0066cc;
            box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
        }

        .address-box {
            grid-column: span 2;
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .btn-edit, .btn-save, .btn-cancel {
            margin-top: 30px;
            padding: 12px 25px;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid transparent;
        }

        .btn-edit {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
        }

        .btn-edit:hover {
            background: #e2e8f0;
        }

        .btn-save {
            background: #0066cc;
            color: #ffffff;
        }

        .btn-save:hover {
            background: #0052a3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.2);
        }

        .btn-cancel {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .btn-cancel:hover {
            background: #fecaca;
        }

        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @media (max-width: 850px) {
            .profile-card { grid-template-columns: 1fr; }
            .profile-sidebar { border-right: none; padding-right: 0; padding-bottom: 30px; border-bottom: 1px solid #f1f5f9; }
            .info-grid { grid-template-columns: 1fr; }
            .address-box { grid-column: span 1; }
        }

        .badge-notif {
            display: inline-flex; align-items: center; justify-content: center;
            background: #ff4757; color: white; font-size: 10px; font-weight: 800;
            width: 18px; height: 18px; border-radius: 50%; margin-left: 6px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="navbar">
            <div class="navbar-brand-group">
                <h1>Perpustakaan</h1>
                <ul class="nav-links">
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'active' : '' }}">Katalog</a></li>
                    <li><a href="{{ route('ebooks') }}" class="{{ request()->routeIs('ebooks') ? 'active' : '' }}">E-Book</a></li>
                    <li><a href="{{ route('loans') }}" class="{{ request()->routeIs('loans') ? 'active' : '' }}">History Peminjaman</a></li>
                    <li>
                        <a href="{{ route('denda') }}" class="{{ request()->routeIs('denda') ? 'active' : '' }}">
                            Denda
                            @if(isset($unpaidFinesCount) && $unpaidFinesCount > 0)
                                <span class="badge-notif">{{ $unpaidFinesCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li><a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">Profil</a></li>
                </ul>
            </div>
            <div class="user-section">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="list-style: none;">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-card">
            <div class="profile-sidebar">
                <div class="avatar-circle">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2>{{ $user->name }}</h2>
                <p>{{ '@' . $user->username }}</p>
                <div style="font-size: 14px; font-weight: 700; color: #0066cc; margin-bottom: 15px; letter-spacing: 1px;">
                    NIM: {{ $user->nim ?? '-' }}
                </div>
                <div class="badge-status">Anggota Aktif</div>
                
                <div style="margin-top: 40px; text-align: left;">
                    <div class="info-label">Terdaftar Sejak</div>
                    <div class="info-value">{{ $user->created_at->format('d M Y') }}</div>
                </div>
            </div>

            <div class="profile-main">
                <div id="view-mode">
                    <h3>👤 Informasi Pribadi</h3>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">NIM (Nomor Induk Mahasiswa)</div>
                            <div class="info-value">{{ $user->nim ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">{{ $user->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Username</div>
                            <div class="info-value">{{ $user->username }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nomor Telepon</div>
                            <div class="info-value">{{ $user->phone ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Jenis Kelamin</div>
                            <div class="info-value">{{ $user->gender ?? '-' }}</div>
                        </div>
                        
                        <div class="info-item address-box">
                            <div class="info-label">Alamat Lengkap</div>
                            <div class="info-value">{{ $user->address ?? 'Alamat belum diisi' }}</div>
                        </div>
                    </div>

                    <button class="btn-edit" onclick="toggleEdit()">
                        <i class="fas fa-edit"></i> Edit Profil
                    </button>
                </div>

                <div id="edit-mode" style="display: none;">
                    <h3>📝 Edit Informasi Pribadi</h3>
                    
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="info-grid">
                            <div class="info-item">
                                <label class="info-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                            </div>
                            <div class="info-item">
                                <label class="info-label">Jenis Kelamin</label>
                                <select name="gender" class="form-control">
                                    <option value="" disabled>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ $user->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ $user->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="info-item address-box">
                                <label class="info-label">Alamat Lengkap</label>
                                <textarea name="address" class="form-control" rows="3">{{ $user->address }}</textarea>
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <button type="button" class="btn-cancel" onclick="toggleEdit()">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleEdit() {
            const viewMode = document.getElementById('view-mode');
            const editMode = document.getElementById('edit-mode');
            
            if (viewMode.style.display === 'none') {
                viewMode.style.display = 'block';
                editMode.style.display = 'none';
            } else {
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
            }
        }
    </script>
</body>
</html>
