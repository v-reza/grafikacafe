<?php

use App\Http\Controllers\Admin\Log\IndexController as AdminLogIndexController;
use App\Http\Controllers\Admin\User\IndexController as UserIndexController;
use App\Http\Controllers\Auth\IndexController;
use App\Http\Controllers\Global\Meja\IndexController as MejaIndexController;
use App\Http\Controllers\Kasir\Keranjang\IndexController as KeranjangIndexController;
use App\Http\Controllers\Kasir\PembayaranPegawai\IndexController as PembayaranPegawaiIndexController;
use App\Http\Controllers\Kasir\Produk\IndexController as ProdukIndexController;
use App\Http\Controllers\Kasir\Transaksi\IndexController as TransaksiIndexController;
use App\Http\Controllers\Manager\Log\IndexController as LogIndexController;
use App\Http\Controllers\Manager\Menu\IndexController as MenuIndexController;
use App\Http\Controllers\Manager\Pendapatan\IndexControler as PendapatanController;
use App\Http\Controllers\Manager\Transaksi\IndexController as ManagerTransaksiIndexController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::prefix('/manager')->group(function() {
        Route::controller(MenuIndexController::class)->group(function() {
            Route::get('/menu', 'render');
            Route::get('/menu/{id}', 'getById');
            Route::post('/menu', 'addMenu')->name('addmenu');
            Route::post('/delete/{id}', 'delete');
            Route::get('/editmenu/{id}', 'renderEdit');
            Route::post('/editmenu/{id}', 'editMenu')->name('editmenu');
        });
        Route::controller(ManagerTransaksiIndexController::class)->group(function() {
            Route::get('/transaksi', 'render');
            Route::get('/transaksi/all', 'renderAll');
            Route::get('/renderTransaksi/{id}', 'renderById');
            Route::post('/searchPegawai', 'searchPegawai');
            Route::post('/filterTgl', 'filterTgl');
        });
        Route::controller(PendapatanController::class)->group(function () {
            Route::get('/laporan', 'render');
            Route::get('/renderPendapatan', 'renderJson');
            Route::post('/filterHarian', 'filterHarian');
            Route::post('/filterBulanan', 'filterBulanan');
        });
        Route::controller(LogIndexController::class)->group(function () {
            Route::get('/log', 'render');
        });
    });

    Route::prefix('/admin')->group(function() {
        Route::controller(UserIndexController::class)->group(function() {
            Route::get('/user', 'render');
            Route::get('/hapusKasir/{id}', 'hapusKasir');
            Route::get('/hapusManager/{id}', 'hapusManager');
            Route::get('/getById/{id}', 'getById');
            Route::post('/updateUser', 'update');
            Route::post('/createUser/{key}', 'create');
        });
        Route::controller(AdminLogIndexController::class)->group(function () {
            Route::get('/log', 'render');
        });
    });

    Route::controller(IndexController::class)->group(function() {
        Route::post('/logout', 'destroySession');
    });

    Route::controller(ProdukIndexController::class)->group(function () {
        Route::get('/renderProduk', 'renderJson');
        Route::post('/addToCart', 'addToCart');
        Route::post('/deleteFromCart', 'deleteCart');
        Route::get('/filterKategori/{key}', 'filterKategori');
    });

    Route::controller(KeranjangIndexController::class)->group(function () {
        Route::get('/renderKeranjang', 'renderJson');
    });

    Route::controller(PembayaranPegawaiIndexController::class)->group(function() {
        Route::get('/renderTotalPembayaran', 'renderJson');
        Route::post('/addPesananModal', 'addPesanModal');
        Route::post('/kurangPesananModal', 'kurangPesanModal');
    });

    Route::controller(TransaksiIndexController::class)->group(function() {
        Route::get('/renderRiwayatTransaksi', 'renderJson');
        Route::post('/addTransaksi', 'addTransaksi');
    });

    Route::controller(MejaIndexController::class)->group(function() {
        Route::get('/renderMeja', 'renderJson');
    });


    Route::get('/', function () {
        return view('welcome');
    });
});


Route::middleware(['guest'])->controller(IndexController::class)->group(function () {
    Route::get('/login', 'render')->name('login');
    Route::post('/login', 'login');
});
