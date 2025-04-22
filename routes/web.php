<?php

use App\Http\Controllers\DetailSalesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalessController;
use App\Http\Controllers\UserController;
use App\Models\products;
use Database\Seeders\sales;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [UserController::class, 'loginpage'])->name('login.page');
    Route::post('/', [UserController::class, 'login'])->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/dashboard',[DetailSalesController::class, 'index'])->name('dashboard');
    Route::get('/product',[ProductsController::class, 'index'])->name('product');
    Route::get('/sales',[SalessController::class, 'index'])->name('sales');
    Route::get('/download/{id}', [DetailSalesController::class, 'downloadPDF'])->name('download');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
  
});


    Route::prefix('/product')->name('product.')->group(function () {
        Route::put('/edit-stock/{id}', [ProductsController::class, 'updateStock'])->name('stock');
        Route::get('/create', [ProductsController::class, 'create'])->name('create');
        Route::post('/store', [ProductsController::class, 'store'])->name('store');
        Route::delete('/{id}', [ProductsController::class, 'destroy'])->name('delete');
        Route::get('/edit/{id}', [ProductsController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [ProductsController::class, 'update'])->name('update');
        Route::get('/exportexcel', [ProductsController::class, 'exportexcel'])->name('exportexcel');
      
    });

    Route::prefix('/user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('list');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::get('/exportexcel', [UserController::class, 'export'])->name('export');
    });


    Route::prefix('/sales')->name('sales.')->group(function () {
        Route::get('/create',[SalessController::class, 'create'])->name('create');
        Route::post('/create/post',[SalessController::class, 'store'])->name('store');
        Route::post('/create/post/createsales',[SalessController::class, 'createsales'])->name('createsales');
        Route::get('/create/post',[SalessController::class, 'post'])->name('post');
        Route::get('/print/{id}',[DetailSalesController::class, 'show'])->name('print.show');
        Route::get('/create/member/{id}', [SalessController::class, 'createmember'])->name('create.member');
        Route::get('/exportexcel', [DetailSalesController::class, 'exportexcel'])->name('exportexcel');
        
    });




// Route::get('/sales/create',[SalessController::class, 'create'])->name('sales.create');
// Route::post('/sales/create/post',[SalessController::class, 'store'])->name('sales.store');
// Route::post('/sales/create/post/createsales',[SalessController::class, 'createsales'])->name('sales.createsales');
// Route::get('/sales/create/post',[SalessController::class, 'post'])->name('sales.post');
// Route::get('/sales/print/{id}',[DetailSalesController::class, 'show'])->name('sales.print.show');
// Route::get('/sale/create/member/{id}', [SalessController::class, 'createmember'])->name('sales.create.member');




// Route::fallback(function () {
//     return redirect()->route('dashboard');
// });
