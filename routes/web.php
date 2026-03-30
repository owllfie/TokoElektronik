<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
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

Route::get('/stock', [StockController::class, 'index'])->name('stock');
Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
Route::put('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');

Route::get('/users', [UserController::class, 'index'])->name('users');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/items', [ItemController::class, 'index'])->name('items');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

Route::get('/types', [TypeController::class, 'index'])->name('types');
Route::post('/types', [TypeController::class, 'store'])->name('types.store');
Route::put('/types/{id}', [TypeController::class, 'update'])->name('types.update');
Route::delete('/types/{id}', [TypeController::class, 'destroy'])->name('types.destroy');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
