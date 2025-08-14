<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\EnsureLoggedIn;

Route::get('/', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware([EnsureLoggedIn::class])->group(function () {
    
    Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/temuan', function () {
    return view('temuan');
});

Route::get('/tindakan', function () {
    return view('tindakan');
});

Route::get('/detail-tindakan', function () {
    return view('detail-tindakan');
});

Route::get('/perbaikan', action: function () {
    return view('perbaikan');
});

Route::get('/unit', action: function () {
    return view('unit');
});

Route::get('/tools', action: function () {
    return view('tools');
});

Route::get('/learning', action: function () {
    return view('learning');
});

Route::get('/pengaturan', action: function () {
    return view('pengaturan');
});

Route::get('/pengguna', action: function () {
    return view('pengguna');
});



});

