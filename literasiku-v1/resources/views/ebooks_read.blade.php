<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membaca: {{ $ebook['title'] }} - LiterasiKu</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- PDF.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f172a; /* Slate 900 background */
            color: #f1f5f9;
        }

        .reader-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .reader-header {
            background: #1e293b; /* Slate 800 */
            color: #f8fafc;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 10;
            border-bottom: 1px solid #334155;
        }

        .book-info h1 {
            font-size: 16px;
            margin: 0;
            font-weight: 700;
            color: #ffffff;
        }

        .book-info p {
            font-size: 11px;
            margin: 4px 0 0;
            color: #94a3b8;
        }

        /* Toolbar Controls */
        .toolbar {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #0f172a;
            padding: 6px 16px;
            border-radius: 30px;
            border: 1px solid #334155;
        }

        .toolbar-btn {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 14px;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .toolbar-btn:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }

        .toolbar-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .page-num-indicator {
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            min-width: 80px;
            text-align: center;
        }

        .back-btn {
            background: #3b82f6; /* Blue 500 */
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: #2563eb;
        }

        /* Canvas Container */
        .viewport-container {
            flex-grow: 1;
            overflow: auto;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 30px 10px;
            background: #0f172a;
        }

        #pdf-canvas {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.5);
            background-color: white;
            border-radius: 4px;
            transition: transform 0.1s ease;
        }

        /* Loading Spinner */
        .loading-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 100;
            transition: opacity 0.5s ease;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border-left-color: #3b82f6;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="reader-wrapper">
        <!-- Header -->
        <header class="reader-header">
            <div class="book-info">
                <h1>{{ $ebook['title'] }}</h1>
                <p><i class="fas fa-pen-nib"></i> {{ $ebook['author'] }}</p>
            </div>

            <!-- Toolbar Controls -->
            <div class="toolbar">
                <button id="prev-page" class="toolbar-btn" title="Halaman Sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="page-num-indicator">
                    Halaman <span id="page-num">1</span> / <span id="page-count">0</span>
                </div>
                <button id="next-page" class="toolbar-btn" title="Halaman Berikutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div style="width: 1px; height: 20px; background: #334155; margin: 0 5px;"></div>
                <button id="zoom-out" class="toolbar-btn" title="Perkecil">
                    <i class="fas fa-search-minus"></i>
                </button>
                <button id="zoom-in" class="toolbar-btn" title="Perbesar">
                    <i class="fas fa-search-plus"></i>
                </button>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                @if(!$isPremium)
                    <button onclick="showUpgradeModal(false)" class="back-btn" style="background: linear-gradient(to right, #f59e0b, #ea580c); color: #0f172a; border: none; font-weight: 800; cursor: pointer;">
                        <i class="fas fa-crown"></i> Upgrade Premium
                    </button>
                @endif
                <a href="{{ route('catalog') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                </a>
            </div>
        </header>

        <!-- Viewport -->
        <div class="viewport-container">
            <canvas id="pdf-canvas"></canvas>
        </div>
    </div>

    <!-- Loading Screen Overlay -->
    <div id="loading-overlay" class="loading-screen">
        <div class="spinner"></div>
        <p style="font-size: 14px; color: #94a3b8; font-weight: 500;">Menyiapkan dokumen E-Book...</p>
    </div>

    <!-- Upgrade Premium Modal -->
    <div id="upgrade-modal" class="loading-screen" style="display: none; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(8px); z-index: 150;">
        <div style="background: #1e293b; border: 1px solid #334155; padding: 30px; border-radius: 20px; max-width: 400px; text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);">
            <i class="fas fa-crown" style="font-size: 48px; color: #f59e0b; margin-bottom: 15px; filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.4));"></i>
            <h3 style="margin: 0 0 10px; font-size: 20px; font-weight: 800; color: #ffffff;">Akses E-Book Penuh</h3>
            <p id="upgrade-modal-text" style="font-size: 13px; color: #94a3b8; line-height: 1.6; margin-bottom: 20px;">
                Upgrade ke keanggotaan Premium Scholar sekarang untuk membuka akses E-Book penuh dan mendapatkan fasilitas prioritas tanpa batas!
            </p>
            
            <!-- Package Selection inside Reader Modal -->
            <div style="margin-bottom: 25px; text-align: left;">
                <label style="display: block; font-size: 11px; color: #94a3b8; font-weight: 600; margin-bottom: 8px;">Pilih Paket Langganan:</label>
                <select id="reader-pkg-select" style="width: 100%; padding: 10px; border-radius: 10px; background: #0f172a; border: 1px solid #334155; color: #ffffff; font-size: 13px; font-weight: 700; outline: none; cursor: pointer;">
                    <option value="3">3 Bulan — Rp 49.000</option>
                    <option value="6">6 Bulan — Rp 89.000</option>
                    <option value="12" selected>1 Tahun — Rp 159.000</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="closeUpgradeModal()" class="back-btn" style="background: #334155; color: #cbd5e1; border: none; padding: 10px 20px; cursor: pointer;">
                    Nanti Saja
                </button>
                <form id="reader-upgrade-form" action="{{ route('membership.upgrade') }}" method="POST" style="margin: 0;">
                    @csrf
                    <input type="hidden" name="duration" id="reader-duration-input" value="12">
                    <button type="submit" onclick="document.getElementById('reader-duration-input').value = document.getElementById('reader-pkg-select').value" class="back-btn" style="background: linear-gradient(to right, #f59e0b, #ea580c); color: #0f172a; font-weight: 800; padding: 10px 20px; border: none; cursor: pointer;">
                        Aktifkan Premium
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Set worker path
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

        const url = '{{ $ebook['file_url'] }}';
        const isPremium = @json($isPremium);

        let pdfDoc = null,
            pageNum = 1,
            pageIsRendering = false,
            pageNumPending = null,
            scale = 1.3,
            canvas = document.getElementById('pdf-canvas'),
            ctx = canvas.getContext('2d');

        // Prevent right-click context menu on canvas to block direct image downloads/saving
        canvas.addEventListener('contextmenu', e => e.preventDefault());

        // Render the page
        const renderPage = num => {
            pageIsRendering = true;

            // Get page
            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({ scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderCtx = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                page.render(renderCtx).promise.then(() => {
                    pageIsRendering = false;

                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });

                // Output current page
                document.getElementById('page-num').textContent = num;
            });
        };

        // Check for pages rendering
        const queueRenderPage = num => {
            if (pageIsRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        };

        function showUpgradeModal(isLimitReached = false) {
            const modalText = document.getElementById('upgrade-modal-text');
            if (isLimitReached) {
                modalText.innerText = "Anda telah mencapai batas 30 halaman pratinjau. Upgrade ke Premium Scholar sekarang untuk membaca seluruh dokumen tanpa batas!";
            } else {
                modalText.innerText = "Upgrade ke keanggotaan Premium Scholar sekarang untuk membuka akses E-Book penuh dan mendapatkan fasilitas prioritas tanpa batas!";
            }
            document.getElementById('upgrade-modal').style.display = 'flex';
        }

        function closeUpgradeModal() {
            document.getElementById('upgrade-modal').style.display = 'none';
        }

        // Show Next Page
        const showNextPage = () => {
            const totalPages = pdfDoc.numPages;
            const maxPages = isPremium ? totalPages : Math.min(totalPages, 30);

            if (pageNum >= maxPages) {
                if (!isPremium && totalPages > 30) {
                    showUpgradeModal(true);
                }
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        };

        // Show Prev Page
        const showPrevPage = () => {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        };

        // Zoom In
        const zoomIn = () => {
            if (scale >= 3.0) return;
            scale += 0.2;
            queueRenderPage(pageNum);
        };

        // Zoom Out
        const zoomOut = () => {
            if (scale <= 0.6) return;
            scale -= 0.2;
            queueRenderPage(pageNum);
        };

        // Get Document
        pdfjsLib.getDocument(url).promise.then(pdfDoc_ => {
            pdfDoc = pdfDoc_;
            // Display the true total pages in the E-Book (showing all pages count)
            document.getElementById('page-count').textContent = pdfDoc.numPages;

            // Hide loading overlay
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.style.opacity = '0';
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
            }, 500);

            renderPage(pageNum);
        }).catch(err => {
            console.error('Error loading PDF: ', err);
            const overlay = document.getElementById('loading-overlay');
            overlay.innerHTML = `
                <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #ef4444; margin-bottom: 15px;"></i>
                <p style="font-size: 14px; color: #cbd5e1; text-align: center; max-width: 80%;">Gagal membuka file E-Book. File mungkin rusak atau akses ditolak.</p>
                <a href="{{ route('catalog') }}" class="back-btn" style="margin-top: 20px;">Kembali ke Katalog</a>
            `;
        });

        // Button Events
        document.getElementById('prev-page').addEventListener('click', showPrevPage);
        document.getElementById('next-page').addEventListener('click', showNextPage);
        document.getElementById('zoom-in').addEventListener('click', zoomIn);
        document.getElementById('zoom-out').addEventListener('click', zoomOut);

        // Keyboard navigation
        window.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight' || e.key === 'PageDown') {
                showNextPage();
            } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
                showPrevPage();
            }
        });
    </script>
</body>
</html>
