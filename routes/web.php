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

Route::get('/dashboard', [DashboardController::class,'dashboard']);

Route::get('/dashboard/user', [DashboardController::class,'user']);
Route::get('/dashboard/user_tambah', [DashboardController::class,'user_tambah']);
Route::post('/dashboard/user_aksi', [DashboardController::class,'user_aksi']);
Route::get('/dashboard/user_edit/{id}', [DashboardController::class,'user_edit']);
Route::post('/dashboard/user_update', [DashboardController::class,'user_update']);
Route::get('/dashboard/user_hapus/{id}', [DashboardController::class,'user_hapus']);

Route::get('/user_edit', function () {
    return view('dashboard.user_edit');
});
