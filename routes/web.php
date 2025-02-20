<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login')->name('login.submit');
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register')->name('register.submit');
    });
});

// Authentication Required Routes
Route::middleware(['auth'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });

    // Admin Routes
    Route::middleware(['verified'])->prefix('')->group(function () {
        Route::controller(MainController::class)->group(function () {
            Route::get('/dashboard', 'showDashboard')->name('dashboard');
            //products
            Route::get('/product-list', 'showProduct')->name('product-list');
            Route::get('/product-details', 'showProductDetails')->name('product-details');
            Route::get('/create-product', 'newProduct')->name('new-product');
            Route::get('/edit-product', 'editProduct')->name('edit-product');

            //categories
             Route::get('/category', 'showCategory')->name('category');
             Route::get('/create-category', 'createCategory')->name('create-category');
             Route::get('/edit-category', 'editCategory')->name('edit-category');

             //invoices
               Route::get('/invoices', 'showInvoices')->name('invoices');
               Route::get('/create-invoices', 'CreateInvoices')->name('create-invoices');
               Route::get('/edit-invoices', 'EditInvoices')->name('edit-invoices');
               Route::get('/invoices-details', 'invoicesDetails')->name('invoices-details');
        
               //orders
                  Route::get('/orders', 'showOrders')->name('orders');
                  Route::get('/orders-details', 'showOrderDetails')->name('orders-details');
             
                  //profile
             Route::get('/profile', 'showProfile')->name('profile');
        });
    });
});