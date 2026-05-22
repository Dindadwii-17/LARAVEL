<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Book - Sistem Informasi Perpustakaan</title>
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

        .ebook-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .ebook-card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            color: #333;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .ebook-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .ebook-header {
            height: 160px;
            background: linear-gradient(135deg, #0066cc, #004a99);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            position: relative;
        }

        .ebook-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #fbbf24;
            color: #000;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 800;
        }

        .ebook-body {
            padding: 20px;
            flex-grow: 1;
        }

        .ebook-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #1a1a1a;
        }

        .ebook-author {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
        }

        .ebook-desc {
            font-size: 13px;
            line-height: 1.5;
            color: #555;
            margin-bottom: 20px;
        }

        .ebook-footer {
            padding: 0 20px 20px;
        }

        .btn-read {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            background: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-read:hover {
            background: #0052a3;
        }

        .badge-notif {
            display: inline-flex; align-items: center; justify-content: center;
            background: #ff4757; color: white; font-size: 10px; font-weight: 800;
            width: 18px; height: 18px; border-radius: 50%; margin-left: 6px;
        }

        .no-data {
            grid-column: 1/-1; text-align: center; padding: 60px; background: #ffffff; border-radius: 20px;
            border: 2px dashed rgba(0,0,0,0.1); box-shadow: 0 4px 6px rgba(0,0,0,0.05);
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

        <div style="text-align: center; margin-bottom: 40px; background: #ffffff; padding: 30px; border-radius: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);">
            <h2 style="font-size: 32px; color: #0066cc; margin-bottom: 10px;">📚 Koleksi E-Book</h2>
            <p style="opacity: 0.8; font-size: 16px; color: #333;">Akses ribuan buku digital kapan saja dan di mana saja.</p>
        </div>

        <div class="ebook-grid">
            @forelse ($ebooks as $ebook)
                <div class="ebook-card">
                    <div class="ebook-header">
                        <i class="fas fa-file-pdf"></i>
                        <div class="ebook-badge">{{ $ebook->category->name ?? 'Uncategorized' }}</div>
                    </div>
                    <div class="ebook-body">
                        <div class="ebook-title">{{ $ebook->title }}</div>
                        <div class="ebook-author">Oleh: {{ $ebook->author }}</div>
                        <div class="ebook-desc">{{ $ebook->description }}</div>
                    </div>
                    <div class="ebook-footer">
                        <a href="{{ route('ebooks.read', $ebook->slug) }}" class="btn-read">
                            <i class="fas fa-book-open"></i> Baca Sekarang
                        </a>
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <div style="font-size: 64px; margin-bottom: 20px;">📂</div>
                    <h3 style="color: #000;">Belum ada E-Book tersedia</h3>
                    <p style="font-size: 14px; margin-top: 10px; color: #666;">Kami sedang menyiapkan koleksi buku digital terbaik untuk Anda. Silakan kembali lagi nanti!</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
