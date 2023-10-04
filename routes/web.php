<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

// Login submission for user & admin
Route::post('/login', [UserController::class, 'postLogin'])->name('login.post');

// Logout for user
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
// Logout for admin
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// Dashboard for user (using 'web' guard)
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Dashboard for admin (using 'admin' guard)
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboardadmin', [DashboardAdminController::class, 'index'])->name('dashboardadmin');
});

Route::middleware(['guest:admin'])->group(function () {
    Route::view('/login', 'user.login')->name('login');
    // Admin Authentication Routes
    Route::get('/register/admin', [AdminController::class, 'createAdmin'])->name('create.admin');
    Route::post('/register/admin', [AdminController::class, 'storeAdmin'])->name('store.admin');
});

Route::middleware(['guest:web'])->group(function () {
    Route::view('/login', 'user.login')->name('login');
    // User Authentication Routes
    Route::get('/register/user', [UserController::class, 'createUser'])->name('create.user');
    Route::post('/register/user', [UserController::class, 'storeUser'])->name('store.user');
});
