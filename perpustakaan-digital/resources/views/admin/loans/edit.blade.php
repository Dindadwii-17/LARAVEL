<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peminjaman - Admin Perpustakaan</title>
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
        input, select { 
            padding: 12px 16px; 
            border-radius: 12px; 
            border: 1px solid #e2e8f0; 
            background: #f8fafc; 
            color: #334155; 
            outline: none; 
            transition: 0.3s;
            font-size: 15px;
        }
        input:focus, select:focus { background: #ffffff; border-color: #0066cc; box-shadow: 0 0 0 4px rgba(0,102,204,0.1); }
        select option { background: #ffffff; color: #334155; }
        
        .btn-save { 
            grid-column: 1 / -1; 
            padding: 16px; 
            background: #166534; 
            color: white; 
            border: none; 
            border-radius: 12px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 10px;
            font-size: 16px;
        }
        .btn-save:hover { background: #14532d; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(22,101,52,0.3); }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <div class="header">
                <h2><i class="fas fa-edit"></i> Edit Peminjaman</h2>
                <a href="{{ route('admin.loans') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Anggota</label>
                    <select name="user_id">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $loan->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Buku</label>
                    <select name="book_id">
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ $loan->book_id == $book->id ? 'selected' : '' }}>{{ $book->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="loan_date" value="{{ $loan->loan_date->format('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label>Batas Kembali</label>
                    <input type="date" name="due_date" value="{{ $loan->due_date->format('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label>Tanggal Kembali (Kosongkan jika belum)</label>
                    <input type="date" name="return_date" value="{{ $loan->return_date ? $loan->return_date->format('Y-m-d') : '' }}">
                </div>

                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>
