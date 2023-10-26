<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DinasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

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

Route::get('/login', [LoginController::class, 'view'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.auth');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');



Route::group(['middleware' => 'auth'], function () {

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/data-user', [UserController::class, 'index'])->name('user.data');
Route::get('/get-user', [UserController::class, 'get_user'])->name('user.get');

//DINAS
Route::get('/dinas-luar', [DinasController::class, 'dinas_luar'])->name('dl');
Route::get('/dinas-luar/data', [DinasController::class, 'data_dl'])->name('dl.data');
Route::get('/dinas-luar/add', [DinasController::class, 'add_dl'])->name('dl.add');
Route::post('/dinas-luar/create', [DinasController::class, 'create_dl'])->name('dl.create');
Route::get('/dinas-luar/edit/{no_sp}', [DinasController::class, 'edit_dl'])->name('dl.edit');
Route::post('/dinas-luar/update', [DinasController::class, 'update_dl'])->name('dl.update');


Route::get('/ajax/cek-dinas/{pegawai_id}/{tgl}/{tgl_pulang}', [DinasController::class, 'cek_dinas'])->name('dinas.cek');
Route::get('/ajax/cek-dinas/{pegawai_id}/{tgl}/{tgl_pulang}/{no_sp}', [DinasController::class, 'cek_dinas2'])->name('dinas.cek2');
Route::get('/ajax/cek-sp/{no_sp}', [DinasController::class, 'cek_no_sp'])->name('no_sp.cek');




//AJAX
Route::post('/ajax/user/create', [UserController::class, 'create'])->name('user.create');
Route::get('/ajax/user/cek/{cek}', [UserController::class, 'cek_username'])->name('user.cek');
Route::post('/ajax/user/delete/', [UserController::class, 'delete'])->name('user.delete');



});


