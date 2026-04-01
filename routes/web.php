<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware('auth.session')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::middleware('role:2,3,4')->group(function () {
        Route::get('/report', [ReportController::class, 'index'])->name('report');
        Route::get('/report/print', [ReportController::class, 'print'])->name('report.print');
        Route::get('/report/pdf', [ReportController::class, 'pdf'])->name('report.pdf');
        Route::get('/report/excel', [ReportController::class, 'excel'])->name('report.excel');
    });

    Route::middleware('role:1,3,4')->group(function () {
        Route::get('/stock', [StockController::class, 'index'])->name('stock');
        Route::get('/stock/{type}/print', [StockController::class, 'print'])->whereIn('type', ['in', 'out'])->name('stock.print');
        Route::get('/stock/{type}/pdf', [StockController::class, 'pdf'])->whereIn('type', ['in', 'out'])->name('stock.pdf');
        Route::get('/stock/{type}/excel', [StockController::class, 'excel'])->whereIn('type', ['in', 'out'])->name('stock.excel');
        Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
        Route::put('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
        Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');

        Route::get('/items', [ItemController::class, 'index'])->name('items');
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

        Route::get('/types', [TypeController::class, 'index'])->name('types');
        Route::post('/types', [TypeController::class, 'store'])->name('types.store');
        Route::put('/types/{id}', [TypeController::class, 'update'])->name('types.update');
        Route::delete('/types/{id}', [TypeController::class, 'destroy'])->name('types.destroy');
    });

    Route::middleware('role:3,4')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
