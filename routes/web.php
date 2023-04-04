<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\LoginController;
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

Route::get('/', [LoginController::class, 'login'])->name('root');
Route::get('/register', [LoginController::class, 'register']);
Route::post('/login_aksi', [LoginController::class, 'login_aksi']);
Route::post('/register_aksi', [LoginController::class, 'register_aksi']);
Route::get('/logout', [LoginController::class, 'logout']);

// Route::get('/cron-0', [DatabaseController::class, 'cron_0']);
// Route::get('/cron-1', [DatabaseController::class, 'cron_1']);
// Route::get('/cron-2', [DatabaseController::class, 'cron_2']);
// Route::get('/cron-3', [DatabaseController::class, 'cron_3']);
// Route::get('/cron-4', [DatabaseController::class, 'cron_4']);
// Route::get('/cron-5', [DatabaseController::class, 'cron_5']);
// Route::get('/cron-6', [DatabaseController::class, 'cron_6']);
// Route::get('/cron-7', [DatabaseController::class, 'cron_7']);

Route::group(['middleware' => 'auth'], function () {
  Route::get('/dashboard', [DashboardController::class, 'dashboard']);

  Route::get('/dashboard/user', [DashboardController::class, 'user']);
  Route::get('/dashboard/user_tambah', [DashboardController::class, 'user_tambah']);
  Route::post('/dashboard/user_aksi', [DashboardController::class, 'user_aksi']);
  Route::get('/dashboard/user_edit/{id}', [DashboardController::class, 'user_edit']);
  Route::post('/dashboard/user_update', [DashboardController::class, 'user_update']);
  Route::get('/dashboard/user_hapus/{id}', [DashboardController::class, 'user_hapus']);

  Route::get('/dashboard/kategori', [DashboardController::class, 'kategori']);
  Route::get('/dashboard/kategori_view/{id}', [DashboardController::class, 'kategori_view']);
  Route::get('/dashboard/kategori_tambah', [DashboardController::class, 'kategori_tambah']);
  Route::post('/dashboard/kategori_aksi', [DashboardController::class, 'kategori_aksi']);
  Route::get('/dashboard/kategori_edit/{id}', [DashboardController::class, 'kategori_edit']);
  Route::post('/dashboard/kategori_update', [DashboardController::class, 'kategori_update']);
  Route::get('/dashboard/kategori_hapus/{id}', [DashboardController::class, 'kategori_hapus']);

  Route::get('/dashboard/proyek', [DashboardController::class, 'proyek']);
  Route::get('/dashboard/proyek_tambah', [DashboardController::class, 'proyek_tambah']);
  Route::post('/dashboard/proyek_aksi', [DashboardController::class, 'proyek_aksi']);
  Route::get('/dashboard/proyek_edit/{id}', [DashboardController::class, 'proyek_edit']);
  Route::post('/dashboard/proyek_update', [DashboardController::class, 'proyek_update']);
  Route::get('/dashboard/proyek_hapus/{id}', [DashboardController::class, 'proyek_hapus']);

  Route::get('/dashboard/bukukas_search', [DashboardController::class, 'bukukas_search']);
  Route::get('/dashboard/invoice_search', [DashboardController::class, 'invoice_search']);
  Route::get('/dashboard/proyek_search', [DashboardController::class, 'proyek_search']);

  Route::post('/dashboard/filter', [DashboardController::class, 'filter']);
  Route::get('/dashboard/bukukas_sort/{sort}', [DashboardController::class, 'bukukas_sort']);
  Route::get('/dashboard/bukukas_refresh/{link}', [DashboardController::class, 'bukukas_refresh']);

  Route::get('/dashboard/bukukas', [DashboardController::class, 'bukukas']);
  Route::get('/dashboard/bukukas_tambah', [DashboardController::class, 'bukukas_tambah']);
  Route::post('/dashboard/bukukas_aksi', [DashboardController::class, 'bukukas_aksi']);
  Route::get('/dashboard/bukukas_edit/{id}', [DashboardController::class, 'bukukas_edit']);
  Route::post('/dashboard/bukukas_update', [DashboardController::class, 'bukukas_update']);
  Route::get('/dashboard/bukukas_hapus/{id}', [DashboardController::class, 'bukukas_hapus']);

  Route::get('/dashboard/ambil_stok', [DashboardController::class, 'ambil_stok']);
  Route::post('/dashboard/ambil_stok_aksi', [DashboardController::class, 'ambil_stok_aksi']);
  Route::get('/dashboard/ambil_stok_edit/{id}', [DashboardController::class, 'ambil_stok_edit']);
  Route::post('/dashboard/ambil_stok_update', [DashboardController::class, 'ambil_stok_update']);
  Route::get('/dashboard/ambil_stok_hapus/{id}', [DashboardController::class, 'ambil_stok_hapus']);

  Route::get('/dashboard/invoice', [DashboardController::class, 'invoice']);
  Route::get('/dashboard/invoice_tambah', [DashboardController::class, 'invoice_tambah']);
  Route::post('/dashboard/invoice_aksi', [DashboardController::class, 'invoice_aksi']);
  Route::get('/dashboard/invoice_edit/{id}', [DashboardController::class, 'invoice_edit']);
  Route::post('/dashboard/invoice_update', [DashboardController::class, 'invoice_update']);
  Route::get('/dashboard/invoice_hapus/{id}', [DashboardController::class, 'invoice_hapus']);

  Route::get('/dashboard/invoice_cetak/{id}', [DashboardController::class, 'invoice_cetak']);

  Route::get('/dashboard/pajak', [DashboardController::class, 'pajak']);
  Route::post('/dashboard/pajak_aksi', [DashboardController::class, 'pajak_aksi']);

  Route::get('/dashboard/stok', [DashboardController::class, 'stok']);
  Route::get('/dashboard/stok_tambah', [DashboardController::class, 'stok_tambah']);
  Route::post('/dashboard/stok_aksi', [DashboardController::class, 'stok_aksi']);
  Route::get('/dashboard/stok_edit/{id}', [DashboardController::class, 'stok_edit']);
  Route::post('/dashboard/stok_update', [DashboardController::class, 'stok_update']);
  Route::get('/dashboard/stok_hapus/{id}', [DashboardController::class, 'stok_hapus']);

  Route::get('/dashboard/export', [DashboardController::class, 'export']);

  Route::get('/dashboard/tukang_borongan', [GajiController::class, 'tukang_borongan']);
  Route::get('/dashboard/tukang_borongan_tambah', [GajiController::class, 'tukang_borongan_tambah']);
  Route::get('/dashboard/tukang_borongan_edit/{id}', [GajiController::class, 'tukang_borongan_edit']);
  Route::get('/dashboard/tukang_borongan_hapus/{id}', [GajiController::class, 'tukang_borongan_hapus']);
  Route::post('/dashboard/tukang_borongan_aksi', [GajiController::class, 'tukang_borongan_aksi']);
  Route::post('/dashboard/tukang_borongan_update', [GajiController::class, 'tukang_borongan_update']);

  Route::get('/dashboard/tukang_borongan_cetak/{id}', [GajiController::class, 'tukang_borongan_cetak']);
  Route::get('/dashboard/tukang_borongan_ekspor/{id}', [GajiController::class, 'tukang_borongan_ekspor']);

  Route::get('/dashboard/tukang_harian', [GajiController::class, 'tukang_harian']);
  Route::get('/dashboard/tukang_harian_tambah', [GajiController::class, 'tukang_harian_tambah']);
  Route::get('/dashboard/tukang_harian_edit/{id}', [GajiController::class, 'tukang_harian_edit']);
  Route::get('/dashboard/tukang_harian_approve/{id}', [GajiController::class, 'tukang_harian_approve']);
  Route::get('/dashboard/tukang_harian_disapprove/{id}', [GajiController::class, 'tukang_harian_disapprove']);
  Route::get('/dashboard/tukang_harian_hapus/{id}', [GajiController::class, 'tukang_harian_hapus']);
  Route::post('/dashboard/tukang_harian_aksi', [GajiController::class, 'tukang_harian_aksi']);
  Route::post('/dashboard/tukang_harian_update', [GajiController::class, 'tukang_harian_update']);

  Route::get('/dashboard/tukang_harian_cetak/{id}', [GajiController::class, 'tukang_harian_cetak']);
  Route::get('/dashboard/tukang_harian_ekspor/{id}', [GajiController::class, 'tukang_harian_ekspor']);

  Route::get('/dashboard/tukang_mandor', [GajiController::class, 'tukang_mandor']);
  Route::post('/dashboard/tukang_mandor_before', [GajiController::class, 'tukang_mandor_before']);
  Route::get('/dashboard/tukang_mandor_tambah/{id}/{date}/{ot?}', [GajiController::class, 'tukang_mandor_tambah']);
  Route::get('/dashboard/tukang_mandor_tambah_b/{id}/{date}', [GajiController::class, 'tukang_mandor_tambah_b']);
  Route::get('/dashboard/tukang_mandor_tambah_c/{id}/{date}', [GajiController::class, 'tukang_mandor_tambah_c']);
  Route::post('/dashboard/tukang_mandor_aksi_b', [GajiController::class, 'tukang_mandor_aksi_b']);
  Route::post('/dashboard/tukang_mandor_aksi_c', [GajiController::class, 'tukang_mandor_aksi_c']);
  Route::get('/dashboard/tukang_mandor_edit/{id}', [GajiController::class, 'tukang_mandor_edit']);
  Route::get('/dashboard/tukang_mandor_approve/{id}', [GajiController::class, 'tukang_mandor_approve']);
  Route::get('/dashboard/tukang_mandor_disapprove/{id}', [GajiController::class, 'tukang_mandor_disapprove']);
  Route::get('/dashboard/tukang_mandor_hapus/{id}', [GajiController::class, 'tukang_mandor_hapus']);
  Route::get('/dashboard/tukang_mandor_hapus_a/{id}', [GajiController::class, 'tukang_mandor_hapus_a']);
  Route::get('/dashboard/tukang_mandor_daftar_a/{id}', [GajiController::class, 'tukang_mandor_daftar_a']);
  Route::get('/dashboard/tukang_mandor_daftar_b/{id}/{ic}', [GajiController::class, 'tukang_mandor_daftar_b']);
  Route::post('/dashboard/tukang_mandor_aksi', [GajiController::class, 'tukang_mandor_aksi']);
  Route::post('/dashboard/tukang_mandor_aksi_a', [GajiController::class, 'tukang_mandor_aksi_a']);
  Route::post('/dashboard/tukang_mandor_update', [GajiController::class, 'tukang_mandor_update']);

  Route::get('/dashboard/tukang_mandor_cetak/{id}', [GajiController::class, 'tukang_mandor_cetak']);
  Route::get('/dashboard/tukang_mandor_ekspor/{id}', [GajiController::class, 'tukang_mandor_ekspor']);

  Route::get('/dashboard/pengaturan_tunjangan', [GajiController::class, 'pengaturan_tunjangan']);
  Route::get('/dashboard/pengaturan_tunjangan_tambah', [GajiController::class, 'pengaturan_tunjangan_tambah']);
  Route::get('/dashboard/pengaturan_tunjangan_approve/{id}/{aksi}', [GajiController::class, 'pengaturan_tunjangan_approve']);
  Route::get('/dashboard/pengaturan_tunjangan_edit/{id}', [GajiController::class, 'pengaturan_tunjangan_edit']);
  Route::get('/dashboard/pengaturan_tunjangan_hapus/{id}', [GajiController::class, 'pengaturan_tunjangan_hapus']);
  Route::post('/dashboard/pengaturan_tunjangan_aksi', [GajiController::class, 'pengaturan_tunjangan_aksi']);
  Route::post('/dashboard/pengaturan_tunjangan_update', [GajiController::class, 'pengaturan_tunjangan_update']);

  Route::get('/dashboard/daftar_tukang', [GajiController::class, 'daftar_tukang']);
  Route::get('/dashboard/daftar_tukang_tambah', [GajiController::class, 'daftar_tukang_tambah']);
  Route::get('/dashboard/daftar_tukang_edit/{id}', [GajiController::class, 'daftar_tukang_edit']);
  Route::get('/dashboard/daftar_tukang_hapus/{id}', [GajiController::class, 'daftar_tukang_hapus']);
  Route::post('/dashboard/daftar_tukang_aksi', [GajiController::class, 'daftar_tukang_aksi']);
  Route::post('/dashboard/daftar_tukang_update', [GajiController::class, 'daftar_tukang_update']);

  Route::get('/dashboard/daftar_mandor', [GajiController::class, 'daftar_mandor']);
  Route::get('/dashboard/daftar_mandor_tambah', [GajiController::class, 'daftar_mandor_tambah']);
  Route::get('/dashboard/daftar_mandor_edit/{id}', [GajiController::class, 'daftar_mandor_edit']);
  Route::get('/dashboard/daftar_mandor_hapus/{id}', [GajiController::class, 'daftar_mandor_hapus']);
  Route::post('/dashboard/daftar_mandor_aksi', [GajiController::class, 'daftar_mandor_aksi']);
  Route::post('/dashboard/daftar_mandor_update', [GajiController::class, 'daftar_mandor_update']);

  Route::get('/dashboard/gaji_mandor', [GajiController::class, 'gaji_mandor']);
  Route::get('/dashboard/gaji_mandor_tambah', [GajiController::class, 'gaji_mandor_tambah']);
  Route::get('/dashboard/gaji_mandor_edit/{id}', [GajiController::class, 'gaji_mandor_edit']);
  Route::get('/dashboard/gaji_mandor_approve/{id}/{aksi}', [GajiController::class, 'gaji_mandor_approve']);
  Route::get('/dashboard/gaji_mandor_hapus/{id}', [GajiController::class, 'gaji_mandor_hapus']);
  Route::post('/dashboard/gaji_mandor_aksi', [GajiController::class, 'gaji_mandor_aksi']);
  Route::post('/dashboard/gaji_mandor_update', [GajiController::class, 'gaji_mandor_update']);
});
