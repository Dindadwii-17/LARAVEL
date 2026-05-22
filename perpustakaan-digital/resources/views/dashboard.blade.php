<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Informasi Perpustakaan</title>
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

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        .card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .welcome-card h2 { font-size: 24px; margin-bottom: 8px; color: #000000; }
        .welcome-card p { font-size: 14px; color: #333333; line-height: 1.6; }

        .stats-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .stat-box {
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
            color: #000000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .stat-box h3 { font-size: 36px; margin-bottom: 5px; color: #000000; }
        .stat-box p { font-size: 13px; color: rgba(0, 0, 0, 0.6); text-transform: uppercase; letter-spacing: 1px; }

        .section-title {
            font-size: 20px;
            margin: 40px 0 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #000000;
            background: #ffffff;
            padding: 5px 15px;
            border-radius: 10px;
            display: inline-flex;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: white;
            color: #333;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .book-card:hover { transform: translateY(-10px); }

        .book-cover {
            height: 250px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            position: relative;
        }

        .book-info { padding: 15px; }
        .book-title { font-weight: 700; font-size: 16px; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #000000; }
        .book-author { font-size: 13px; color: #666; }

        .no-data {
            text-align: center;
            padding: 40px;
            background: #ffffff;
            border-radius: 15px;
            grid-column: 1 / -1;
            border: 2px dashed rgba(0, 0, 0, 0.1);
            color: rgba(0, 0, 0, 0.6);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 900px) {
            .dashboard-grid { grid-template-columns: 1fr; }
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

        <div class="dashboard-grid">
            <div class="card welcome-card">
                <h2>Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                <p>Selamat datang kembali di sistem informasi perpustakaan. Anda bisa menjelajahi katalog buku, mengecek status peminjaman, atau memperbarui profil Anda melalui dashboard ini.</p>
                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <a href="{{ route('catalog') }}" style="padding: 10px 20px; background: #0066cc; border-radius: 8px; font-weight: 600; cursor: pointer; color: white; text-decoration: none;">Cari Buku</a>
                    <a href="{{ route('loans') }}" style="padding: 10px 20px; background: rgba(0,0,0,0.05); border-radius: 8px; font-weight: 600; cursor: pointer; color: #000; text-decoration: none; border: 1px solid rgba(0,0,0,0.1);">Riwayat Pinjam</a>
                </div>
            </div>

            <div class="stats-container">
                <div class="stat-box">
                    <h3>{{ $totalBooks }}</h3>
                    <p>Total Buku</p>
                </div>

                <div class="stat-box">
                    <h3>{{ $borrowedBooksCount }}</h3>
                    <p>Buku Dipinjam</p>
                </div>

                <div class="card" style="grid-column: 1 / -1; padding: 20px; background: rgba(0, 102, 204, 0.1); border-color: rgba(0, 102, 204, 0.2);">
                    <h4 style="font-size: 16px; margin-bottom: 15px; color: #004080; display: flex; align-items: center; gap: 8px;">
                        📂 E-Book Terbaru
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($recentEbooks as $ebook)
                            <a href="{{ route('ebooks.read', $ebook->slug) }}" style="text-decoration: none; color: #000; display: block; background: rgba(255,255,255,0.6); padding: 10px; border-radius: 8px; transition: 0.3s; border: 1px solid rgba(0,0,0,0.05);">
                                <div style="font-size: 13px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ebook->title }}</div>
                                <div style="font-size: 11px; color: #555;">{{ $ebook->author }}</div>
                            </a>
                        @endforeach
                        <a href="{{ route('ebooks') }}" style="text-align: center; font-size: 12px; color: #0066cc; margin-top: 10px; text-decoration: none; font-weight: 700;">Lihat Semua →</a>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="section-title">📚 Buku Terbaru</h3>
        
        <div class="book-list">
            @forelse ($recentBooks as $book)
                <div class="book-card">
                    <div class="book-cover">
                        📖
                        <div style="position: absolute; bottom: 10px; right: 10px; background: #fbbf24; color: #000; padding: 2px 8px; border-radius: 5px; font-size: 11px; font-weight: 700;">
                            {{ $book->stock }} Stok
                        </div>
                    </div>
                    <div class="book-info">
                        <div class="book-title" title="{{ $book->title }}">{{ $book->title }}</div>
                        <div class="book-author">{{ $book->author }}</div>
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
                    <p>Belum ada koleksi buku yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
