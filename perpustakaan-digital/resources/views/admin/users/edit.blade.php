<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anggota - Admin Perpustakaan</title>
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
        
        form { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 20px; display: flex; flex-direction: column; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 12px; color: #64748b; text-transform: uppercase; margin-bottom: 8px; font-weight: 700; letter-spacing: 0.5px; }
        input, textarea { 
            padding: 12px 16px; 
            border-radius: 12px; 
            border: 1px solid #e2e8f0; 
            background: #f8fafc; 
            color: #334155; 
            outline: none; 
            transition: 0.3s;
            font-size: 15px;
        }
        input:focus, textarea:focus { background: #ffffff; border-color: #0066cc; box-shadow: 0 0 0 4px rgba(0,102,204,0.1); }
        
        .btn-save { 
            grid-column: 1 / -1; 
            padding: 16px; 
            background: #0066cc; 
            color: white; 
            border: none; 
            border-radius: 12px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 10px;
            font-size: 16px;
        }
        .btn-save:hover { background: #0052a3; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,102,204,0.3); }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <div class="header">
                <h2><i class="fas fa-user-edit"></i> Edit Anggota</h2>
                <a href="{{ route('admin.users') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group full">
                    <label>NIM</label>
                    <input type="text" name="nim" value="{{ $user->nim }}">
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ $user->username }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ $user->phone }}">
                </div>

                <div class="form-group full">
                    <label>Alamat</label>
                    <textarea name="address" rows="3">{{ $user->address }}</textarea>
                </div>

                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>
