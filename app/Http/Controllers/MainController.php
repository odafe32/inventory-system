<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    //

public function showDashboard(){

   return view('inventory.dashboard', [
            'meta_title' => 'Dashboard - Larkon | inventory management Services',
            'meta_desc' => 'A Management system that helps businesses keep track of their products...',
            'meta_image' => url('images/favicon.ico'),
        ]);
}

//products list
public function showProduct(){
    return view('inventory.product.product', [
        'meta_title' => 'Products - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}

public function showProductDetails(){
    return view('inventory.product.product-details', [
        'meta_title' => 'Products Details - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}

public function newProduct(){
    return view('inventory.product.create-product', [
        'meta_title' => 'Create Product - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}

public function EditProduct(){
    return view('inventory.product.edit-product', [
        'meta_title' => 'Edit Product - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}

//catogries

public function showCategory(){
    return view('inventory.category.category ', [
        'meta_title' => 'Category - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}

public function createCategory(){
    return view('inventory.category.create-category ', [
        'meta_title' => 'Create Category - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}
public function editCategory(){
    return view('inventory.category.edit-category ', [
        'meta_title' => 'Edit Category - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}

//invoicesz
public function showInvoices(){
    return view('inventory.invoices.invoices ', [
        'meta_title' => 'Invoices - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}
public function CreateInvoices(){
    return view('inventory.invoices.create-invoices ', [
        'meta_title' => 'Create Invoices - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}
public function EditInvoices(){
    return view('inventory.invoices.edit-invoices ', [
        'meta_title' => 'Edit Invoices - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}
public function invoicesDetails(){
    return view('inventory.invoices.invoices-details ', [
        'meta_title' => ' Invoices - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}

//orders

public function showOrders(){
    return view('inventory.orders.orders ', [
        'meta_title' => 'Orders - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}

public function showOrderDetails(){
    return view('inventory.orders.orders-details ', [
        'meta_title' => ' Orders Details - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}

//profile

public function showProfile(){
    return view('inventory.profile ', [
        'meta_title' => ' Profile - Larkon',
        'meta_desc' => 'A Management system that helps businesses keep track of their products...',
        'meta_image' => url('images/favicon.ico'),
    ]);
}
}