<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MemberDashboardController extends Controller
{
    private function getDashboardData()
    {
        $user = Auth::user();
        $books = Book::all();
        
        $gradients = [
            "from-purple-600 to-indigo-800",
            "from-amber-600 to-amber-900",
            "from-emerald-500 to-teal-800",
            "from-rose-600 to-red-950",
            "from-blue-600 to-slate-900",
            "from-indigo-700 to-cyan-900",
            "from-cyan-700 to-blue-900",
            "from-stone-700 to-amber-950"
        ];
        $textColor = [
            "text-purple-100",
            "text-amber-50",
            "text-emerald-50",
            "text-rose-50",
            "text-blue-50",
            "text-indigo-50",
            "text-cyan-50",
            "text-stone-100"
        ];

        $formattedBooks = $books->map(function ($book) use ($gradients, $textColor) {
            $cleanId = preg_replace('/[^0-9]/', '', $book->id);
            $idNum = is_numeric($cleanId) ? (int)$cleanId : 1;
            $gIndex = $idNum % count($gradients);

            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'category' => $book->category,
                'rating' => round(4.5 + ($idNum % 5) * 0.1, 1),
                'reviewsCount' => (($idNum * 231) % 1500) + 100,
                'year' => 2020 + ($idNum % 6),
                'pages' => $book->pages ?? 150,
                'publisher' => 'Pustaka Utama',
                'status' => $book->available > 0 ? 'Tersedia' : 'Dipinjam',
                'stock' => $book->stock,
                'available' => $book->available,
                'synopsis' => $book->description ?? 'Deskripsi tidak tersedia.',
                'colorGradient' => $gradients[$gIndex],
                'textColor' => $textColor[$gIndex],
                'is_ebook' => $book->is_ebook,
                'slug' => $book->slug,
                'file_path' => $book->file_path,
                'pdf_url' => $book->pdf_url,
                'cover' => $book->cover ?? '📚'
            ];
        });

        $borrowedBookIds = Transaction::where('member_id', $user->id)
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->pluck('book_id')
            ->toArray();

        $pendingBookIds = Transaction::where('member_id', $user->id)
            ->where('status', 'menunggu')
            ->pluck('book_id')
            ->toArray();

        $historyRecords = Transaction::where('member_id', $user->id)
            ->with('book')
            ->orderBy('borrow_date', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'title' => $t->book ? $t->book->title : 'N/A',
                    'date' => $t->borrow_date->format('Y-m-d'),
                    'due_date' => $t->due_date->format('Y-m-d'),
                    'returnDate' => $t->return_date ? $t->return_date->format('Y-m-d') : '-',
                    'penalty' => $t->fine > 0 ? ('Rp ' . number_format($t->fine, 0, ',', '.') . ($t->fine_paid ? ' (Lunas)' : ' (Belum Lunas)')) : 'Rp 0 (Tepat Waktu)',
                    'status' => $t->status,
                    'fine' => (int) $t->fine,
                    'fine_paid' => (bool) $t->fine_paid,
                    'payment_status' => $t->payment_status,
                    'payment_proof' => $t->payment_proof
                ];
            });

        $currentUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nim' => $user->nim ?? '-',
            'phone' => $user->phone ?? '-',
            'address' => $user->address ?? '-',
            'gender' => $user->gender ?? 'Laki-laki',
            'membership' => ($user->isPremium() || $user->role === 'admin') ? 'Premium Scholar' : 'Regular Scholar',
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff&rounded=true',
            'borrowedBooks' => $borrowedBookIds,
            'pendingBooks' => $pendingBookIds,
            'favoriteBooks' => [],
            'readingGoal' => [
                'current' => Transaction::where('member_id', $user->id)->where('status', 'Kembali')->count(),
                'target' => 10
            ]
        ];

        $categories = Category::pluck('name')->prepend('Semua')->toArray();

        return [
            'formattedBooks' => $formattedBooks,
            'currentUser' => $currentUser,
            'historyRecords' => $historyRecords,
            'formattedCategories' => $categories
        ];
    }

    public function index()
    {
        $data = $this->getDashboardData();
        $activeTab = 'dashboard';
        return view('dashboard', array_merge($data, compact('activeTab')));
    }

    public function catalog()
    {
        $data = $this->getDashboardData();
        $activeTab = 'catalog';
        return view('dashboard', array_merge($data, compact('activeTab')));
    }

    public function profile()
    {
        $data = $this->getDashboardData();
        $activeTab = 'profile';
        return view('dashboard', array_merge($data, compact('activeTab')));
    }

    public function memberCard()
    {
        $data = $this->getDashboardData();
        $activeTab = 'member-card';
        return view('dashboard', array_merge($data, compact('activeTab')));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:users,nim,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|string|in:Laki-laki,Perempuan',
        ]);

        $user->update($request->only(['name', 'nim', 'phone', 'address', 'gender']));
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function readEbook($slug)
    {
        $user = Auth::user();
        $book = Book::where('slug', $slug)->where('is_ebook', true)->first();

        if ($book) {
            $fileUrl = $book->pdf_url;
            if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                $fileUrl = asset('storage/' . $book->file_path);
            }
            
            // Proxy remote URLs to bypass browser CORS block
            if (filter_var($fileUrl, FILTER_VALIDATE_URL) && !str_starts_with($fileUrl, asset(''))) {
                $fileUrl = route('pdf.proxy', ['url' => $fileUrl]);
            }
            
            $ebook = [
                'title' => $book->title,
                'author' => $book->author,
                'file_url' => $fileUrl
            ];

            $isPremium = $user->isPremium() || $user->role === 'admin';
            
            return view('ebooks_read', compact('ebook', 'isPremium'));
        }

        return back()->withErrors(['message' => 'E-Book tidak ditemukan.']);
    }

    public function proxyPdf(Request $request)
    {
        $url = $request->query('url');
        if (!$url) {
            abort(400, 'Missing url parameter');
        }

        try {
            // Retrieve external PDF file
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
            ])->get($url);

            if ($response->successful()) {
                return response($response->body(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline',
                ]);
            }
        } catch (\Exception $e) {
            // Fallback
        }

        abort(404, 'PDF file cannot be retrieved');
    }

    public function upgradeToPremium(Request $request)
    {
        $user = Auth::user();
        $duration = (int) $request->input('duration', 12); // Default to 12 months (1 year)
        
        if ($duration === 3) {
            $premiumUntil = now()->addMonths(3);
            $msg = 'Selamat! Akun Anda berhasil di-upgrade ke Premium Scholar selama 3 Bulan.';
        } elseif ($duration === 6) {
            $premiumUntil = now()->addMonths(6);
            $msg = 'Selamat! Akun Anda berhasil di-upgrade ke Premium Scholar selama 6 Bulan.';
        } else {
            $premiumUntil = now()->addYear(1);
            $msg = 'Selamat! Akun Anda berhasil di-upgrade ke Premium Scholar selama 1 Tahun.';
        }

        $user->update([
            'membership_status' => 'premium',
            'premium_until' => $premiumUntil,
        ]);

        return back()->with('success', $msg);
    }

    public function loans()
    {
        $user = Auth::user();
        $loans = Transaction::where('member_id', $user->id)
            ->whereNull('return_date')
            ->get();

        $fine_rate_per_day = 2000;

        foreach ($loans as $loan) {
            if ($loan->status === 'menunggu') {
                continue;
            }

            if ($loan->due_date && $loan->due_date->isPast()) {
                $loan->status = 'Terlambat';
                $days_overdue = abs(now()->startOfDay()->diffInDays($loan->due_date->startOfDay(), false));
                $loan->fine = $days_overdue * $fine_rate_per_day;
                $loan->save();
            }
        }

        $data = $this->getDashboardData();
        $activeTab = 'history';
        return view('dashboard', array_merge($data, compact('activeTab')));
    }

    public function payFineForm(Transaction $transaction)
    {
        if ($transaction->member_id !== Auth::id() || $transaction->fine <= 0 || $transaction->fine_paid) {
            return redirect()->route('loans')->with('error', 'Data denda tidak valid.');
        }

        $bankAccount = "901013554280";
        $qrisPayload = $this->generateQRIS($transaction->fine, "LITERASIKU DENDA", $bankAccount);
        
        $loan = $transaction;

        return view('pay_fine', compact('loan', 'bankAccount', 'qrisPayload'));
    }

    public function submitPaymentProof(Request $request, Transaction $transaction)
    {
        if ($transaction->member_id !== Auth::id() || $transaction->fine_paid) {
            return redirect()->route('loans')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('payment_proof')) {
            if ($transaction->payment_proof) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }

            $file = $request->file('payment_proof');
            $filename = 'bukti_denda_' . Auth::user()->id . '_' . $transaction->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs', $filename, 'public');

            $transaction->update([
                'payment_proof' => $path,
                'payment_status' => 'pending'
            ]);

            return redirect()->route('loans')->with('success', 'Bukti pembayaran denda berhasil diunggah. Mohon tunggu verifikasi admin.');
        }

        return back()->withErrors(['message' => 'Gagal mengunggah berkas bukti.']);
    }

    public function borrowBook(Book $book)
    {
        $user = Auth::user();

        if ($user->status !== 'Aktif') {
            return back()->withErrors(['message' => 'Akun Anda sedang ditangguhkan. Anda tidak dapat melakukan peminjaman.']);
        }

        if ($book->stock <= 0) {
            return back()->withErrors(['message' => 'Stok buku tidak tersedia.']);
        }

        $existingLoan = Transaction::where('member_id', $user->id)
            ->where('book_id', $book->id)
            ->whereNull('return_date')
            ->first();

        if ($existingLoan) {
            return back()->withErrors(['message' => 'Anda sedang meminjam buku ini dan belum mengembalikannya.']);
        }

        $lastIdNum = 100;
        $lastTransaction = Transaction::orderBy('id', 'desc')->first();
        if ($lastTransaction && preg_match('/T-(\d+)/', $lastTransaction->id, $matches)) {
            $lastIdNum = (int)$matches[1];
        }
        $newTxId = 'T-' . ($lastIdNum + 1);

        Transaction::create([
            'id' => $newTxId,
            'member_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'menunggu',
            'fine' => 0,
            'fine_paid' => false,
            'payment_status' => 'none'
        ]);

        return back()->with('success', 'Permintaan peminjaman berhasil diajukan! Menunggu verifikasi admin.');
    }

    public function chat(Request $request)
    {
        $message = strtolower($request->input('message', ''));
        $response = "Maaf, saya tidak mengerti maksud Anda. Ada hal lain yang bisa dibantu?";

        if (str_contains($message, 'cari') || str_contains($message, 'buku') || str_contains($message, 'rekomendasi')) {
            $books = Book::inRandomOrder()->take(2)->get();
            if ($books->isNotEmpty()) {
                $response = "Berikut beberapa rekomendasi buku dari saya:\n";
                foreach ($books as $b) {
                    $response .= "• *" . $b->title . "* oleh " . $b->author . " (" . ($b->is_ebook ? 'E-Book' : 'Buku Fisik') . ")\n";
                }
                $response .= "\nSilakan cari buku ini di menu Katalog!";
            } else {
                $response = "Saat ini tidak ada koleksi buku di perpustakaan.";
            }
        } elseif (str_contains($message, 'pinjam') || str_contains($message, 'cara')) {
            $response = "Untuk meminjam buku:\n1. Buka menu *Katalog*\n2. Cari buku yang ingin dipinjam\n3. Klik *Pinjam Buku* untuk buku fisik atau *Baca Sekarang* untuk E-Book\n4. Tunggu verifikasi admin.";
        } elseif (str_contains($message, 'denda') || str_contains($message, 'bayar')) {
            $response = "Jika Anda memiliki denda keterlambatan:\n1. Masuk ke menu *History*\n2. Klik *Bayar* di samping tagihan\n3. Scan QRIS yang tertera, transfer dan unggah bukti transfer Anda.";
        } elseif (str_contains($message, 'halo') || str_contains($message, 'hi') || str_contains($message, 'pagi') || str_contains($message, 'siang') || str_contains($message, 'sore')) {
            $response = "Halo! Saya Librarian AI. Ada yang bisa saya bantu hari ini?";
        }

        return response()->json(['response' => $response]);
    }

    private function generateQRIS($amount, $merchantName, $accountNumber)
    {
        $amountStr = (string)$amount;
        $payload = [
            "00" => "01",
            "01" => "11",
            "26" => [
                "00" => "ID.CO.QRIS.WWW",
                "01" => "ID1020231267890",
                "02" => $accountNumber,
                "03" => "UMI"
            ],
            "52" => "0000",
            "53" => "360",
            "54" => $amountStr,
            "58" => "ID",
            "59" => substr(strtoupper($merchantName), 0, 25),
            "60" => "JAKARTA",
            "63" => ""
        ];

        $qrisStr = "";
        foreach ($payload as $tag => $value) {
            if (is_array($value)) {
                $subValue = "";
                foreach ($value as $subTag => $v) {
                    $subValue .= $subTag . sprintf("%02d", strlen($v)) . $v;
                }
                $value = $subValue;
            }
            if ($tag !== "63") {
                $qrisStr .= $tag . sprintf("%02d", strlen($value)) . $value;
            }
        }

        $qrisStr .= "6304";
        $qrisStr .= $this->crc16($qrisStr);
        return $qrisStr;
    }

    private function crc16($data)
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc <<= 1;
                }
            }
        }
        return strtoupper(sprintf('%04X', $crc & 0xFFFF));
    }
}
