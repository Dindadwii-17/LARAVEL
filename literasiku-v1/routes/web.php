<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\MemberDashboardController;

Route::get('/', function () {
    $books = collect([
        (object)[
            'is_ebook' => false,
            'title' => 'Filosofi Teras',
            'author' => 'Henry Manampiring',
            'description' => 'Buku yang memperkenalkan filsafat Stoikisme dengan cara yang praktis dan relevan untuk kehidupan modern di Indonesia.',
            'stock' => 12,
        ],
        (object)[
            'is_ebook' => false,
            'title' => 'Sapiens: Riwayat Singkat Umat Manusia',
            'author' => 'Yuval Noah Harari',
            'description' => 'Menjelajahi sejarah umat manusia dari Zaman Batu hingga abad ke-21, membahas bagaimana biologi dan sejarah mendefinisikan kita.',
            'stock' => 10,
        ],
        (object)[
            'is_ebook' => true,
            'title' => 'Alice in Wonderland',
            'author' => 'Lewis Carroll',
            'description' => 'E-Book: Alice in Wonderland karya Lewis Carroll. Nikmati bacaan digital berkualitas di perpustakaan kami.',
            'stock' => 0,
        ],
        (object)[
            'is_ebook' => false,
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'description' => 'Kisah perjuangan 10 anak di Belitung dalam mengejar pendidikan di sekolah yang penuh keterbatasan.',
            'stock' => 20,
        ],
        (object)[
            'is_ebook' => true,
            'title' => 'Eloquent JavaScript',
            'author' => 'Marijn Haverbeke',
            'description' => 'E-Book: Eloquent JavaScript karya Marijn Haverbeke. Nikmati bacaan digital berkualitas di perpustakaan kami.',
            'stock' => 0,
        ],
        (object)[
            'is_ebook' => false,
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'description' => 'Panduan komprehensif untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk dengan langkah kecil.',
            'stock' => 25,
        ],
        (object)[
            'is_ebook' => true,
            'title' => 'The Art of War',
            'author' => 'Sun Tzu',
            'description' => 'E-Book: The Art of War karya Sun Tzu. Nikmati bacaan digital berkualitas di perpustakaan kami.',
            'stock' => 0,
        ],
        (object)[
            'is_ebook' => false,
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'description' => 'Panduan praktis untuk menulis kode yang bersih, mudah dibaca, dan mudah dipelihara.',
            'stock' => 8,
        ],
        (object)[
            'is_ebook' => true,
            'title' => 'Pro Git',
            'author' => 'Scott Chacon',
            'description' => 'E-Book: Pro Git karya Scott Chacon. Nikmati bacaan digital berkualitas di perpustakaan kami.',
            'stock' => 0,
        ],
        (object)[
            'is_ebook' => false,
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer',
            'description' => 'Roman sejarah yang mengisahkan perjuangan pemuda pribumi di masa penjajahan Belanda.',
            'stock' => 15,
        ]
    ]);

    $totalPhysicalBooks = $books->where('is_ebook', false)->count();
    $totalEbooks = $books->where('is_ebook', true)->count();
    $totalBooks = $books->count();

    return view('welcome', compact('books', 'totalBooks', 'totalPhysicalBooks', 'totalEbooks'));
});

// Routes to show login and register forms
Route::get('/login', [AdminDashboardController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminDashboardController::class, 'login'])->name('login.process');
Route::post('/logout', [AdminDashboardController::class, 'logout'])->name('logout');

Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/register', function () {
    return redirect()->back()->withInput()->withErrors(['name' => 'Proses pendaftaran dinonaktifkan pada mode pratinjau tampilan ini.']);
})->name('register.process');

// Member Dashboard Routes (Auth protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/catalog', [MemberDashboardController::class, 'catalog'])->name('catalog');
    Route::get('/loans', [MemberDashboardController::class, 'loans'])->name('loans');
    Route::get('/ebooks/read/{slug}', [MemberDashboardController::class, 'readEbook'])->name('ebooks.read');
    Route::get('/pdf-proxy', [MemberDashboardController::class, 'proxyPdf'])->name('pdf.proxy');
    Route::get('/member-card', [MemberDashboardController::class, 'memberCard'])->name('member.card');
    Route::get('/profile', [MemberDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [MemberDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [MemberDashboardController::class, 'updatePassword'])->name('profile.password');
    Route::get('/denda/bayar/{transaction}', [MemberDashboardController::class, 'payFineForm'])->name('denda.pay');
    Route::post('/denda/bayar/{transaction}', [MemberDashboardController::class, 'submitPaymentProof'])->name('denda.submit');
    Route::post('/borrow/{book}', [MemberDashboardController::class, 'borrowBook'])->name('books.borrow');
    Route::post('/chat', [MemberDashboardController::class, 'chat'])->name('chat');
    Route::post('/membership/upgrade', [MemberDashboardController::class, 'upgradeToPremium'])->name('membership.upgrade');
});

// Admin Dashboard MySQL Integration Routes
Route::prefix('admin')->group(function() {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/books', [AdminDashboardController::class, 'storeBook'])->name('admin.books.store');
    Route::put('/books/{id}', [AdminDashboardController::class, 'updateBook'])->name('admin.books.update');
    Route::delete('/books/{id}', [AdminDashboardController::class, 'deleteBook'])->name('admin.books.destroy');
    Route::post('/members', [AdminDashboardController::class, 'storeMember'])->name('admin.members.store');
    Route::put('/members/{id}', [AdminDashboardController::class, 'updateMember'])->name('admin.members.update');
    Route::post('/members/{id}/toggle', [AdminDashboardController::class, 'toggleMemberStatus'])->name('admin.members.toggle');
    Route::post('/loans', [AdminDashboardController::class, 'storeLoan'])->name('admin.loans.store');
    Route::post('/loans/{id}/extend', [AdminDashboardController::class, 'extendLoan'])->name('admin.loans.extend');
    Route::post('/loans/{id}/approve', [AdminDashboardController::class, 'approveLoan'])->name('admin.loans.approve');
    Route::post('/loans/{id}/return', [AdminDashboardController::class, 'returnLoan'])->name('admin.loans.return');
    Route::post('/loans/{id}/pay', [AdminDashboardController::class, 'payFine'])->name('admin.loans.pay');
    Route::post('/loans/{id}/reject-fine', [AdminDashboardController::class, 'rejectFinePayment'])->name('admin.loans.reject-fine');
    Route::post('/categories', [AdminDashboardController::class, 'storeCategory'])->name('admin.categories.store');
    Route::delete('/categories', [AdminDashboardController::class, 'deleteCategory'])->name('admin.categories.destroy');
});

