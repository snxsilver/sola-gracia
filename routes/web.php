<?php

use App\Http\Controllers\DashboardController;
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

Route::get('/', [LoginController::class,'login']);
Route::post('/login_aksi', [LoginController::class,'login_aksi']);
Route::get('/logout', [LoginController::class,'logout']);

Route::get('/dashboard', [DashboardController::class,'dashboard']);

Route::get('/dashboard/user', [DashboardController::class,'user']);
Route::get('/dashboard/user_tambah', [DashboardController::class,'user_tambah']);
Route::post('/dashboard/user_aksi', [DashboardController::class,'user_aksi']);
Route::get('/dashboard/user_edit/{id}', [DashboardController::class,'user_edit']);
Route::post('/dashboard/user_update', [DashboardController::class,'user_update']);
Route::get('/dashboard/user_hapus/{id}', [DashboardController::class,'user_hapus']);

Route::get('/dashboard/kategori', [DashboardController::class,'kategori']);
Route::get('/dashboard/kategori_tambah', [DashboardController::class,'kategori_tambah']);
Route::post('/dashboard/kategori_aksi', [DashboardController::class,'kategori_aksi']);
Route::get('/dashboard/kategori_edit/{id}', [DashboardController::class,'kategori_edit']);
Route::post('/dashboard/kategori_update', [DashboardController::class,'kategori_update']);
Route::get('/dashboard/kategori_hapus/{id}', [DashboardController::class,'kategori_hapus']);

Route::get('/dashboard/proyek', [DashboardController::class,'proyek']);
Route::get('/dashboard/proyek_tambah', [DashboardController::class,'proyek_tambah']);
Route::post('/dashboard/proyek_aksi', [DashboardController::class,'proyek_aksi']);
Route::get('/dashboard/proyek_edit/{id}', [DashboardController::class,'proyek_edit']);
Route::post('/dashboard/proyek_update', [DashboardController::class,'proyek_update']);
Route::get('/dashboard/proyek_hapus/{id}', [DashboardController::class,'proyek_hapus']);

Route::get('/dashboard/bukukas', [DashboardController::class,'bukukas']);
Route::get('/dashboard/bukukas_tambah', [DashboardController::class,'bukukas_tambah']);
Route::post('/dashboard/bukukas_aksi', [DashboardController::class,'bukukas_aksi']);
Route::get('/dashboard/bukukas_edit/{id}', [DashboardController::class,'bukukas_edit']);
Route::post('/dashboard/bukukas_update', [DashboardController::class,'bukukas_update']);
Route::get('/dashboard/bukukas_hapus/{id}', [DashboardController::class,'bukukas_hapus']);

Route::get('/dashboard/ambil_stok', [DashboardController::class,'ambil_stok']);
Route::post('/dashboard/ambil_stok_aksi', [DashboardController::class,'ambil_stok_aksi']);

Route::get('/dashboard/invoice', [DashboardController::class,'invoice']);
Route::get('/dashboard/invoice_tambah', [DashboardController::class,'invoice_tambah']);
Route::post('/dashboard/invoice_aksi', [DashboardController::class,'invoice_aksi']);
Route::get('/dashboard/invoice_edit/{id}', [DashboardController::class,'invoice_edit']);
Route::post('/dashboard/invoice_update', [DashboardController::class,'invoice_update']);
Route::get('/dashboard/invoice_hapus/{id}', [DashboardController::class,'invoice_hapus']);

Route::get('/dashboard/stok', [DashboardController::class,'stok']);
Route::get('/dashboard/stok_tambah', [DashboardController::class,'stok_tambah']);
Route::post('/dashboard/stok_aksi', [DashboardController::class,'stok_aksi']);
Route::get('/dashboard/stok_edit/{id}', [DashboardController::class,'stok_edit']);
Route::post('/dashboard/stok_update', [DashboardController::class,'stok_update']);
Route::get('/dashboard/stok_hapus/{id}', [DashboardController::class,'stok_hapus']);