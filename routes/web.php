<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UtamaControl;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ComboController;




Route::get('/', function () {
    return view('combo');
});
 
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
  
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
  
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});
  
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
 
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('products');
        Route::get('create', 'create')->name('products.create');
        Route::post('store', 'store')->name('products.store');
        Route::get('show/{id}', 'show')->name('products.show');
        Route::get('edit/{id}', 'edit')->name('products.edit');
        Route::put('edit/{id}', 'update')->name('products.update');
        Route::delete('destroy/{id}', 'destroy')->name('products.destroy');
    });
 
    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
});


Route::get('/utama', [UtamaControl::class, 'index'])->name('halaman_utama');
Route::post('/update-sales-data', [UtamaControl::class, 'updateSalesData'])->name('updateSalesData');
Route::get('/products/download-txt', [ProductController::class, 'downloadTxt'])->name('products.download.txt');
Route::get('/products/download-json', [ProductController::class, 'downloadJson'])->name('products.download.json');
Route::get('/products/download-xml', [ProductController::class, 'downloadXml'])->name('products.download.xml');
Route::get('/products/download/excel', function () {
    return Excel::download(new ProductsExport, 'products.xlsx');
})->name('products.download.excel');
Route::get('/product/logs', [ProductController::class, 'showLogs'])->name('products.logs');
//route combo
Route::get('/combo', [ComboController::class, 'index'])->name('combo');



