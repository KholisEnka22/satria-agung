<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\MuridController;
use App\Http\Controllers\Admin\PelatihController;
use App\Http\Controllers\Admin\RayonController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\TahunController;
use Illuminate\Support\Facades\Route;

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
    return redirect('/login');
});

Route::get('/login',[AuthController::class, 'login'])->name('login');
Route::post('/postlogin',[AuthController::class, 'postlogin'])->name('postlogin');
Route::get('/logout',[AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'checkRole:admin,pelatih'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/chart-data', [HomeController::class, 'getData']);

    Route::get('/murid', [MuridController::class, 'index'])->name('admin.murid');
    Route::get('/murid/detail/{id}', [MuridController::class, 'show'])->name('admin.murid.detail');
    Route::get('/tambah-murid', [MuridController::class, 'create'])->name('admin.murid.create');
    Route::post('/murid/simpan', [MuridController::class, 'store'])->name('admin.murid.store');
    Route::post('/murid/toggle-pelatih/{id}', [MuridController::class, 'togglePelatih'])->name('admin.murid.toggle-pelatih');
    Route::get('/murid/edit/{id}', [MuridController::class, 'edit'])->name('admin.murid.edit');
    Route::post('/murid/update/{id}', [MuridController::class, 'update'])->name('admin.murid.update');
    Route::get('murid/delete/{id}', [MuridController::class, 'destroy'])->name('murid.destroy');

    Route::post('/murid/import', [MuridController::class, 'importExcel'])->name('admin.murid.import');
    Route::get('/murid/export', [MuridController::class, 'exportExcel'])->name('admin.murid.export');

    Route::get('/tingkat/{tingkat}', [MuridController::class, 'showMuridByTingkat'])->name('admin.murid.tingkat');

    Route::get('/pelatih', [PelatihController::class, 'index'])->name('admin.pelatih');
    Route::get('/tambah-pelatih', [PelatihController::class, 'create'])->name('admin.pelatih.create');
    Route::post('/pelatih/simpan', [PelatihController::class, 'store'])->name('admin.pelatih.store');

    Route::get('/tahun', [TahunController::class, 'index'])->name('admin.tahun');
    Route::post('/tahun/simpan', [TahunController::class, 'store'])->name('admin.tahun.store');
    Route::get('/tahun/edit/{id}', [TahunController::class, 'edit'])->name('admin.tahun.edit');
    Route::post('/tahun/update/{id}', [TahunController::class, 'update'])->name('admin.tahun.update');

    Route::get('/rayon', [RayonController::class, 'index'])->name('admin.rayon');
    Route::post('/rayon/simpan', [RayonController::class, 'store'])->name('admin.rayon.store');
    Route::post('/rayon/update/{id}', [RayonController::class, 'update'])->name('admin.rayon.update');
    Route::get('/rayon/delete/{id}', [RayonController::class, 'destroy'])->name('admin.rayon.destroy');

    Route::get('/tagihan', [TagihanController::class, 'index'])->name('admin.tagihan');
    Route::post('/tagihan/simpan', [TagihanController::class, 'store'])->name('admin.tagihan.store');
    Route::post('/input-manual/simpan',[TagihanController::class, 'inputManual'])->name('admin.manualInput');
    Route::get('/riwayat-pembayaran', [HistoryController::class, 'index'])->name('admin.history');
    Route::post('/riwayat/toggle-status/{id}',[HistoryController::class, 'toggleTagihanStatus'])->name('admin.tagihan.toggle-status');
    Route::get('/riwayat/download-struk/{id}', [HistoryController::class, 'downloadStruk'])->name('admin.tagihan.download');
    Route::get('/riwayat/update-status/{id}', [HistoryController::class, 'updateStatus'])->name('admin.history.update-status');

});
