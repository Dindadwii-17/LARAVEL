<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Only Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('books', BookController::class);
        Route::resource('users', UserController::class);
        Route::resource('borrowings', BorrowingController::class);
        Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');
    });

    // Member Routes
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('books', [BookController::class, 'memberIndex'])->name('books.index');
        Route::get('borrowings', [BorrowingController::class, 'memberIndex'])->name('borrowings.index');
        Route::post('books/{book}/borrow', [BorrowingController::class, 'store'])->name('borrowings.store');
    });
});

require __DIR__.'/auth.php';
