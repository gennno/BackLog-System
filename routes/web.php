<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\EnsureLoggedIn;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TemuanController;
use App\Http\Controllers\TindakanController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\ProfileController;

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

    Route::get('/perbaikan', [PerbaikanController::class, 'index'])->name('perbaikan.index');
    Route::post('/perbaikan', [PerbaikanController::class, 'store'])->name('perbaikan.store');
    Route::get('/perbaikan/{id}/edit', [PerbaikanController::class, 'edit'])->name('perbaikan.edit');
    Route::put('/perbaikan/{id}', [PerbaikanController::class, 'update'])->name('perbaikan.update');
    Route::delete('/perbaikan/{id}', [PerbaikanController::class, 'destroy'])->name('perbaikan.destroy');


    Route::get('/unit', [UnitController::class, 'index'])->name('unit.index');
    Route::post('/unit', [UnitController::class, 'store'])->name('unit.store');
    Route::put('/unit/{unit}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('/unit/{unit}', [UnitController::class, 'destroy'])->name('unit.destroy');
    Route::get('/unit/{unit}/edit', [UnitController::class, 'edit'])->name('unit.edit');

    Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
    Route::post('/tools', [ToolController::class, 'store'])->name('tools.store');
    Route::get('/tools/{tool}/edit', [ToolController::class, 'edit'])->name('tools.edit');
    Route::put('/tools/{tool}', [ToolController::class, 'update'])->name('tools.update');
    Route::delete('/tools/{tool}', [ToolController::class, 'destroy'])->name('tools.destroy');

Route::get('/learning', action: function () {
    return view('learning');
});

    Route::get('/pengaturan', [ProfileController::class, 'edit'])->name('pengaturan.edit');
    Route::post('/pengaturan', [ProfileController::class, 'update'])->name('pengaturan.update');




Route::get('/pengguna', action: function () {
    return view('pengguna');
});



});

