<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman - Admin Perpustakaan</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
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

        .table-card { 
            background: #ffffff; 
            border: 1px solid rgba(0,0,0,0.1); 
            padding: 30px; 
            border-radius: 20px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            overflow-x: auto; 
            margin-bottom: 40px;
        }

        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        
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
        .status-menunggu { background: #fef3c7; color: #92400e; }
        .status-dipinjam { background: #e0f2fe; color: #0369a1; }
        .status-dikembalikan { background: #dcfce7; color: #166534; }
        .status-terlambat { background: #fee2e2; color: #dc3545; }

        .filter-group { display: flex; gap: 12px; }
        select { 
            padding: 10px 16px; 
            background: #ffffff; 
            border: 1px solid rgba(0,0,0,0.1); 
            border-radius: 10px; 
            color: #334155; 
            outline: none; 
            cursor: pointer; 
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        select:hover { border-color: #0066cc; }

        .chart-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-top: 30px; }
        .chart-card { 
            background: #ffffff; 
            border: 1px solid rgba(0,0,0,0.1); 
            padding: 25px; 
            border-radius: 20px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .chart-title { 
            font-size: 18px; 
            font-weight: 700; 
            margin-bottom: 25px; 
            color: #000000; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
        }

        .btn-action { text-decoration: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; margin-right: 5px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; }
        .btn-view { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }
        .btn-view:hover { background: #e2e8f0; }
        .btn-edit { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .btn-edit:hover { background: #bae6fd; }

        .badge-notif {
            display: inline-flex; align-items: center; justify-content: center;
            background: #ff4757; color: white; font-size: 10px; font-weight: 800;
            width: 18px; height: 18px; border-radius: 50%; margin-left: 6px;
        }

        .section-title {
            font-size: 20px;
            margin: 40px 0 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #000000;
            background: #ffffff;
            padding: 8px 20px;
            border-radius: 12px;
            display: inline-flex;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0,0,0,0.1);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
            display: none; justify-content: center; align-items: center; z-index: 2000;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-content {
            background: white; color: #333; border-radius: 30px;
            width: 95%; max-width: 500px; overflow: hidden;
            transform: translateY(30px); transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }
        .modal-overlay.active .modal-content { transform: translateY(0); }
        
        .modal-header {
            background: #ffffff; color: #000;
            padding: 30px; text-align: center; position: relative;
            border-bottom: 1px solid #eee;
        }
        .modal-header i { font-size: 50px; margin-bottom: 15px; color: #10b981; }
        .modal-header h3 { font-size: 24px; font-weight: 700; }
        .close-modal { position: absolute; top: 20px; right: 20px; font-size: 20px; color: #999; cursor: pointer; transition: 0.3s; }
        .close-modal:hover { color: #333; }

        .modal-body { padding: 30px; }
        .modal-info-grid { display: grid; grid-template-columns: 1fr; gap: 15px; margin-bottom: 25px; }
        .info-item { display: flex; align-items: flex-start; gap: 15px; padding: 12px; background: #f8f9fa; border-radius: 15px; border: 1px solid rgba(0,0,0,0.05); }
        .info-item i { margin-top: 3px; color: #10b981; width: 20px; text-align: center; }
        .info-label { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; margin-bottom: 2px; }
        .info-value { font-size: 15px; font-weight: 600; color: #333; }

        .modal-footer { padding: 0 30px 30px; display: flex; gap: 15px; }
        .btn-confirm { flex: 2; padding: 15px; background: #10b981; color: white; border: none; border-radius: 15px; cursor: pointer; font-weight: 700; font-size: 16px; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-confirm:hover { background: #059669; box-shadow: 0 8px 15px rgba(16, 185, 129, 0.2); }
        .btn-cancel { flex: 1; padding: 15px; background: #f1f3f5; color: #495057; border: none; border-radius: 15px; cursor: pointer; font-weight: 700; transition: 0.3s; }
        .btn-cancel:hover { background: #e9ecef; }
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

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #bbf7d0; display: flex; justify-content: space-between; align-items: center;">
                <div><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                <button onclick="this.parentElement.style.display='none'" style="background: transparent; border: none; color: #166534; cursor: pointer; font-size: 16px;"><i class="fas fa-times"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #fecaca; display: flex; justify-content: space-between; align-items: center;">
                <div><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
                <button onclick="this.parentElement.style.display='none'" style="background: transparent; border: none; color: #991b1b; cursor: pointer; font-size: 16px;"><i class="fas fa-times"></i></button>
            </div>
        @endif



        <div class="header-section">
            <h2><i class="fas fa-file-alt"></i> Data Laporan Peminjaman</h2>
            <form action="{{ route('admin.loans') }}" method="GET" id="filterForm" class="filter-group">
                <select name="month" onchange="document.getElementById('filterForm').submit()">
                    <option value="">📅 Semua Bulan</option>
                    @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $num => $name)
                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="year" onchange="document.getElementById('filterForm').submit()">
                    <option value="">📆 Semua Tahun</option>
                    @for($y = date('Y'); $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <select name="status" onchange="document.getElementById('filterForm').submit()">
                    <option value="">🏷️ Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </form>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #000000;">{{ $loan->user->name ?? 'User' }}</div>
                                <div style="font-size: 11px; color: #64748b;">{{ $loan->user->nim ?? '-' }}</div>
                            </td>
                            <td style="font-weight: 600;">{{ $loan->book->title ?? 'Buku' }}</td>
                            <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                            <td>{{ $loan->return_date ? $loan->return_date->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="status-badge status-{{ $loan->status }}">
                                    {{ $loan->status }}
                                </span>
                            </td>
                            <td>
                                @if($loan->status === 'menunggu')
                                    <button type="button" class="btn-action btn-edit" style="background: #10b981; color: white; border: none; cursor: pointer;"
                                            onclick="openVerifyModal({
                                                id: '{{ $loan->id }}',
                                                user: '{{ addslashes($loan->user->name ?? 'User') }}',
                                                nim: '{{ addslashes($loan->user->nim ?? '-') }}',
                                                book: '{{ addslashes($loan->book->title ?? 'Buku') }}',
                                                date: '{{ $loan->loan_date->format('d/m/Y') }}'
                                            })">
                                        <i class="fas fa-check"></i> Verifikasi
                                    </button>
                                @endif
                                <a href="{{ route('admin.loans.view', $loan->id) }}" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('admin.loans.edit', $loan->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.loans.delete', $loan->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peminjaman ini? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action" style="background: #fee2e2; color: #dc3545; border: 1px solid #fecaca; cursor: pointer;">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">Tidak ada data peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="section-title"><i class="fas fa-chart-line"></i> Statistik Peminjaman</h3>

        <div class="chart-grid">
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-chart-bar"></i> Tren Peminjaman Bulanan ({{ date('Y') }})</div>
                <canvas id="monthlyChart" height="150"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-chart-pie"></i> Distribusi Status</div>
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Combined Denda Section -->
        <h3 class="section-title" style="margin-top: 60px;"><i class="fas fa-wallet"></i> Denda Aktif (Belum Lunas)</h3>
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Jatuh Tempo</th>
                        <th>Jumlah Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activeFines as $fine)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #000000;">{{ $fine->user->name ?? 'User' }}</div>
                                <div style="font-size: 11px; color: #64748b;">{{ $fine->user->nim ?? '-' }}</div>
                            </td>
                            <td style="font-weight: 600;">{{ $fine->book->title ?? 'Buku' }}</td>
                            <td>{{ $fine->due_date->format('d/m/Y') }}</td>
                            <td style="color: #dc3545; font-weight: 800;">Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($fine->return_date)
                                    <span class="status-badge" style="background: #e0f2fe; color: #0369a1;">Buku Kembali</span>
                                @else
                                    <span class="status-badge status-terlambat">Masih Dipinjam</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.loans.pay_fine', $fine->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran denda anggota {{ $fine->user->name ?? '' }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action" style="background: #10b981; color: white; border: none; cursor: pointer; padding: 8px 15px;">
                                        <i class="fas fa-check-circle"></i> Bayar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">Tidak ada denda aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="section-title" style="margin-top: 40px;"><i class="fas fa-history"></i> Riwayat Pembayaran Denda</h3>
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Kembali</th>
                        <th>Jumlah Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paidFines as $paid)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #000000;">{{ $paid->user->name ?? 'User' }}</div>
                                <div style="font-size: 11px; color: #64748b;">{{ $paid->user->nim ?? '-' }}</div>
                            </td>
                            <td style="font-weight: 600;">{{ $paid->book->title ?? 'Buku' }}</td>
                            <td>{{ $paid->return_date ? $paid->return_date->format('d/m/Y') : '-' }}</td>
                            <td style="font-weight: 700;">Rp {{ number_format($paid->fine_amount, 0, ',', '.') }}</td>
                            <td><span class="status-badge status-dikembalikan">Lunas</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">Belum ada riwayat pembayaran denda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Verification Modal -->
    <div id="verifyModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-clipboard-check"></i>
                <h3>Verifikasi Peminjaman</h3>
                <div class="close-modal" onclick="closeVerifyModal()"><i class="fas fa-times"></i></div>
            </div>
            
            <div class="modal-body">
                <p style="color: #666; margin-bottom: 25px; font-size: 15px; text-align: center;">Apakah Anda yakin ingin menyetujui permintaan peminjaman ini?</p>
                
                <div class="modal-info-grid">
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <div class="info-label">Nama Anggota</div>
                            <div class="info-value" id="verifyUserName"></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-id-card"></i>
                        <div>
                            <div class="info-label">NIM</div>
                            <div class="info-value" id="verifyUserNim"></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-book"></i>
                        <div>
                            <div class="info-label">Judul Buku</div>
                            <div class="info-value" id="verifyBookTitle"></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-calendar-day"></i>
                        <div>
                            <div class="info-label">Tanggal Pengajuan</div>
                            <div class="info-value" id="verifyLoanDate"></div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="verifyForm" method="POST">
                @csrf
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeVerifyModal()">Batal</button>
                    <button type="submit" class="btn-confirm">
                        <i class="fas fa-check-circle"></i> Setujui Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal logic
        const verifyModal = document.getElementById('verifyModal');
        const verifyForm = document.getElementById('verifyForm');

        function openVerifyModal(loan) {
            console.log('Opening verify modal for loan:', loan);
            document.getElementById('verifyUserName').textContent = loan.user;
            document.getElementById('verifyUserNim').textContent = loan.nim;
            document.getElementById('verifyBookTitle').textContent = loan.book;
            document.getElementById('verifyLoanDate').textContent = loan.date;

            verifyForm.action = `/admin/loans/${loan.id}/approve`;
            verifyModal.classList.add('active');
        }

        function closeVerifyModal() {
            verifyModal.classList.remove('active');
        }

        // Close modal if clicking outside
        verifyModal.addEventListener('click', (e) => {
            if (e.target === verifyModal) closeVerifyModal();
        });

        // Chart data
        const monthlyData = @json($chartMonthly);
        const statusData = @json($chartStatus);

        // Monthly Chart
        const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: monthlyData,
                    backgroundColor: 'rgba(0, 102, 204, 0.7)',
                    borderColor: '#0066cc',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: '#64748b' } },
                    x: { grid: { display: false }, ticks: { color: '#64748b' } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // Status Chart
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Dipinjam', 'Dikembalikan', 'Terlambat'],
                datasets: [{
                    data: [statusData.menunggu, statusData.dipinjam, statusData.dikembalikan, statusData.terlambat],
                    backgroundColor: [
                        '#fef3c7',
                        '#e0f2fe',
                        '#dcfce7',
                        '#fee2e2'
                    ],
                    borderColor: [
                        '#92400e',
                        '#0369a1',
                        '#166534',
                        '#dc3545'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#334155', padding: 20, font: { size: 12, weight: '600' } }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</body>
</html>
