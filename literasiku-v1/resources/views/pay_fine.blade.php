<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar Denda - LiterasiKu</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
            color: #334155; 
            line-height: 1.5; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container { max-width: 720px; width: 100%; padding: 20px; }
        
        .card { 
            background: rgba(255, 255, 255, 0.7); 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 24px; 
            box-shadow: 0 25px 50px rgba(0,0,0,0.1); 
            overflow: hidden; 
            display: flex;
            flex-direction: column;
            border: 2px solid #0066cc; /* Garis tepi biru */
        }
        
        .card-header { background: #003366; color: white; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
        .card-header h2 { font-size: 16px; font-weight: 700; }
        .card-header p { opacity: 0.8; font-size: 10px; font-weight: 500; }
        
        .card-content { display: grid; grid-template-columns: 1fr 1.1fr; gap: 0; }
        
        .column-left { padding: 25px; border-right: 1px solid rgba(0,102,204,0.1); background: rgba(255,255,255,0.2); }
        .column-right { padding: 25px; background: transparent; }

        .summary-box { background: rgba(255,255,255,0.5); border-radius: 12px; padding: 12px; margin-bottom: 15px; border: 1px solid rgba(0,102,204,0.1); }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 4px; }
        .label { color: #64748b; font-size: 9px; font-weight: 700; text-transform: uppercase; }
        .value { color: #1e293b; font-weight: 700; font-size: 12px; }
        .total-value { color: #dc3545; font-size: 18px; font-weight: 800; border-top: 1px dashed rgba(0,102,204,0.2); padding-top: 8px; margin-top: 8px; display: flex; justify-content: space-between; width: 100%; }

        .qris-section { text-align: center; padding: 12px; background: #ffffff; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05); }
        .qris-image { width: 110px; height: 110px; margin-bottom: 8px; padding: 4px; border: 1px solid #eee; border-radius: 6px; }
        .bank-info { font-size: 10px; color: #475569; margin-top: 6px; padding: 8px; background: rgba(255,255,255,0.5); border-radius: 10px; line-height: 1.4; border: 1px solid rgba(255,255,255,0.5); }

        .form-group label { display: block; margin-bottom: 8px; font-weight: 700; font-size: 12px; color: #1e293b; }
        .file-input-wrapper { 
            border: 2px dashed rgba(0,102,204,0.2); border-radius: 16px; padding: 20px; text-align: center; cursor: pointer; transition: 0.3s; 
            position: relative; background: rgba(255,255,255,0.3); height: 160px; display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .file-input-wrapper:hover { border-color: #0066cc; background: rgba(255,255,255,0.5); }
        .file-input-wrapper input { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .file-input-wrapper i { font-size: 28px; color: #94a3b8; margin-bottom: 5px; }
        .file-input-wrapper p { font-size: 11px; color: #64748b; }
        .preview-img { width: 100%; height: 100%; object-fit: contain; border-radius: 8px; display: none; position: absolute; top: 0; left: 0; padding: 8px; background: white; z-index: 10; }

        .btn-submit { 
            width: 100%; padding: 12px; background: #10b981; color: white; border: none; border-radius: 10px; 
            font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 15px;
        }
        .btn-submit:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 8px 15px rgba(16, 185, 129, 0.2); }
        .btn-back { display: block; text-align: center; margin-top: 10px; color: #64748b; text-decoration: none; font-size: 12px; font-weight: 600; }
        
        @media (max-width: 600px) {
            .container { max-width: 400px; }
            .card-content { grid-template-columns: 1fr; }
            .column-left { border-right: none; border-bottom: 1px solid rgba(0,102,204,0.1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Pembayaran Denda</h2>
                <p>#LN-{{ $loan->id }}</p>
            </div>
            
            <form action="{{ route('denda.submit', $loan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-content">
                    <!-- Sisi Kiri: Info & QRIS -->
                    <div class="column-left">
                        <div class="summary-box">
                            <div class="summary-item">
                                <span class="label">Buku</span>
                                <span class="value">{{ $loan->book->title }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Tgl Kembali</span>
                                <span class="value">{{ $loan->return_date ? $loan->return_date->format('d M Y') : 'Belum Kembali' }}</span>
                            </div>
                            <div class="total-value">
                                <span class="label" style="font-size: 10px; margin-top: 5px;">Total Denda</span>
                                <span>Rp {{ number_format($loan->fine, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="qris-section">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrisPayload) }}" alt="QRIS" class="qris-image">
                            <div class="bank-info">
                                <strong>Manual: BCA {{ $bankAccount }}</strong><br>
                                a/n Perpustakaan Digital
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Upload -->
                    <div class="column-right">
                        @if($errors->any())
                            <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 12px; font-size: 11px;">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Bukti Transfer</label>
                            <div class="file-input-wrapper">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p id="fileStatus">Klik/Seret foto bukti</p>
                                <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required onchange="previewFile()">
                                <img id="preview" class="preview-img">
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Kirim Bukti
                        </button>
                        <a href="{{ route('loans') }}" class="btn-back">Batal & Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewFile() {
            const preview = document.getElementById('preview');
            const file = document.getElementById('payment_proof').files[0];
            const reader = new FileReader();
            const statusText = document.getElementById('fileStatus');

            reader.onloadend = function () {
                preview.src = reader.result;
                preview.style.display = 'inline-block';
                statusText.textContent = "File terpilih: " + file.name;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>
