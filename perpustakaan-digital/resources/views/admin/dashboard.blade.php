<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sistem Informasi Perpustakaan</title>
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

        .dashboard-header { margin-bottom: 30px; }
        .dashboard-header h1 { font-size: 28px; color: #ffff00; margin-bottom: 8px; }
        .dashboard-header p { font-size: 16px; color: #ffffff; }

        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 20px; 
            margin-bottom: 40px; 
        }

        .stat-card { 
            background: #ffffff; 
            border: 1px solid rgba(0, 0, 0, 0.1); 
            padding: 30px; 
            border-radius: 20px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h4 { 
            font-size: 14px; 
            color: #64748b; 
            text-transform: uppercase; 
            margin-bottom: 10px; 
            letter-spacing: 1px;
            font-weight: 700;
        }

        .stat-card h3 { 
            font-size: 36px; 
            font-weight: 700; 
            color: #000000;
        }

        .quick-actions { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 20px; 
        }

        .action-btn {
            background: #ffffff; 
            border: 1px solid rgba(0, 0, 0, 0.1); 
            padding: 25px; 
            border-radius: 15px;
            text-align: center; 
            text-decoration: none; 
            color: #334155; 
            transition: 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-weight: 600;
        }

        .action-btn:hover { 
            background: rgba(0, 0, 0, 0.02); 
            transform: translateY(-5px); 
            color: #0066cc;
            border-color: #0066cc;
        }

        .action-icon { font-size: 32px; margin-bottom: 10px; display: block; }
        
        .badge-notif {
            display: inline-flex; align-items: center; justify-content: center;
            background: #ff4757; color: white; font-size: 10px; font-weight: 800;
            width: 18px; height: 18px; border-radius: 50%; margin-left: 6px;
        }

        .section-title {
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #000000;
            background: #ffffff;
            padding: 5px 15px;
            border-radius: 10px;
            display: inline-flex;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="navbar">
            <div class="navbar-brand-group">
                <h1>Admin Perpustakaan</h1>
                <ul class="nav-links">
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.books') }}" class="{{ request()->routeIs('admin.books') ? 'active' : '' }}">Manajemen Buku</a></li>
                    <li><a href="{{ route('admin.ebooks') }}" class="{{ request()->routeIs('admin.ebooks') ? 'active' : '' }}">E-Book</a></li>
                    <li>
                        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            Anggota
                            @if($pendingApprovalsCount > 0)
                                <span class="badge-notif">{{ $pendingApprovalsCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.loans') }}" class="{{ request()->routeIs('admin.loans') ? 'active' : '' }}">
                            Laporan & Denda
                            @if(($pendingLoansCount + $totalUnpaidFinesCount) > 0)
                                <span class="badge-notif">{{ $pendingLoansCount + $totalUnpaidFinesCount }}</span>
                            @endif
                        </a>
                    </li>
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

        <div class="dashboard-header">
            <h1>Halo, Admin {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
            <p>Kelola koleksi buku dan data anggota dengan efisien.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h4>Total Koleksi Buku</h4>
                <h3>{{ $totalBooks }}</h3>
            </div>
            <div class="stat-card">
                <h4>Anggota Terdaftar</h4>
                <h3>{{ $totalMembers }}</h3>
            </div>
            <div class="stat-card">
                <h4>Peminjaman Aktif</h4>
                <h3>{{ $totalLoans }}</h3>
            </div>
        </div>

        <h3 class="section-title">⚡ Akses Cepat</h3>
        <div class="quick-actions">
            <a href="{{ route('admin.books.create') }}" class="action-btn">
                <span class="action-icon">➕</span>
                Tambah Buku Baru
            </a>
            <a href="{{ route('admin.ebooks') }}" class="action-btn">
                <span class="action-icon">📂</span>
                Manajemen E-Book
            </a>
            <a href="{{ route('admin.users') }}" class="action-btn">
                <span class="action-icon">👥</span>
                Kelola Anggota
            </a>
            <a href="{{ route('admin.loans') }}" class="action-btn">
                <span class="action-icon">📊</span>
                Laporan & Statistik
            </a>
        </div>
    </div>
</body>
</html>
