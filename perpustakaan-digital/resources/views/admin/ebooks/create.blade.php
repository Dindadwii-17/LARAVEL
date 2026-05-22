<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah E-Book Baru - Admin Perpustakaan</title>
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
        h2 { color: #000000; margin-bottom: 30px; font-size: 24px; font-weight: 700; text-align: center; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .full-width { grid-column: span 2; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-size: 14px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        input, textarea, select {
            width: 100%; 
            padding: 12px 16px; 
            background: #f8fafc; 
            border: 1px solid #e2e8f0;
            border-radius: 12px; 
            color: #334155; 
            outline: none; 
            transition: 0.3s;
            font-size: 15px;
        }
        input:focus, textarea:focus, select:focus { border-color: #0066cc; background: #ffffff; box-shadow: 0 0 0 4px rgba(0,102,204,0.1); }
        option { background: #ffffff; color: #334155; }
        
        .btn-save {
            background: #0066cc; 
            color: #ffffff; 
            border: none; 
            padding: 16px; 
            border-radius: 12px; 
            font-weight: 700; 
            cursor: pointer; 
            width: 100%; 
            margin-top: 10px;
            font-size: 16px;
            transition: 0.3s;
        }
        .btn-save:hover { background: #0052a3; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,102,204,0.3); }

        .btn-cancel {
            display: block; 
            text-align: center; 
            margin-top: 20px; 
            color: #64748b; 
            text-decoration: none; 
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-cancel:hover { color: #000000; }

        .file-info { font-size: 12px; color: #64748b; margin-top: 8px; font-style: italic; }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <h2>➕ Tambah E-Book Baru</h2>
            
            <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Judul E-Book</label>
                        <input type="text" name="title" placeholder="Masukkan judul e-book" required>
                    </div>
                    <div class="form-group">
                        <label>Penulis</label>
                        <input type="text" name="author" placeholder="Nama penulis" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group full-width" style="padding: 20px; background: #f0f9ff; border: 2px dashed #bae6fd; border-radius: 16px;">
                        <label style="color: #0369a1;">File E-Book (PDF/EPUB)</label>
                        <input type="file" name="ebook_file" accept=".pdf,.epub" required style="background: transparent; border: none; padding: 10px 0;">
                        <div class="file-info">Maksimal ukuran file: 10MB</div>
                    </div>
                    <div class="form-group full-width">
                        <label>Deskripsi E-Book</label>
                        <textarea name="description" rows="4" placeholder="Ringkasan singkat e-book..."></textarea>
                    </div>
                </div>
                
                <button type="submit" class="btn-save">Unggah E-Book</button>
                <a href="{{ route('admin.ebooks') }}" class="btn-cancel">Batal dan Kembali</a>
            </form>
        </div>
    </div>
</body>
</html>
