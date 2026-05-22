<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Anggota - Admin Perpustakaan</title>
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
        
        .header-section { 
            margin-bottom: 30px; 
        }

        .header-section h2 {
            font-size: 24px;
            color: #000000;
            background: #ffffff;
            padding: 10px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.1);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .header-section p {
            margin-top: 10px;
            color: #ffffff;
            font-weight: 500;
            padding-left: 5px;
        }

        .table-card { 
            background: #ffffff; 
            border: 1px solid rgba(0,0,0,0.1); 
            padding: 30px; 
            border-radius: 20px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            overflow-x: auto; 
        }

        table { width: 100%; border-collapse: collapse; min-width: 1000px; }
        
        th { 
            text-align: left; 
            padding: 15px; 
            border-bottom: 2px solid rgba(0,0,0,0.05); 
            color: #64748b; 
            text-transform: uppercase; 
            font-size: 12px; 
            letter-spacing: 1px; 
            font-weight: 700;
        }

        td { 
            padding: 15px; 
            border-bottom: 1px solid rgba(0,0,0,0.05); 
            font-size: 14px; 
            color: #334155;
        }
        
        .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }

        .btn-action { text-decoration: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; margin-right: 5px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; }
        .btn-view { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .btn-view:hover { background: #bae6fd; }
        .btn-edit { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }
        .btn-edit:hover { background: #e2e8f0; }
        .btn-approve { background: #166534; color: white; border: none; }
        .btn-approve:hover { background: #14532d; }
        .btn-delete { background: #fee2e2; color: #dc3545; border: 1px solid #fecaca; }
        .btn-delete:hover { background: #fecaca; }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid #bbf7d0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
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

        <div class="header-section">
            <h2><i class="fas fa-users"></i> Manajemen Anggota</h2>
            <p>Kelola pendaftaran dan data profil mahasiswa.</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Anggota</th>
                        <th>Email / Username</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td style="font-weight: 700; color: #0066cc;">{{ $user->nim ?? '-' }}</td>
                            <td>
                                <div style="font-weight: 700; color: #000000;">{{ $user->name }}</div>
                                <div style="font-size: 11px; color: #64748b;">Daftar: {{ $user->created_at->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div>{{ $user->email }}</div>
                                <div style="font-size: 11px; color: #64748b;">@ {{ $user->username }}</div>
                            </td>
                            <td>
                                @if($user->is_approved)
                                    <span class="status-badge status-active">Aktif</span>
                                @else
                                    <span class="status-badge status-pending">Menunggu ACC</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.view', $user->id) }}" class="btn-action btn-view"><i class="fas fa-eye"></i> Detail</a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                
                                @if(!$user->is_approved)
                                    <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-approve" style="cursor:pointer;"><i class="fas fa-check"></i> Approve</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus anggota ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" style="cursor:pointer; border:none; font-family:inherit;"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">Belum ada data anggota.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
