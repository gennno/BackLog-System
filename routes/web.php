<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\EnsureLoggedIn;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TemuanController;
use App\Http\Controllers\TindakanController;

Route::get('/', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware([EnsureLoggedIn::class])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(\App\Http\Middleware\EnsureLoggedIn::class)
        ->name('dashboard');

    Route::get('/temuan', [TemuanController::class, 'index'])->name('temuan');
    Route::post('/temuan', [TemuanController::class, 'store'])->name('temuan.store');
    Route::put('/temuan/{id}', [TemuanController::class, 'update'])->name('temuan.update');
    Route::delete('/temuan/{id}', [TemuanController::class, 'destroy'])->name('temuan.destroy');
    
    Route::get('/tindakan', [TindakanController::class, 'index'])->name('tindakan.index');
    Route::get('/tindakan/export', [TindakanController::class, 'export'])->name('tindakan.export');
    Route::post('/tindakan/update', [TindakanController::class, 'store'])->name('tindakan.store');
    Route::delete('/tindakan/{id}', [TindakanController::class, 'destroy'])->name('tindakan.destroy');
    Route::get('/tindakan/{id}', [TindakanController::class, 'show'])->name('detail-tindakan');
    Route::put('/tindakan/{id}', [TindakanController::class, 'update'])->name('tindakan.update');

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

