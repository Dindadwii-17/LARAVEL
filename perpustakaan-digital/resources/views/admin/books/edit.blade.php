<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - Admin Perpustakaan</title>
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
        input, textarea {
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
        input:focus, textarea:focus { border-color: #0066cc; background: #ffffff; box-shadow: 0 0 0 4px rgba(0,102,204,0.1); }
        
        .btn-update {
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
        .btn-update:hover { background: #0052a3; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,102,204,0.3); }

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

        .ebook-upload-box {
            padding: 25px; 
            background: #f0f9ff; 
            border: 2px dashed #bae6fd; 
            border-radius: 16px; 
            margin-top: 10px;
        }
        .ebook-upload-box label { color: #0369a1; }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <h2>📝 Edit Buku</h2>
            
            <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Judul Buku</label>
                        <input type="text" name="title" value="{{ $book->title }}" required>
                    </div>
                    <div class="form-group">
                        <label>Penulis</label>
                        <input type="text" name="author" value="{{ $book->author }}" required>
                    </div>
                    <div class="form-group">
                        <label>Penerbit</label>
                        <input type="text" name="publisher" value="{{ $book->publisher }}">
                    </div>
                    <div class="form-group">
                        <label>Tahun Terbit</label>
                        <input type="number" name="publication_year" value="{{ $book->publication_year }}">
                    </div>
                    <div class="form-group">
                        <label>ISBN</label>
                        <input type="text" name="isbn" value="{{ $book->isbn }}">
                    </div>
                    <div class="form-group full-width">
                        <label>Jumlah Stok</label>
                        <input type="number" name="stock" value="{{ $book->stock }}" min="0" required>
                    </div>
                    <div class="form-group full-width ebook-upload-box">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            📂 Unggah Versi E-Book (PDF)
                        </label>
                        @if($book->file_path)
                            <div style="font-size: 13px; color: #0369a1; margin-bottom: 12px; font-weight: 500;">
                                📄 File saat ini: {{ basename($book->file_path) }} ({{ $book->file_size }})
                            </div>
                        @endif
                        <input type="file" name="ebook_file" accept=".pdf" style="padding: 8px 0; background: transparent; border: none;">
                        <p style="font-size: 12px; color: #64748b; margin-top: 8px;">Biarkan kosong jika tidak ingin menambah/mengubah versi digital.</p>
                    </div>
                    <div class="form-group full-width">
                        <label>Deskripsi Buku</label>
                        <textarea name="description" rows="4">{{ $book->description }}</textarea>
                    </div>
                </div>
                
                <button type="submit" class="btn-update">Perbarui Koleksi</button>
                <a href="{{ route('admin.books') }}" class="btn-cancel">Batal dan Kembali</a>
            </form>
        </div>
    </div>
</body>
</html>
