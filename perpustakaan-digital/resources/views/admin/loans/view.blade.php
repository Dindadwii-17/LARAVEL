<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman - Admin Perpustakaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('/bg.jpeg') center/cover no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            color: #000000;
        }
        .page { max-width: 800px; margin: 0 auto; padding: 50px 20px; }
        .card { 
            background: #ffffff; 
            border: 1px solid rgba(0,0,0,0.1); 
            padding: 40px; 
            border-radius: 24px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px; 
            border-bottom: 1px solid rgba(0,0,0,0.05); 
            padding-bottom: 20px; 
        }
        h2 { font-size: 24px; font-weight: 700; color: #000000; }
        .btn-back { 
            color: #64748b; 
            text-decoration: none; 
            font-size: 14px; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            font-weight: 600;
            transition: 0.3s; 
        }
        .btn-back:hover { color: #000000; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .info-item { 
            background: #f8fafc; 
            padding: 20px; 
            border-radius: 16px; 
            border: 1px solid #e2e8f0; 
        }
        .label { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; font-weight: 700; }
        .value { font-size: 16px; font-weight: 700; color: #000000; }
        
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; margin-top: 5px; text-transform: uppercase; }
        .status-dipinjam { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .status-selesai { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .status-terlambat { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <div class="header">
                <h2><i class="fas fa-info-circle"></i> Detail Peminjaman</h2>
                <a href="{{ route('admin.loans') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="label">ID Transaksi</div>
                    <div class="value">#{{ $loan->id }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Status</div>
                    @php
                        $isReturned = $loan->status === 'dikembalikan';
                        $isOverdue = !$isReturned && $loan->due_date && $loan->due_date->isPast();
                    @endphp
                    @if($isReturned)
                        <span class="status-badge status-selesai">Selesai</span>
                    @elseif($isOverdue)
                        <span class="status-badge status-terlambat">Terlambat</span>
                    @else
                        <span class="status-badge status-dipinjam">Dipinjam</span>
                    @endif
                </div>
                <div class="info-item">
                    <div class="label">Nama Anggota</div>
                    <div class="value">{{ $loan->user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Judul Buku</div>
                    <div class="value">{{ $loan->book->title }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Tanggal Pinjam</div>
                    <div class="value">{{ $loan->loan_date->format('d M Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Batas Kembali</div>
                    <div class="value">{{ $loan->due_date->format('d M Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Tanggal Kembali</div>
                    <div class="value">{{ $loan->return_date ? $loan->return_date->format('d M Y') : 'Belum dikembalikan' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Denda</div>
                    <div class="value" style="color: {{ $loan->fine_amount > 0 ? '#dc2626' : 'inherit' }}">Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
