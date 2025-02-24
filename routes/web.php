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
            Route::get('/product-details/{id}', 'showProductDetails')->name('product-details');
            Route::get('/create-product', 'newProduct')->name('new-product');
            Route::post('/products',  'storeProduct')->name('products.store');
            Route::get('/edit-product/{id}', 'editProduct')->name('edit-product'); // Changed this line
            Route::put('/products/{id}',  'updateProduct')->name('products.update');
            Route::delete('/products/{id}', 'deleteProduct')->name('products.delete');
            

            //categories
            Route::get('/category', 'showCategory')->name('category');
            Route::get('/create-category', 'createCategory')->name('create-category');
            Route::post('/categories', 'storeCategory')->name('categories.store');
            Route::delete('/categories/{id}', 'deleteCategory')->name('categories.delete');
            Route::get('/edit-category/{id}', 'editCategory')->name('edit-category');
            Route::put('/categories/{id}', 'updateCategory')->name('categories.update');

            //invoices
            Route::get('/invoices', 'showInvoices')->name('invoices');
            Route::get('/create-invoices', 'CreateInvoices')->name('create-invoices');
            Route::get('/edit-invoices', 'EditInvoices')->name('edit-invoices');
            Route::get('/invoices-details', 'invoicesDetails')->name('invoices-details');
        
            //orders
            Route::get('/orders', 'showOrders')->name('orders');
            Route::get('/orders-details', 'showOrderDetails')->name('orders-details');
            Route::post('/orders', 'storeOrder')->name('orders.store');
            Route::get('/create-order', 'CreateOrders')->name('orders');
            Route::delete('/orders/{id}', 'deleteOrder')->name('orders.delete');
            Route::get('/edit-order/{id}', 'editOrder')->name('edit-order');
             
            //profile
            Route::get('/profile', 'showProfile')->name('profile');
        });
    });
});