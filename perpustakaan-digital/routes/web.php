<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Member Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/catalog', [DashboardController::class, 'catalog'])->name('catalog');
    Route::get('/ebooks', [DashboardController::class, 'ebooks'])->name('ebooks');
    Route::get('/ebooks/download/{slug}', [DashboardController::class, 'downloadEbook'])->name('ebooks.download');
    Route::get('/ebooks/read/{slug}', [DashboardController::class, 'readEbook'])->name('ebooks.read');
    Route::get('/loans', [DashboardController::class, 'loans'])->name('loans');
    Route::get('/denda', [DashboardController::class, 'denda'])->name('denda');
    Route::post('/borrow/{book}', [DashboardController::class, 'borrowBook'])->name('books.borrow');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Books Management
    Route::get('/books', [AdminController::class, 'books'])->name('admin.books');
    Route::get('/ebooks', [AdminController::class, 'ebooks'])->name('admin.ebooks');
    Route::get('/ebooks/create', [AdminController::class, 'createEbook'])->name('admin.ebooks.create');
    Route::post('/ebooks', [AdminController::class, 'storeEbook'])->name('admin.ebooks.store');
    Route::get('/ebooks/{book}/edit', [AdminController::class, 'editEbook'])->name('admin.ebooks.edit');
    Route::put('/ebooks/{book}', [AdminController::class, 'updateEbook'])->name('admin.ebooks.update');
    Route::get('/books/create', [AdminController::class, 'createBook'])->name('admin.books.create');
    Route::post('/books', [AdminController::class, 'storeBook'])->name('admin.books.store');
    Route::get('/books/{book}/edit', [AdminController::class, 'editBook'])->name('admin.books.edit');
    Route::put('/books/{book}', [AdminController::class, 'updateBook'])->name('admin.books.update');
    Route::delete('/books/{book}', [AdminController::class, 'deleteBook'])->name('admin.books.delete');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}', [AdminController::class, 'viewUser'])->name('admin.users.view');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Loans & Fines
    Route::get('/loans', [AdminController::class, 'loans'])->name('admin.loans');
    Route::get('/loans/{loan}', [AdminController::class, 'viewLoan'])->name('admin.loans.view');
    Route::post('/loans/{loan}/approve', [AdminController::class, 'approveLoan'])->name('admin.loans.approve');
    Route::get('/loans/{loan}/edit', [AdminController::class, 'editLoan'])->name('admin.loans.edit');
    Route::put('/loans/{loan}', [AdminController::class, 'updateLoan'])->name('admin.loans.update');
    Route::delete('/loans/{loan}', [AdminController::class, 'deleteLoan'])->name('admin.loans.delete');
    Route::post('/loans/{loan}/return', [AdminController::class, 'returnBook'])->name('admin.loans.return');
    Route::post('/loans/{loan}/pay-fine', [AdminController::class, 'payFine'])->name('admin.loans.pay_fine');
});

require __DIR__.'/auth.php';
