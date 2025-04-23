<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UtamaControl;
use App\Http\Controllers\AdminController;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ComboController;

// Rute publik
Route::get('/', function () {
    return view('combo');
});

// Rute autentikasi
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
  
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
  
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
    
    // Route untuk membuat admin (hanya untuk development)
    Route::get('create-admin', 'createAdmin');
});

// Routes yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Profile - semua user bisa akses
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    
    // Dashboard
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Products - semua user bisa akses dan melakukan CRUD
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('products');
        Route::get('show/{id}', 'show')->name('products.show');
        Route::get('create', 'create')->name('products.create');
        Route::post('store', 'store')->name('products.store');
        Route::get('edit/{id}', 'edit')->name('products.edit');
        Route::put('edit/{id}', 'update')->name('products.update');
        Route::delete('destroy/{id}', 'destroy')->name('products.destroy');
        
        // Download routes
        Route::get('/download-txt', 'downloadTxt')->name('products.download.txt');
        Route::get('/download-json', 'downloadJson')->name('products.download.json');
        Route::get('/download-xml', 'downloadXml')->name('products.download.xml');
        Route::get('/download/excel', function () {
            return Excel::download(new ProductsExport, 'products.xlsx');
        })->name('products.download.excel');
        Route::get('/logs', 'showLogs')->name('products.logs');
    });
    
    // Halaman Utama - hanya admin yang bisa akses
    Route::middleware('can:admin-access')->group(function() {
        Route::get('/utama', [UtamaControl::class, 'index'])->name('halaman_utama');
        Route::post('/update-sales-data', [UtamaControl::class, 'updateSalesData'])->name('updateSalesData');
    });
});

// Combo
Route::get('/combo', [ComboController::class, 'index'])->name('combo');