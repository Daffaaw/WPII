<?php

use App\Http\Controllers\BelajarController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/login',[LoginController::class,'index'])->name('login');

Route::get('/enkripsi',[BelajarController::class,'enkripsi'])->name('enkripsi');
Route::get('/enkripsi-detail/{params}',[BelajarController::class,'enkripsi_detail'])->name('enkripsi-detail');

Route::get('/forgot-password',[LoginController::class,'forgot_password'])->name('forgot-password');
Route::post('/forgot-password-action',[LoginController::class,'forgot_password_act'])->name('forgot-password-act');

Route::get('/validasi-forgot-password/{token}',[LoginController::class,'validasi_forgot_password'])->name('validasi-forgot-password');
Route::post('/validasi-forgot-password-act',[LoginController::class,'validasi_forgot_password_act'])->name('validasi-forgot-password-act');

Route::post('/login-proses',[LoginController::class,'login_proses'])->name('login-proses');
Route::get('/logout',[LoginController::class,'logout'])->name('logout');

Route::get('/register',[LoginController::class,'register'])->name('register');
Route::post('/register-proses',[LoginController::class,'register_proses'])->name('register-proses');

Route ::group(['prefix' => 'admin','middleware' =>['auth'], 'as' => 'admin.'], function(){
    Route::get('/dashboard',[HomeController::class,'dashboard'])->name('dashboard');

    Route::get('/user',[HomeController::class,'index'])->name('index');
    Route::get('/assets',[HomeController::class,'assets'])->name('user.assets');
    Route::get('/create',[HomeController::class,'create'])->name('user.create');
    Route::post('/store',[HomeController::class,'store'])->name('user.store');

    Route::get('/clientside',[DataTableController::class,'clientside'])->name('clientside');
    Route::get('/serverside',[DataTableController::class,'serverside'])->name('serverside');

    Route::get('/edit/{id}',[HomeController::class,'edit'])->name('user.edit');
    Route::get('/detail/{id}',[HomeController::class,'detail'])->name('user.detail');
    Route::put('/update/{id}',[HomeController::class,'update'])->name('user.update');
    Route::delete('/delete/{id}',[HomeController::class,'delete'])->name('user.delete');

    Route::group(['prefix' => 'belajar'], function(){
        Route::get('/cache',[BelajarController::class,'cache'])->name('cache');
        Route::get('/import',[BelajarController::class,'import'])->name('import');
        Route::post('/import-proses',[BelajarController::class,'import_proses'])->name('import-proses');
    });
});

