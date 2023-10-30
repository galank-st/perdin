<?php

use App\Http\Controllers\BidangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DinasController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\UserController;
use App\Models\Bidang;

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

//DINAS LUAR
Route::get('/dinas-luar', [DinasController::class, 'dinas_luar'])->name('dl');
Route::get('/dinas-luar/data', [DinasController::class, 'data_dl'])->name('dl.data');
Route::get('/dinas-luar/add', [DinasController::class, 'add_dl'])->name('dl.add');
Route::post('/dinas-luar/create', [DinasController::class, 'create_dl'])->name('dl.create');
Route::get('/dinas-luar/edit/{no_sp}', [DinasController::class, 'edit_dl'])->name('dl.edit');
Route::post('/dinas-luar/update', [DinasController::class, 'update_dl'])->name('dl.update');

//DINAS DALAM
Route::get('/dinas-dalam', [DinasController::class, 'dinas_dalam'])->name('dd');
Route::get('/dinas-dalam/data', [DinasController::class, 'data_dd'])->name('dd.data');
Route::get('/dinas-dalam/add', [DinasController::class, 'add_dd'])->name('dd.add');
Route::post('/dinas-dalam/create', [DinasController::class, 'create_dd'])->name('dd.create');
Route::get('/dinas-dalam/edit/{no_sp}', [DinasController::class, 'edit_dd'])->name('dd.edit');
Route::post('/dinas-dalam/update', [DinasController::class, 'update_dd'])->name('dd.update');

//PEGAWAI
Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai');
Route::get('/ajax/pegawai/data', [PegawaiController::class, 'data'])->name('pegawai.data');
Route::post('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
Route::post('/pegawai/update', [PegawaiController::class, 'update'])->name('pegawai.update');
Route::get('/ajax/pegawai/del/{id}', [PegawaiController::class, 'delete'])->name('pegawai.delete');

//BIDANG
Route::get('/bidang', [BidangController::class, 'index'])->name('bidang');
Route::post('/bidang/create', [BidangController::class, 'create'])->name('bidang.create');
Route::post('/bidang/update', [BidangController::class, 'update'])->name('bidang.update');
Route::get('/ajax/bidang/del/{id}', [BidangController::class, 'delete'])->name('bidang.delete');
Route::get('/bidang/data', [BidangController::class, 'data'])->name('bidang.data');


//JABATAN
Route::get('/jabatan', [JabatanController::class, 'index'])->name('jabatan');
Route::post('/jabatan/create', [JabatanController::class, 'create'])->name('jabatan.create');
Route::post('/jabatan/update', [JabatanController::class, 'update'])->name('jabatan.update');
Route::get('/ajax/jabatan/del/{id}', [JabatanController::class, 'delete'])->name('jabatan.delete');
Route::get('/jabatan/data', [JabatanController::class, 'data'])->name('jabatan.data');


Route::get('/ajax/cek-dinas/{pegawai_id}/{tgl}/{tgl_pulang}', [DinasController::class, 'cek_dinas'])->name('dinas.cek');
Route::get('/ajax/cek-dinas/{pegawai_id}/{tgl}/{tgl_pulang}/{no_sp}', [DinasController::class, 'cek_dinas2'])->name('dinas.cek2');
Route::get('/ajax/cek-sp/{no_sp}', [DinasController::class, 'cek_no_sp'])->name('no_sp.cek');
Route::get('/ajax/dinas-dalam/del/{no_sp}', [DinasController::class, 'delete_dd'])->name('dd.delete');
Route::get('/ajax/dinas-luar/del/{no_sp}', [DinasController::class, 'delete_dl'])->name('dl.delete');






//AJAX
Route::post('/ajax/user/create', [UserController::class, 'create'])->name('user.create');
Route::get('/ajax/user/cek/{cek}', [UserController::class, 'cek_username'])->name('user.cek');
Route::post('/ajax/user/delete/', [UserController::class, 'delete'])->name('user.delete');



});


