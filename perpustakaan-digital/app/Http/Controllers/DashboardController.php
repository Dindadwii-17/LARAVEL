<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan; // Import model Loan
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\Redirect; // Import Redirect facade
use Illuminate\Support\Facades\Validator; // Import Validator facade
use Illuminate\Support\Facades\Log; // Import Log facade for error logging
use Illuminate\Support\Facades\Storage; // Import Storage facade

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalBooks = Book::count();
        $borrowedBooksCount = Loan::where('user_id', $user->id)
                                  ->whereNull('return_date')
                                  ->count();
        
        $unpaidFinesCount = Loan::where('user_id', $user->id)
                                 ->where('is_paid', false)
                                 ->where(function($query) {
                                     $query->where('fine_amount', '>', 0)
                                           ->orWhere(function($q) {
                                               $q->whereNull('return_date')->where('due_date', '<', now());
                                           });
                                 })
                                 ->count();

        $recentBooks = Book::where('is_ebook', false)->latest()->take(5)->get();
        $recentEbooks = Book::where('is_ebook', true)->latest()->take(3)->get();

        return view('dashboard', compact('totalBooks', 'borrowedBooksCount', 'unpaidFinesCount', 'recentBooks', 'recentEbooks'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|string|in:Laki-laki,Perempuan',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            /** @var \App\Models\User $user */
            $user->update($request->only(['name', 'phone', 'address', 'gender']));
            return Redirect::back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error("Error updating profile: " . $e->getMessage());
            return Redirect::back()->withErrors(['message' => 'Terjadi kesalahan saat memperbarui profil.']);
        }
    }

    public function catalog()
    {
        $books = Book::with('category')->orderBy('id', 'asc')->get();
        return view('catalog', compact('books'));
    }

    public function ebooks()
    {
        $ebooks = Book::where('is_ebook', true)->with('category')->get();
        return view('ebooks', compact('ebooks'));
    }

    public function downloadEbook($slug)
    {
        $book = Book::where('slug', $slug)->where('is_ebook', true)->first();

        if ($book && $book->file_path && Storage::disk('public')->exists($book->file_path)) {
            return Storage::disk('public')->download($book->file_path, $book->title . '.pdf');
        }

        return Redirect::back()->withErrors(['message' => 'File E-Book tidak ditemukan.']);
    }

    public function readEbook($slug)
    {
        $book = Book::where('slug', $slug)->where('is_ebook', true)->first();

        if ($book && $book->file_path && Storage::disk('public')->exists($book->file_path)) {
            $ebook = [
                'title' => $book->title,
                'author' => $book->author,
                'file_url' => asset('storage/' . $book->file_path)
            ];
            return view('ebooks_read', compact('ebook'));
        }

        return Redirect::back()->withErrors(['message' => 'E-Book tidak tersedia untuk dibaca online.']);
    }

    public function loans()
    {
        $user = Auth::user();
        // Fetch loans for the authenticated user, eager-load book details
        $loans = Loan::where('user_id', $user->id)
                      ->with('book') // Eager load the book relationship
                      ->orderBy('loan_date', 'desc') // Order by loan date, newest first
                      ->get();

        $fine_rate_per_day = 5000; // Denda per hari dalam Rupiah (contoh)

        foreach ($loans as $loan) {
            // Jika status menunggu verifikasi
            if ($loan->status === 'menunggu') {
                $loan->fine_amount = 0;
                continue;
            }

            // Jika sudah dikembalikan, gunakan denda yang tersimpan di database
            if ($loan->return_date) {
                $loan->status = 'dikembalikan';
                // fine_amount tetap menggunakan nilai dari database
            } elseif ($loan->due_date && $loan->due_date->isPast()) {
                // Jika belum kembali dan sudah lewat jatuh tempo, hitung denda berjalan
                $loan->status = 'terlambat';
                $hours_overdue = $loan->due_date->diffInHours(now(), false);
                $days_overdue = $hours_overdue > 0 ? ceil($hours_overdue / 24) : 0;
                $loan->fine_amount = $days_overdue * $fine_rate_per_day;
            } else {
                // Jika masih dipinjam dan belum telat
                $loan->status = 'dipinjam';
                $loan->fine_amount = 0;
            }
        }

        // If no loans found, pass an empty collection to the view
        if ($loans->isEmpty()) {
            $loans = collect();
        }

        return view('loans', compact('loans'));
    }

    public function denda()
    {
        $user = Auth::user();
        $loans = Loan::where('user_id', $user->id)
                      ->where('is_paid', false)
                      ->where(function($query) {
                          $query->whereNull('return_date')->where('due_date', '<', now());
                          $query->orWhere('fine_amount', '>', 0);
                      })
                      ->with('book')
                      ->get();

        $fine_rate_per_day = 5000;
        $loansWithFines = collect();
        $totalAmount = 0;

        foreach ($loans as $loan) {
            if ($loan->return_date) {
                $loansWithFines->push($loan);
                $totalAmount += $loan->fine_amount;
            } else {
                $hours_overdue = $loan->due_date->diffInHours(now(), false);
                $days_overdue = $hours_overdue > 0 ? ceil($hours_overdue / 24) : 0;
                
                $loan->fine_amount = $days_overdue * $fine_rate_per_day;
                $loansWithFines->push($loan);
                $totalAmount += $loan->fine_amount;
            }
        }

        $bankAccount = "901013554280";
        
        // PETUNJUK: Jika Anda memiliki string QRIS asli dari bank (seperti Mandiri/BCA), 
        // tempelkan di sini untuk menggantikan QRIS buatan.
        $realQrisString = ""; 

        $qrisPayload = "";
        if ($totalAmount > 0) {
            if (!empty($realQrisString)) {
                $qrisPayload = $realQrisString;
            } else {
                $qrisPayload = $this->generateQRIS($totalAmount, "PERPUSTAKAAN DIGITAL", $bankAccount);
            }
        }

        return view('denda', compact('loansWithFines', 'totalAmount', 'qrisPayload', 'bankAccount'));
    }

    /**
     * Generate a realistic QRIS Payload (Static QRIS)
     * Format EMVCo QR Code
     */
    private function generateQRIS($amount, $merchantName, $accountNumber)
    {
        $amountStr = (string)$amount;

        $payload = [
            "00" => "01",                               // Payload Format Indicator
            "01" => "11",                               // Point of Initiation Method (11 = Static)
            "26" => [                                   // Merchant Account Information
                "00" => "ID.CO.QRIS.WWW",
                "01" => "ID1020231267890",              // NMID Dummy
                "02" => $accountNumber,                 // Account Number
                "03" => "UMI"                           // Merchant Criteria
            ],
            "52" => "0000",                             // Merchant Category Code
            "53" => "360",                              // Currency Code (IDR)
            "54" => $amountStr,                         // Transaction Amount
            "58" => "ID",                               // Country Code
            "59" => substr(strtoupper($merchantName), 0, 25), // Merchant Name
            "60" => "JAKARTA",                          // Merchant City
            "63" => ""                                  // CRC
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

    /**
     * Handle book borrowing request.
     */
    public function borrowBook(Book $book)
    {
        $user = Auth::user();

        // Basic validation: User must be authenticated and approved
        if (!$user || !$user->is_approved) {
            return Redirect::back()->withErrors(['message' => 'Akses ditolak. Pengguna tidak valid atau belum disetujui.']);
        }

        // Check book availability
        if ($book->stock <= 0) {
            return Redirect::back()->withErrors(['message' => 'Buku tidak tersedia.']);
        }

        // Optional: Check if user already borrowed this book and it's not returned
        $existingLoan = Loan::where('user_id', $user->id)
                           ->where('book_id', $book->id)
                           ->whereNull('return_date')
                           ->first();
        if ($existingLoan) {
            return Redirect::back()->withErrors(['message' => 'Anda sudah meminjam buku ini dan belum mengembalikannya.']);
        }

        // Create the loan record
        try {
            Loan::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'loan_date' => now()->format('Y-m-d'), // Gunakan loan_date yang sesuai migrasi
                'due_date' => now()->addDays(7), // Example: Due in 7 days. Adjust as per library policy.
                'status' => 'menunggu',
            ]);

            // Stok tidak dikurangi sekarang, dikurangi saat admin menyetujui
            // $book->stock -= 1;
            // $book->save();

            return Redirect::back()->with('success', 'Permintaan peminjaman berhasil diajukan! Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Error borrowing book: " . $e->getMessage());
            return Redirect::back()->withErrors(['message' => 'Terjadi kesalahan saat meminjam buku. Silakan coba lagi nanti.']);
        }
    }
}