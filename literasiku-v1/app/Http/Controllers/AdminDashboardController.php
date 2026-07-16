<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $field = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'id';

        $loginAttempt = [
            $field => $credentials['username'],
            'password' => $credentials['password']
        ];

        if (auth()->attempt($loginAttempt)) {
            if (auth()->user()->status !== 'Aktif' && auth()->user()->role !== 'admin') {
                auth()->logout();
                return back()->withErrors([
                    'username' => 'Akun Anda telah ditangguhkan. Silakan hubungi administrator.',
                ])->onlyInput('username');
            }
            $request->session()->regenerate();
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'username' => 'Username/Email atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    public function index()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('login')->withErrors(['username' => 'Akses ditolak. Silakan login sebagai administrator.']);
        }

        $books = Book::all();
        $members = User::where('role', 'member')->get()->map(function($m) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'email' => $m->email,
                'joinedDate' => $m->created_at->format('Y-m-d'),
                'status' => $m->status,
                'borrowedCount' => Transaction::where('member_id', $m->id)
                    ->whereIn('status', ['Dipinjam', 'Terlambat'])
                    ->count()
            ];
        });

        $transactions = Transaction::with(['member', 'book'])->get()->map(function($t) {
            $status = $t->status;
            if ($t->return_date && $status !== 'Kembali') {
                $status = 'Kembali';
            }
            return [
                'id' => $t->id,
                'memberId' => $t->member_id,
                'memberName' => $t->member ? $t->member->name : 'N/A',
                'bookId' => $t->book_id,
                'bookTitle' => $t->book ? $t->book->title : 'N/A',
                'borrowDate' => $t->borrow_date ? $t->borrow_date->format('Y-m-d') : null,
                'dueDate' => $t->due_date ? $t->due_date->format('Y-m-d') : null,
                'returnDate' => $t->return_date ? $t->return_date->format('Y-m-d') : null,
                'status' => $status,
                'fine' => (int) $t->fine,
                'finePaid' => (bool) $t->fine_paid,
                'paymentProof' => $t->payment_proof,
                'paymentStatus' => $t->payment_status
            ];
        });

        $categories = Category::pluck('name');

        return view('admin_dashboard', compact('books', 'members', 'transactions', 'categories'));
    }

    // Books CRUD
    public function storeBook(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|unique:books,id',
            'title' => 'required|string',
            'author' => 'required|string',
            'category' => 'required|string',
            'isbn' => 'required|string',
            'stock' => 'required|integer|min:0',
            'available' => 'required|integer|min:0',
            'cover' => 'required|string',
            'file_size' => 'required|string',
            'pages' => 'required|integer|min:1',
            'pdf_url' => 'nullable|string'
        ]);

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $data['pdf_url'] = '/uploads/' . $fileName;
        }

        $data['is_ebook'] = !empty($data['pdf_url']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['title']);

        $book = Book::create($data);
        return response()->json(['success' => true, 'book' => $book]);
    }

    public function updateBook(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category' => 'required|string',
            'isbn' => 'required|string',
            'stock' => 'required|integer|min:0',
            'available' => 'required|integer|min:0',
            'cover' => 'required|string',
            'file_size' => 'required|string',
            'pages' => 'required|integer|min:1',
            'pdf_url' => 'nullable|string'
        ]);

        if ($request->hasFile('pdf_file')) {
            if ($book->pdf_url && file_exists(public_path($book->pdf_url))) {
                @unlink(public_path($book->pdf_url));
            }
            $file = $request->file('pdf_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $data['pdf_url'] = '/uploads/' . $fileName;
        }

        $data['is_ebook'] = !empty($data['pdf_url']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['title']);

        $book->update($data);
        return response()->json(['success' => true, 'book' => $book]);
    }

    public function deleteBook($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json(['success' => true]);
    }

    // Members CRUD
    public function storeMember(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|unique:users,id',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'status' => 'required|string'
        ]);

        $member = User::create([
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => $data['status'],
            'role' => 'member',
            'password' => Hash::make('password')
        ]);

        return response()->json([
            'success' => true, 
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'joinedDate' => $member->created_at->format('Y-m-d'),
                'status' => $member->status,
                'borrowedCount' => 0
            ]
        ]);
    }

    public function updateMember(Request $request, $id)
    {
        $member = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'status' => 'required|string'
        ]);

        $member->update($data);
        return response()->json(['success' => true]);
    }

    public function toggleMemberStatus($id)
    {
        $member = User::findOrFail($id);
        $member->status = ($member->status === 'Aktif') ? 'Ditangguhkan' : 'Aktif';
        $member->save();
        return response()->json(['success' => true, 'status' => $member->status]);
    }

    // Circulation CRUD
    public function storeLoan(Request $request)
    {
        $data = $request->validate([
            'memberId' => 'required|exists:users,id',
            'bookId' => 'required|exists:books,id',
            'borrowDays' => 'required|integer|min:1'
        ]);

        $book = Book::findOrFail($data['bookId']);
        if ($book->available <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok buku kosong/tidak tersedia saat ini.'], 400);
        }

        // Decrement availability
        $book->available = $book->available - 1;
        $book->save();

        $borrowDate = now();
        $dueDate = now()->addDays($data['borrowDays']);

        // Generate Transaction ID like T-10x
        $nextIdNum = Transaction::count() + 1;
        $txId = "T-10" . $nextIdNum;
        while (Transaction::where('id', $txId)->exists()) {
            $nextIdNum++;
            $txId = "T-10" . $nextIdNum;
        }

        $tx = Transaction::create([
            'id' => $txId,
            'member_id' => $data['memberId'],
            'book_id' => $data['bookId'],
            'borrow_date' => $borrowDate->format('Y-m-d'),
            'due_date' => $dueDate->format('Y-m-d'),
            'return_date' => null,
            'status' => 'Dipinjam',
            'fine' => 0,
            'fine_paid' => false
        ]);

        return response()->json([
            'success' => true,
            'transaction' => [
                'id' => $tx->id,
                'memberId' => $tx->member_id,
                'memberName' => $tx->member ? $tx->member->name : 'N/A',
                'bookId' => $tx->book_id,
                'bookTitle' => $tx->book ? $tx->book->title : 'N/A',
                'borrowDate' => $tx->borrow_date,
                'dueDate' => $tx->due_date,
                'returnDate' => null,
                'status' => 'Dipinjam',
                'fine' => 0,
                'finePaid' => false
            ]
        ]);
    }

    public function extendLoan($id)
    {
        $tx = Transaction::findOrFail($id);
        $currentDueDate = \Carbon\Carbon::parse($tx->due_date);
        $tx->due_date = $currentDueDate->addDays(7)->format('Y-m-d');
        $tx->status = 'Dipinjam';
        $tx->fine = 0;
        $tx->save();

        return response()->json(['success' => true, 'dueDate' => $tx->due_date]);
    }

    public function approveLoan($id)
    {
        $tx = Transaction::findOrFail($id);

        $book = Book::findOrFail($tx->book_id);
        if ($book->available <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok buku kosong/tidak tersedia saat ini.'], 400);
        }

        // Decrement availability
        $book->available = $book->available - 1;
        $book->save();

        $tx->status = 'Dipinjam';
        $tx->borrow_date = now()->format('Y-m-d');
        $tx->due_date = now()->addDays(7)->format('Y-m-d');
        $tx->save();

        return response()->json([
            'success' => true,
            'borrowDate' => $tx->borrow_date->format('Y-m-d'),
            'dueDate' => $tx->due_date->format('Y-m-d')
        ]);
    }

    public function returnLoan($id)
    {
        $tx = Transaction::findOrFail($id);
        if ($tx->status !== 'Kembali') {
            $book = Book::find($tx->book_id);
            if ($book) {
                $book->available = $book->available + 1;
                $book->save();
            }
            $tx->return_date = now()->format('Y-m-d');
            $tx->status = 'Kembali';
            $tx->save();
        }

        return response()->json(['success' => true, 'returnDate' => $tx->return_date]);
    }

    public function payFine($id)
    {
        $tx = Transaction::findOrFail($id);
        $tx->fine_paid = true;
        $tx->payment_status = 'approved';
        $tx->save();

        return response()->json(['success' => true]);
    }

    public function rejectFinePayment($id)
    {
        $tx = Transaction::findOrFail($id);
        $tx->payment_status = 'rejected';
        $tx->fine_paid = false;
        $tx->save();

        return response()->json(['success' => true]);
    }

    // Categories CRUD
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:categories,name'
        ]);

        $category = Category::create($data);
        return response()->json(['success' => true, 'name' => $category->name]);
    }

    public function deleteCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|exists:categories,name'
        ]);

        Category::where('name', $data['name'])->delete();
        return response()->json(['success' => true]);
    }
}
