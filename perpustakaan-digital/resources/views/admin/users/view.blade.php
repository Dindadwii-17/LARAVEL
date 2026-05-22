<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Anggota - Admin Perpustakaan</title>
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
        .status-approved { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <div class="header">
                <h2><i class="fas fa-user-circle"></i> Detail Anggota</h2>
                <a href="{{ route('admin.users') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="label">NIM</div>
                    <div class="value">{{ $user->nim ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">ID Anggota</div>
                    <div class="value">#{{ $user->id }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Status Akun</div>
                    @if($user->is_approved)
                        <span class="status-badge status-approved">Aktif / Disetujui</span>
                    @else
                        <span class="status-badge status-pending">Menunggu Persetujuan</span>
                    @endif
                </div>
                <div class="info-item">
                    <div class="label">Nama Lengkap</div>
                    <div class="value">{{ $user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Username</div>
                    <div class="value">{{ $user->username }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Email</div>
                    <div class="value">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Nomor Telepon</div>
                    <div class="value">{{ $user->phone ?? '-' }}</div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="label">Alamat</div>
                    <div class="value">{{ $user->address ?? 'Alamat belum diisi' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Tanggal Bergabung</div>
                    <div class="value">{{ $user->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
