<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/students', function () {
    return view('student');
});




Route::view('/register', 'register');

Route::view('/login', 'login');

Route::view('/dashboard', 'dashboard');

Route::view('/add-money', 'add-money');


use App\Http\Controllers\AdminPageController;

Route::get(
    '/admin/dashboard',
    [AdminPageController::class, 'dashboard']
)->name('admin.dashboard.page');

Route::get(
    '/admin/users',
    [AdminPageController::class, 'users']
)->name('admin.users.page');

Route::get(
    '/admin/user/{userId}/wallet',
    [AdminPageController::class, 'wallet']
)->name('admin.wallet.page');

Route::get(
    '/admin/transactions',
    [AdminPageController::class, 'transactions']
)->name('admin.transactions.page');

Route::get('/forgot-password', function () {
    return view('forgot-password');
});