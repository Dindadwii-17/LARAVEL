<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku - Perpustakaan</title>
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

        .search-section { margin-bottom: 40px; text-align: center; }
        .search-container { position: relative; max-width: 600px; margin: 0 auto; }
        .search-input {
            width: 100%; padding: 15px 25px 15px 50px; border-radius: 30px; border: 1px solid rgba(0,0,0,0.1);
            background: #ffffff; color: #000000; font-size: 16px; outline: none; transition: 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .search-input:focus { border-color: #0066cc; box-shadow: 0 4px 12px rgba(0, 102, 204, 0.1); }
        .search-icon { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: rgba(0,0,0,0.4); }

        .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 30px; }
        .book-card {
            background: white; color: #333; border-radius: 20px; overflow: hidden; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex; flex-direction: column; border: 1px solid rgba(0,0,0,0.05);
        }
        .book-card:hover { transform: translateY(-12px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .book-cover { height: 300px; background: #f8f9fa; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; border-bottom: 1px solid #eee; }
        .book-cover i { font-size: 80px; color: #0066cc; opacity: 0.8; }
        .stock-badge { position: absolute; top: 15px; right: 15px; background: #fbbf24; color: #000; padding: 5px 12px; border-radius: 10px; font-size: 12px; font-weight: 800; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .category-badge { position: absolute; bottom: 15px; left: 15px; background: rgba(0, 102, 204, 0.1); color: #0066cc; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        
        .book-info { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
        .book-title { font-weight: 800; font-size: 18px; margin-bottom: 8px; color: #1a1a1a; line-height: 1.3; }
        .book-author { font-size: 14px; color: #666; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        
        .btn-pinjam {
            width: 100%; padding: 12px; background: #0066cc; color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 700; transition: 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 10px; margin-top: auto;
        }
        .btn-pinjam:hover { background: #0052a3; transform: scale(1.02); }

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
        .modal-header i { font-size: 50px; margin-bottom: 15px; color: #0066cc; }
        .modal-header h3 { font-size: 24px; font-weight: 700; }
        .close-modal { position: absolute; top: 20px; right: 20px; font-size: 20px; color: #999; cursor: pointer; transition: 0.3s; }
        .close-modal:hover { color: #333; }

        .modal-body { padding: 30px; }
        .modal-info-grid { display: grid; grid-template-columns: 1fr; gap: 15px; margin-bottom: 25px; }
        .info-item { display: flex; align-items: flex-start; gap: 15px; padding: 12px; background: #f8f9fa; border-radius: 15px; border: 1px solid rgba(0,0,0,0.05); }
        .info-item i { margin-top: 3px; color: #0066cc; width: 20px; text-align: center; }
        .info-label { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; margin-bottom: 2px; }
        .info-value { font-size: 15px; font-weight: 600; color: #333; }

        .modal-footer { padding: 0 30px 30px; display: flex; gap: 15px; }
        .btn-confirm { flex: 2; padding: 15px; background: #28a745; color: white; border: none; border-radius: 15px; cursor: pointer; font-weight: 700; font-size: 16px; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-confirm:hover { background: #218838; box-shadow: 0 8px 15px rgba(40, 167, 69, 0.2); }
        .btn-cancel { flex: 1; padding: 15px; background: #f1f3f5; color: #495057; border: none; border-radius: 15px; cursor: pointer; font-weight: 700; transition: 0.3s; }
        .btn-cancel:hover { background: #e9ecef; }

        .alert {
            position: relative;
            padding: 15px 45px 15px 25px; border-radius: 15px; margin-bottom: 25px; animation: fadeIn 0.5s ease-out;
            text-align: left; font-size: 14px; display: flex; align-items: center; gap: 12px; font-weight: 500;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .close-alert {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            cursor: pointer; opacity: 0.5; transition: 0.3s; font-size: 16px;
        }
        .close-alert:hover { opacity: 1; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        
        .fine-warning {
            background: #fff7ed;
            border: 1px solid #ffedd5;
            padding: 12px 18px;
            border-radius: 12px;
            color: #9a3412;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        .badge-notif {
            display: inline-flex; align-items: center; justify-content: center;
            background: #ff4757; color: white; font-size: 10px; font-weight: 800;
            width: 18px; height: 18px; border-radius: 50%; margin-left: 6px;
        }

        .no-data {
            grid-column: 1/-1; text-align: center; padding: 100px; background: #ffffff; border-radius: 30px;
            border: 2px dashed rgba(0,0,0,0.1); box-shadow: 0 4px 6px rgba(0,0,0,0.05);
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

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <i class="fas fa-times close-alert" onclick="this.parentElement.style.display='none'"></i>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                <i class="fas fa-times close-alert" onclick="this.parentElement.style.display='none'"></i>
            </div>
        @endif

        <div class="search-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Cari judul buku, penulis, atau kategori...">
            </div>
        </div>

        <div class="book-grid">
            @forelse ($books as $book)
                <div class="book-card">
                    <div class="book-cover">
                        <i class="fas fa-book"></i>
                        <div class="stock-badge">{{ $book->stock }} Stok</div>
                        <div class="category-badge">{{ $book->category->name ?? 'UMUM' }}</div>
                    </div>
                    <div class="book-info">
                        <div class="book-title">{{ $book->title }}</div>
                        <div class="book-author"><i class="fas fa-pen-nib"></i> {{ $book->author }}</div>
                        <button type="button" class="btn-pinjam" 
                                onclick="openModal({
                                    id: '{{ $book->id }}',
                                    title: '{{ $book->title }}',
                                    author: '{{ $book->author }}',
                                    publisher: '{{ $book->publisher ?? '-' }}',
                                    year: '{{ $book->publication_year ?? '-' }}',
                                    isbn: '{{ $book->isbn ?? '-' }}',
                                    category: '{{ $book->category->name ?? 'Umum' }}'
                                })">
                            <i class="fas fa-hand-holding-hand"></i> Pinjam Buku
                        </button>
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <i class="fas fa-book-open" style="font-size: 50px; opacity: 0.3; margin-bottom: 20px; display: block;"></i>
                    <p style="font-size: 18px; opacity: 0.5;">Belum ada buku tersedia di katalog.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Enhanced Modal Pop-up Konfirmasi -->
    <div id="borrowModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-book-bookmark"></i>
                <h3>Ajukan Peminjaman</h3>
                <div class="close-modal" onclick="closeModal()"><i class="fas fa-times"></i></div>
            </div>
            
            <div class="modal-body">
                <p style="color: #666; margin-bottom: 25px; font-size: 15px; text-align: center;">Permintaan peminjaman Anda akan diverifikasi oleh Admin.</p>
                
                <div class="modal-info-grid">
                    <div class="info-item">
                        <i class="fas fa-heading"></i>
                        <div>
                            <div class="info-label">Judul Buku</div>
                            <div class="info-value" id="modalBookTitle"></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-user-edit"></i>
                        <div>
                            <div class="info-label">Penulis</div>
                            <div class="info-value" id="modalBookAuthor"></div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="info-item">
                            <i class="fas fa-building"></i>
                            <div>
                                <div class="info-label">Penerbit</div>
                                <div class="info-value" id="modalBookPublisher"></div>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <div class="info-label">Tahun Terbit</div>
                                <div class="info-value" id="modalBookYear"></div>
                            </div>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <div class="info-label">Estimasi Durasi</div>
                            <div class="info-value">7 Hari setelah disetujui Admin</div>
                        </div>
                    </div>
                </div>

                <div class="fine-warning">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>Dikenakan denda, apabila melewati batas pengembalian!</span>
                </div>
            </div>

            <form id="borrowForm" method="POST">
                @csrf
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-confirm" style="background: #0066cc;">
                        <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('borrowModal');
        const form = document.getElementById('borrowForm');
        
        function openModal(book) {
            document.getElementById('modalBookTitle').textContent = book.title;
            document.getElementById('modalBookAuthor').textContent = book.author;
            document.getElementById('modalBookPublisher').textContent = book.publisher;
            document.getElementById('modalBookYear').textContent = book.year;

            // Set form action dynamically
            form.action = `/borrow/${book.id}`;
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        // Close modal if clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        // Simple search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.book-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.book-title').textContent.toLowerCase();
                const author = card.querySelector('.book-author').textContent.toLowerCase();
                const category = card.querySelector('.category-badge').textContent.toLowerCase();
                
                if (title.includes(term) || author.includes(term) || category.includes(term)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
