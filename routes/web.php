<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PJController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\EvaluasiController;

// Rute untuk halaman PJ
Route::get('/pj/daftarkegiatan', function () {
    return view('pj.daftarkegiatan'); // Pastikan folder 'PJ' sudah ada di resources/views
});

// Rute untuk halaman PJ
Route::get('/pj/daftarkegiatan', [PJController::class, 'index'])->name('pjdaftarkegiatan.index');

// Rute Tambah Kegiatan
Route::get('/pj/tambahkegiatan', [PJController::class, 'create'])->name('pjdaftarkegiatan.create');
Route::post('/pj/tambahkegiatan', [PJController::class, 'store'])->name('pjdaftarkegiatan.store');

// Rute untuk Mengelola Kegiatan (CRUD)
Route::resource('kegiatan', PJController::class)->except(['show']);

// Rute untuk Edit dan Update Kegiatan
Route::get('/pj/kegiatan/{kegiatan}/edit', [PJController::class, 'edit'])->name('pjdaftarkegiatan.edit');
Route::put('/pj/kegiatan{kegiatan}', [PJController::class, 'update'])->name('pjdaftarkegiatan.update');

// Rute untuk Hapus Kegiatan
Route::delete('/pj/kegiatan/{id}', [PJController::class, 'destroy'])->name('pjdaftarkegiatan.destroy');

// Rute untuk Filter Kegiatan
Route::get('/pj/kegiatan/filter', [PJController::class, 'filter'])->name('pjdaftarkegiatan.filter');

// Rute untuk Search
Route::post('/pj/ajax-search', [PJController::class, 'ajaxSearch'])->name('daftarkegiatan.ajaxSearch');

// Rute untuk Download dan Print
Route::get('/pj/kegiatan/download/{format}', [PJController::class, 'download'])->name('pjdaftarkegiatan.download');
Route::get('/pj/kegiatan/print', [PJController::class, 'printPage'])->name('pjdaftarkegiatan.print');

// Rute untuk halaman Evaluasi Kegiatan (GET)
Route::get('/pj/evaluasikegiatan', [EvaluasiController::class, 'evaluasiKegiatan'])->name('pj.evaluasikegiatan');

// Rute untuk halaman Evaluasi Kegiatan (GET)
Route::get('/pj/evaluasikegiatan', [EvaluasiController::class, 'evaluasiKegiatan'])->name('pj.evaluasikegiatan');

// Route::get('/pj/daftarkegiatan', [PJController::class, 'index'])->name('pjdaftarkegiatan.index');
// Route::get('/pj/tambahkegiatan', [PJController::class, 'create'])->name('pjdaftarkegiatan.create');
// Route::post('/pj/tambahkegiatan', [PJController::class, 'store'])->name('pjdaftarkegiatan.store');
// Route::get('kegiatan/{kegiatan}/edit', [PJController::class, 'edit'])->name('pjdaftarkegiatan.edit');

// // Rute untuk Mengelola Kegiatan (CRUD)
// Route::resource('kegiatan', PJController::class)->except(['show']);

// // Route::get('/kegiatan/{id}/edit', [PJController::class, 'edit'])->name('kegiatan.edit');
// Route::put('/pj/kegiatan/{id}', [PJController::class, 'update'])->name('pjdaftarkegiatan.update');
// Route::delete('/pj/kegiatan/{id}', [PJController::class, 'destroy'])->name('pjdaftarkegiatan.destroy');
// Route::get('/pj/kegiatan/search', [PJController::class, 'search'])->name('pjdaftarkegiatan.search');

// // Rute untuk Filter Kegiatan
// Route::get('/pj/kegiatan/filter', [PJController::class, 'filter'])->name('pjdaftarkegiatan.filter');
// Route::post('/pj/ajax-search', [PJController::class, 'ajaxSearch'])->name('daftarkegiatan.ajaxSearch');
// Route::get('/pj/kegiatan/download/{format}', [PJController::class, 'download'])->name('pjdaftarkegiatan.download');
// Route::get('/pj/kegiatan/print', [PJController::class, 'printPage'])->name('pjdaftarkegiatan.print');


// // Menambahkan route untuk 'kegiatan.index'
// Route::get('/pj/kegiatan', [PimpinanController::class, 'index'])->name('kegiatan.index');

// Rute untuk halaman PIMPINAN
Route::get('/pimpinan/daftarkegiatan', function () {
    return view('pimpinan.daftarkegiatan'); // Pastikan folder 'Pimpinan' sudah ada di resources/views
});

// Rute untuk filter kegiatan
Route::get('/pimpinan/kegiatan/filter', [PimpinanController::class, 'filter'])->name('pimpinandaftarkegiatan.filter');

// Rute untuk Ajax search
Route::post('/pimpinan/ajax-search', [PimpinanController::class, 'ajaxSearch'])->name('pimpinandaftarkegiatan.ajaxSearch');

// Rute untuk download kegiatan
Route::get('/pimpinan/kegiatan/download/{format}', [PimpinanController::class, 'download'])->name('pimpinandaftarkegiatan.download');

// Rute untuk halaman print kegiatan
Route::get('/pimpinan/kegiatan/print', [PimpinanController::class, 'printPage'])->name('pimpinandaftarkegiatan.print');

// Menambahkan route untuk 'daftarkegiatan.index'
Route::get('/pimpinan/daftarkegiatan', [PimpinanController::class, 'index'])->name('pimpinandaftarkegiatan.index');


// Rute untuk halaman Evaluasi Kegiatan (GET)
Route::get('/pimpinan/evaluasikegiatan', [EvaluasiController::class, 'evaluasiKegiatan'])->name('pimpinan.evaluasikegiatan');
Route::post('/pimpinan/evaluasikegiatan/store', [EvaluasiController::class, 'storeEvaluasi'])->name('pimpinanevaluasikegiatan.store');
Route::put('/pimpinan/evaluasikegiatan/edit', [EvaluasiController::class, 'edit'])->name('pimpinanevaluasikegiatan.edit');

// Rute untuk halaman Evaluasi Kegiatan (GET)
Route::get('/pimpinan/evaluasikegiatan', [EvaluasiController::class, 'evaluasiKegiatan'])->name('pimpinan.evaluasikegiatan');
// Rute untuk halaman anggota
Route::get('/anggota/daftarkegiatan', function () {
    return view('anggota.daftarkegiatan'); // Pastikan folder 'anggota' sudah ada di resources/views
});

// Rute untuk filter kegiatan
Route::get('/anggota/kegiatan/filter', [AnggotaController::class, 'filter'])->name('anggotadaftarkegiatan.filter');

// Rute untuk Ajax search
Route::post('/anggota/ajax-search', [AnggotaController::class, 'ajaxSearch'])->name('anggotadaftarkegiatan.ajaxSearch');

// Rute untuk download kegiatan
Route::get('/anggota/kegiatan/download/{format}', [AnggotaController::class, 'download'])->name('anggotadaftarkegiatan.download');

// Rute untuk halaman print kegiatan
Route::get('/anggota/kegiatan/print', [AnggotaController::class, 'printPage'])->name('anggotadaftarkegiatan.print');

// Menambahkan route untuk 'daftarkegiatan.index'
Route::get('/anggota/daftarkegiatan', [AnggotaController::class, 'index'])->name('anggotadaftarkegiatan.index');
