<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/report', function () {
    return view('report');
})->name('report');

Route::get('/stock', function () {
    return view('stock');
})->name('stock');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
