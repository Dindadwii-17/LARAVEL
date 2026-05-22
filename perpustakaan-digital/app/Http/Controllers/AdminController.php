<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalMembers = User::where('role', 'member')->where('is_approved', true)->count();
        $totalLoans = Loan::whereNull('return_date')->count();
        
        $pendingApprovalsCount = User::where('role', 'member')->where('is_approved', false)->count();
        
        $totalUnpaidFinesCount = Loan::where('is_paid', false)
                                     ->where(function($query) {
                                         $query->where('fine_amount', '>', 0)
                                               ->orWhere(function($q) {
                                                   $q->whereNull('return_date')->where('due_date', '<', now());
                                               });
                                     })
                                     ->count();

        return view('admin.dashboard', compact('totalBooks', 'totalMembers', 'totalLoans', 'pendingApprovalsCount', 'totalUnpaidFinesCount'));
    }

    // --- MANAJEMEN BUKU ---
    public function books()
    {
        $books = Book::orderBy('id', 'asc')->get();
        return view('admin.books.index', compact('books'));
    }

    public function ebooks()
    {
        $ebooks = Book::where('is_ebook', true)->with('category')->latest()->get();
        return view('admin.ebooks.index', compact('ebooks'));
    }

    public function createEbook()
    {
        $categories = \App\Models\Category::all();
        return view('admin.ebooks.create', compact('categories'));
    }

    public function storeEbook(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'ebook_file' => 'required|file|mimes:pdf,epub|max:10240', // Max 10MB
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('ebook_file')) {
            $file = $request->file('ebook_file');
            $fileName = 'ebooks/' . Str::slug($validated['title']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ebooks', basename($fileName), 'public');
            
            $size = $this->formatBytes($file->getSize());

            Book::create([
                'title' => $validated['title'],
                'author' => $validated['author'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'file_path' => $path,
                'file_size' => $size,
                'is_ebook' => true,
                'stock' => 0,
            ]);

            return redirect()->route('admin.ebooks')->with('success', 'E-Book berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah file.');
    }

    public function editEbook(Book $book)
    {
        $categories = \App\Models\Category::all();
        $pendingApprovalsCount = User::where('role', 'member')->where('is_approved', false)->count();
        return view('admin.ebooks.edit', compact('book', 'categories', 'pendingApprovalsCount'));
    }

    public function updateEbook(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'ebook_file' => 'nullable|file|mimes:pdf,epub|max:10240', // Max 10MB
            'description' => 'nullable|string',
        ]);

        $data = [
            'title' => $validated['title'],
            'author' => $validated['author'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
        ];

        if ($request->hasFile('ebook_file')) {
            // Delete old file if exists
            if ($book->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->file_path);
            }

            $file = $request->file('ebook_file');
            $fileName = 'ebooks/' . Str::slug($validated['title']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ebooks', basename($fileName), 'public');
            
            $data['file_path'] = $path;
            $data['file_size'] = $this->formatBytes($file->getSize());
        }

        $book->update($data);

        return redirect()->route('admin.ebooks')->with('success', 'E-Book berhasil diperbarui!');
    }

    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function createBook()
    {
        return view('admin.books.create');
    }

    public function storeBook(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer',
            'isbn' => 'nullable|string|unique:books,isbn',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Book::create($validated);
        return redirect()->route('admin.books')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function editBook(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function updateBook(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer',
            'isbn' => 'nullable|string|unique:books,isbn,'.$book->id,
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'ebook_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $data = $validated;
        unset($data['ebook_file']); // Remove file from basic data update

        if ($request->hasFile('ebook_file')) {
            // Delete old file if exists
            if ($book->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->file_path);
            }

            $file = $request->file('ebook_file');
            $fileName = 'ebooks/' . Str::slug($validated['title']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ebooks', basename($fileName), 'public');
            
            $data['file_path'] = $path;
            $data['file_size'] = $this->formatBytes($file->getSize());
            $data['is_ebook'] = true; // Mark as available in e-book menu
        }

        $book->update($data);
        return redirect()->route('admin.books')->with('success', 'Buku berhasil diperbarui!');
    }

    public function deleteBook(Book $book)
    {
        $book->delete();
        return back()->with('success', 'Buku telah dihapus.');
    }

    // --- MANAJEMEN ANGGOTA ---
    public function users()
    {
        $users = User::where('role', 'member')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function viewUser(User $user)
    {
        return view('admin.users.view', compact('user'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'nim' => 'nullable|string|max:20|unique:users,nim,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user->update($validated);
        return redirect()->route('admin.users')->with('success', 'Data anggota berhasil diperbarui!');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'Anggota telah dihapus.');
    }

    public function approveUser(User $user)
    {
        $user->update(['is_approved' => true]);
        return back()->with('success', "Anggota {$user->name} telah disetujui.");
    }

    // --- LAPORAN PEMINJAMAN & DENDA ---
    public function loans(Request $request)
    {
        $query = Loan::with(['user', 'book']);

        if ($request->status === 'dikembalikan') {
            $query->whereNotNull('return_date');
        }

        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('loan_date', $request->month);
        }

        if ($request->has('year') && $request->year != '') {
            $query->whereYear('loan_date', $request->year);
        }

        $loans = $query->latest()->get();

        $fine_rate_per_day = 5000;
        foreach ($loans as $loan) {
            if (!$loan->return_date && $loan->due_date->isPast()) {
                // Jika belum kembali dan sudah telat
                $loan->status = 'terlambat';
                $hours_overdue = $loan->due_date->diffInHours(now(), false);
                $days_overdue = $hours_overdue > 0 ? ceil($hours_overdue / 24) : 0;
                $loan->fine_amount = $days_overdue * $fine_rate_per_day;
            } elseif ($loan->return_date) {
                $loan->status = 'dikembalikan';
            }
        }

        // --- Data Denda Aktif (Belum Lunas) ---
        $activeFines = Loan::with(['user', 'book'])
            ->where('is_paid', false)
            ->where(function($query) {
                $query->where('fine_amount', '>', 0)
                      ->orWhere(function($q) {
                          $q->whereNull('return_date')->where('due_date', '<', now());
                      });
            })
            ->latest()
            ->get();

        foreach ($activeFines as $loanFine) {
            if (!$loanFine->return_date) {
                $hours_overdue = $loanFine->due_date->diffInHours(now(), false);
                $days_overdue = $hours_overdue > 0 ? ceil($hours_overdue / 24) : 0;
                $loanFine->fine_amount = $days_overdue * $fine_rate_per_day;
            }
        }

        // --- Riwayat Pembayaran Denda ---
        $paidFinesQuery = Loan::where('fine_amount', '>', 0)->where('is_paid', true);
        if ($request->has('month') && $request->month != '') {
            $paidFinesQuery->whereMonth('loan_date', $request->month);
        }
        if ($request->has('year') && $request->year != '') {
            $paidFinesQuery->whereYear('loan_date', $request->year);
        }
        $paidFines = $paidFinesQuery->with(['user', 'book'])->latest()->get();

        // Data untuk Grafik Bulanan (Tahun Berjalan)
        $currentYear = date('Y');
        $monthlyData = Loan::selectRaw('MONTH(loan_date) as month, count(*) as count')
            ->whereYear('loan_date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $chartMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartMonthly[] = $monthlyData[$m] ?? 0;
        }

        // Data untuk Grafik Status
        $statusData = Loan::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $chartStatus = [
            'menunggu' => $statusData['menunggu'] ?? 0,
            'dipinjam' => $statusData['dipinjam'] ?? 0,
            'dikembalikan' => $statusData['dikembalikan'] ?? 0,
            'terlambat' => $statusData['terlambat'] ?? 0,
        ];

        return view('admin.loans.index', compact('loans', 'chartMonthly', 'chartStatus', 'activeFines', 'paidFines'));
    }

    public function approveLoan(Loan $loan)
    {
        if ($loan->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }

        $book = $loan->book;
        if ($book->stock <= 0) {
            return back()->with('error', 'Stok buku tidak mencukupi untuk menyetujui peminjaman.');
        }

        // Update status and decrement stock
        $loan->update([
            'status' => 'dipinjam',
            'loan_date' => now(), // Loan officially starts now
            'due_date' => now()->addDays(7), // Refresh due date from approval time
        ]);

        $book->decrement('stock');

        return back()->with('success', "Peminjaman buku {$book->title} oleh {$loan->user->name} telah disetujui.");
    }

    public function viewLoan(Loan $loan)
    {
        $loan->load(['user', 'book']);

        // Jika belum kembali dan sudah lewat jatuh tempo, hitung denda berjalan dan ubah status untuk tampilan
        if (!$loan->return_date && $loan->due_date->isPast()) {
            $loan->status = 'terlambat';
            $fine_rate_per_day = 5000;
            $hours_overdue = $loan->due_date->diffInHours(now(), false);
            $days_overdue = $hours_overdue > 0 ? ceil($hours_overdue / 24) : 0;
            $loan->fine_amount = $days_overdue * $fine_rate_per_day;
        } elseif ($loan->return_date) {
            $loan->status = 'dikembalikan';
        }

        return view('admin.loans.view', compact('loan'));
    }

    public function editLoan(Loan $loan)
    {
        $loan->load(['user', 'book']);
        $users = User::where('role', 'member')->get();
        $books = Book::where('stock', '>', 0)->orWhere('id', $loan->book_id)->get();
        return view('admin.loans.edit', compact('loan', 'users', 'books'));
    }

    public function updateLoan(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:loan_date',
            'return_date' => 'nullable|date|after_or_equal:loan_date',
        ]);

        // If book changed, handle stock
        if ($loan->book_id != $validated['book_id']) {
            $oldBook = $loan->book;
            $oldBook->increment('stock');
            
            $newBook = Book::findOrFail($validated['book_id']);
            $newBook->decrement('stock');
        }

        $loan->update($validated);
        return redirect()->route('admin.loans')->with('success', 'Data peminjaman berhasil diperbarui!');
    }

    public function deleteLoan(Loan $loan)
    {
        // If it was still active, return the stock
        if ($loan->status !== 'dikembalikan') {
            $loan->book->increment('stock');
        }
        
        $loan->delete();
        return back()->with('success', 'Data peminjaman telah dihapus.');
    }

    public function returnBook(Loan $loan)
    {
        if ($loan->status === 'dikembalikan') {
            return back()->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        $now = now();
        $fine_amount = 0;
        $fine_rate_per_day = 5000;
        $days_overdue = 0;

        // Hitung denda berdasarkan selisih waktu antara SEKARANG dan JATUH TEMPO
        if ($now->gt($loan->due_date)) {
            $hours_overdue = $loan->due_date->diffInHours($now, false);
            $days_overdue = $hours_overdue > 0 ? ceil($hours_overdue / 24) : 0;
            $fine_amount = $days_overdue * $fine_rate_per_day;
        }

        // Update loan record
        $loan->update([
            'return_date' => $now,
            'status' => 'dikembalikan',
            'fine_amount' => $fine_amount
        ]);

        // Restore book stock
        $book = $loan->book;
        $book->increment('stock');

        $message = "Buku {$book->title} telah berhasil dikembalikan.";
        if ($fine_amount > 0) {
            $message .= " Denda keterlambatan (" . $days_overdue . " hari): Rp " . number_format($fine_amount, 0, ',', '.');
        }

        return back()->with('success', $message);
    }

    public function payFine(Loan $loan)
    {
        $now = now();

        // Jika buku belum dikembalikan, hitung denda berjalan dan kembalikan stok
        if (!$loan->return_date) {
            if (now()->gt($loan->due_date)) {
                $fine_rate_per_day = 5000;
                $hours_overdue = $loan->due_date->diffInHours($now, false);
                $days_overdue = $hours_overdue > 0 ? ceil($hours_overdue / 24) : 0;
                $loan->fine_amount = $days_overdue * $fine_rate_per_day;
            }
            
            // Kembalikan stok buku karena dianggap sudah dikembalikan saat bayar denda
            $loan->book->increment('stock');
        }

        // Mark as paid and set return_date as requested
        $loan->update([
            'is_paid' => true,
            'return_date' => $now,
            'status' => 'dikembalikan',
            'fine_amount' => $loan->fine_amount
        ]);

        return back()->with('success', 'Pembayaran denda anggota ' . ($loan->user->name ?? '') . ' telah dikonfirmasi dan status buku telah diperbarui.');
    }}
