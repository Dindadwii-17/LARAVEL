<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denda Saya - Perpustakaan</title>
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

        .denda-layout {
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

        .table-title { font-size: 20px; margin-bottom: 25px; color: #0066cc; font-weight: 700; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #f1f5f9; color: #64748b; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #334155; }
        
        .empty-state { text-align: center; padding: 50px; color: #64748b; }

        .sidebar-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky; top: 30px; height: fit-content;
        }

        .sidebar-title { font-size: 18px; margin-bottom: 20px; color: #0066cc; display: flex; align-items: center; gap: 10px; font-weight: 700; }
        
        .qr-container {
            background: #f8fafc; padding: 15px; border-radius: 15px; margin: 20px 0; text-align: center; border: 1px solid #e2e8f0;
        }

        .qr-placeholder {
            width: 100%; aspect-ratio: 1/1; background: #ffffff; border: 2px dashed #e2e8f0; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-direction: column; gap: 10px;
        }

        .total-denda {
            background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 15px; margin-bottom: 20px; text-align: center;
        }
        .total-denda span { font-size: 24px; font-weight: 700; color: #1e40af; }

        .btn-bayar {
            width: 100%; background: #0066cc; color: #ffffff; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-bayar:hover { background: #0052a3; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 102, 204, 0.2); }
        .btn-bayar:disabled { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }

        @media (max-width: 900px) {
            .denda-layout { grid-template-columns: 1fr; }
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

        <div class="denda-layout">
            <div class="card">
                <h2 class="table-title">💰 Riwayat Denda</h2>
                
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Buku</th>
                                <th>Keterlambatan</th>
                                <th>Jumlah Denda</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody> 
                            @php $calculatedTotal = 0; @endphp
                            @forelse ($loansWithFines as $loan)
                                @php
                                    $hours = $loan->due_date->diffInHours($loan->return_date ?? now(), false);
                                    $days = $hours > 0 ? ceil($hours / 24) : 0;
                                    $calculatedTotal += $loan->fine_amount;
                                @endphp
                                <tr>
                                    <td style="font-weight: 600;">{{ $loan->book->title ?? 'Judul tidak tersedia' }}</td>
                                    <td>
                                        <div style="font-weight: 600; color: #b45309;">{{ (int)$days }} Hari</div>
                                        <div style="font-size: 11px; color: #64748b;">Batas: {{ $loan->due_date->format('d M Y') }}</div>
                                    </td>
                                    <td style="font-weight: 700; color: #dc2626;">{{ 'Rp ' . number_format($loan->fine_amount, 0, ',', '.') }}</td>
                                    <td><span style="color: #d97706; font-size: 12px; font-weight: 800; background: #fffbeb; padding: 4px 8px; border-radius: 6px; border: 1px solid #fef3c7;">BELUM DIBAYAR</span></td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <div style="font-size: 48px; margin-bottom: 20px;">✅</div>
                                    <p>Anda tidak memiliki denda yang tertunggak.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="sidebar-card">
                <h3 class="sidebar-title"><i class="fas fa-wallet"></i> Bayar Denda</h3>
                
                <div class="total-denda">
                    <p style="font-size: 12px; color: #1e40af; opacity: 0.7; margin-bottom: 5px; font-weight: 600;">Total Tagihan</p>
                    <span>Rp {{ number_format($totalAmount ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="qr-container">
                    @if(($totalAmount ?? 0) > 0)
                        <div style="margin-bottom: 15px; padding: 12px; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <p style="font-size: 11px; font-weight: 700; margin-bottom: 4px; color: #64748b; text-transform: uppercase;">Transfer Bank / Virtual Account</p>
                            <p style="font-size: 18px; font-weight: 800; color: #0f172a; letter-spacing: 1px;">{{ $bankAccount }}</p>
                        </div>

                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrisPayload) }}&ecc=M" 
                             alt="QR Code Pembayaran" style="width: 100%; max-width: 200px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; padding: 5px;">
                        
                        <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-top: 15px;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS Logo" style="height: 18px;">
                        </div>
                        <p style="color: #0f172a; font-size: 11px; font-weight: 700; margin-top: 8px;">SCAN ATAU TRANSFER KE REKENING DI ATAS</p>
                    @else
                        <div class="qr-placeholder">
                            <i class="fas fa-qrcode" style="font-size: 48px;"></i>
                            <p style="font-size: 13px; font-weight: 600;">Tidak ada tagihan aktif</p>
                        </div>
                    @endif
                </div>

                <p style="font-size: 12px; color: #64748b; text-align: center; margin-bottom: 25px; line-height: 1.5;">
                    Silakan scan kode QR atau transfer ke nomor rekening di atas untuk melakukan pembayaran denda Anda.
                </p>

                <button class="btn-bayar" {{ ($totalAmount ?? 0) == 0 ? 'disabled' : '' }} onclick="alert('Fitur pembayaran otomatis sedang dalam integrasi API. Silakan hubungi petugas perpustakaan.')">
                    <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                </button>
            </div>
        </div>
    </div>
</body>
</html>
