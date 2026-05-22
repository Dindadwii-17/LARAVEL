<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/selamat', function () {
    return 'Selamat Datang di Laravel';
    
}); 

