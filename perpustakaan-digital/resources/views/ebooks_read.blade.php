<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membaca: {{ $ebook['title'] }} - Perpustakaan Digital</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', sans-serif;
        }

        .reader-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: #2c2c2c;
        }

        .reader-header {
            background: #1a1a1a;
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            z-index: 10;
        }

        .book-info h1 {
            font-size: 18px;
            margin: 0;
        }

        .book-info p {
            font-size: 12px;
            margin: 5px 0 0;
            opacity: 0.7;
        }

        .back-btn {
            background: rgba(255,255,255,0.1);
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        .pdf-viewer {
            flex-grow: 1;
            width: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <div class="reader-wrapper">
        <header class="reader-header">
            <div class="book-info">
                <h1>{{ $ebook['title'] }}</h1>
                <p>Oleh: {{ $ebook['author'] }}</p>
            </div>
            <a href="{{ route('ebooks') }}" class="back-btn">← Kembali</a>
        </header>

        @if(isset($ebook['file_url']))
            <iframe src="{{ $ebook['file_url'] }}" class="pdf-viewer"></iframe>
        @else
            <div style="color: white; display: flex; align-items: center; justify-content: center; height: 100%;">
                <p>Maaf, pratinjau tidak tersedia.</p>
            </div>
        @endif
    </div>
</body>
</html>
