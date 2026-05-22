<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Saya - Perpustakaan</title>
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

        .table-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-title { font-size: 20px; margin-bottom: 25px; color: #0066cc; font-weight: 700; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #f1f5f9; color: #64748b; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #334155; }
        
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .status-borrowed { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .status-returned { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .status-overdue { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        
        .empty-state { text-align: center; padding: 60px; color: #64748b; }

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

        <div class="table-card">
            <h2 class="table-title">📖 Riwayat Peminjaman Buku</h2>
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $loan)
                            <tr>
                                <td style="font-weight: 600;">{{ $loan->book->title ?? 'Judul tidak tersedia' }}</td>
                                <td>{{ $loan->loan_date->format('d M Y') }}</td>
                                <td>{{ $loan->due_date->format('d M Y') }}</td>
                                <td>{{ $loan->return_date ? $loan->return_date->format('d M Y') : '-' }}</td>
                                <td>
                                    @if ($loan->status == 'menunggu')
                                        <span class="status-badge status-pending">Menunggu Verifikasi</span>
                                    @elseif ($loan->status == 'dikembalikan')
                                        <span class="status-badge status-returned">Dikembalikan</span>
                                    @elseif ($loan->status == 'terlambat')
                                        <span class="status-badge status-overdue">Terlambat</span>
                                    @else
                                        <span class="status-badge status-borrowed">Dipinjam</span>
                                    @endif
                                </td>
                                <td style="font-weight: 600; color: {{ $loan->fine_amount > 0 ? '#dc2626' : 'inherit' }};">
                                    {{ $loan->fine_amount > 0 ? 'Rp ' . number_format($loan->fine_amount, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <div style="font-size: 48px; margin-bottom: 20px;">📖</div>
                                    <p>Anda belum memiliki riwayat peminjaman buku.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
