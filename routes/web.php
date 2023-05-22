<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
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

//AJAX
Route::post('/ajax/user/create', [UserController::class, 'create'])->name('user.create');
Route::get('/ajax/user/cek/{cek}', [UserController::class, 'cek_username'])->name('user.cek');
Route::post('/ajax/user/delete/', [UserController::class, 'delete'])->name('user.delete');



});


