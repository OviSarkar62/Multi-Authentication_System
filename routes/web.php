<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;



Route::get('/', function () {
    return view('welcome');
});

// Auth submission for user & admin
Route::post('/login', [AuthController::class, 'postLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Dashboard for user (using 'web' guard)
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Dashboard for admin (using 'admin' guard)
Route::middleware(['auth:admin'])->group(function () {
    // Dashboard page for Admin
    Route::get('/dashboardadmin', [DashboardAdminController::class, 'index'])->name('dashboardadmin');
    //-------------------------------------------------------------------------------------------------------
    // Roles page for Admin
    Route::get('/roles', [RolesController::class, 'index'])->name('admin.roles');
    // Create Roles
    Route::get('/roles/create', [RolesController::class, 'create'])->name('create.roles');
    // Store Roles
    Route::post('/roles/create', [RolesController::class, 'store'])->name('store.roles');
    // Edit Roles
    Route::get('/roles/edit/{id}', [RolesController::class, 'edit'])->name('edit.roles');
    // Update Roles
    Route::put('/roles/{id}', [RolesController::class, 'update'])->name('update.roles');
    // Delete Roles
    Route::delete('/roles/{id}', [RolesController::class, 'destroy'])->name('delete.roles');
    //-------------------------------------------------------------------------------------------------------
    // Employee Creation 
    Route::get('/employee/create', [EmployeeController::class, 'createEmployee'])->name('create.employee');
    Route::post('/employee/create', [EmployeeController::class, 'storeEmployee'])->name('store.employee');
    // Employee List
    Route::get('/employee/index', [EmployeeController::class, 'index'])->name('employee.index');
    // Employee Edit
    Route::get('/employee/edit/{id}', [EmployeeController::class,'editEmployee'])->name('edit.employee');
    // Employee Update
    Route::put('/employee/{id}', [EmployeeController::class, 'updateEmployee'])->name('update.employee');
    // Employee Delete
    Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('delete.employee');
    //-------------------------------------------------------------------------------------------------------
    // User List 
    Route::get('/user/index', [UserController::class, 'userIndex'])->name('user.index');
    // User Delete
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('delete.user');
    //-------------------------------------------------------------------------------------------------------
    // Product creation
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/delete-attribute/{attributeIndex}', [ProductController::class,'deleteAttribute'])->name('products.deleteAttribute');
    //--------------------------------------Order---------------------------------------------------------------------------------------------
    // Order List
    Route::get('/order/index', [OrderController::class, 'orderIndex'])->name('order.index');
    // Create Order
    Route::get('/order/create', [OrderController::class, 'createOrder'])->name('create.order');
    // Store Order
    Route::post('/order/create', [OrderController::class, 'storeOrder'])->name('store.order');
    // Edit Order
    Route::get('/order/edit/{id}', [OrderController::class, 'editOrder'])->name('edit.order');
    // Update Order
    Route::put('/order/{id}', [OrderController::class, 'updateOrder'])->name('update.order');
    // Delete Order
    Route::delete('/order/{id}', [OrderController::class, 'destroyOrder'])->name('delete.order');
    //------------------------------------------Transaction-------------------------------------------------------------
    // Transaction List 
    Route::get('/transaction/index', [TransactionController::class, 'transactionIndex'])->name('transaction.index');
    // Create Transaction
    Route::get('/transaction/create', [TransactionController::class, 'createTransaction'])->name('create.transaction');
    // Store Transaction
    Route::post('/transaction/create', [TransactionController::class, 'storeTransaction'])->name('store.transaction');
    // Edit Transaction
    Route::get('/transaction/edit/{id}', [TransactionController::class, 'editTransaction'])->name('edit.transaction');
    // Update Transaction
    Route::put('/transaction/{id}', [TransactionController::class, 'updateTransaction'])->name('update.transaction');
    // Delete Transaction
    Route::delete('/transaction/{id}', [TransactionController::class, 'destroyTransaction'])->name('delete.transaction');
});

Route::middleware(['guest:web'])->group(function () {
    Route::view('/login', 'user.login')->name('login');
    // User Authentication Routes
    Route::get('/register/user', [UserController::class, 'createUser'])->name('create.user');
    Route::post('/register/user', [UserController::class, 'storeUser'])->name('store.user');
});

Route::middleware(['guest:admin'])->group(function () {
    Route::view('/login', 'user.login')->name('login'); 
    // Admin Authentication Routes
    Route::get('/register/admin', [AdminController::class, 'createAdmin'])->name('create.admin');
    Route::post('/register/admin', [AdminController::class, 'storeAdmin'])->name('store.admin');
});

Route::group(['prefix' => 'admin'], function () {
    Route::resource('roles', 'RolesController', ['names' => 'admin.roless']);
    Route::resource('admins', 'AdminController', ['names' => 'admin.admins']);
});