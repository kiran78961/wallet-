<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

Route::post('signup',[AuthController::class,'signup']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::apiresource('post',PostController::class)->middleware('auth:sanctum');
use App\Http\Controllers\Api\StudentController;

// Route::get('/students', function () {
//     return view('student');
// });

Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);
Route::put('/students/{id}', [StudentController::class, 'update']);
Route::delete('/students/{id}', [StudentController::class, 'destroy']);
Route::get('/students/{id}', [StudentController::class, 'show']);
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\transactionController;
Route::post('addmoney', [WalletController::class, 'addMoney'])->middleware('auth:sanctum');
Route::post('balance', [WalletController::class, 'balance'])->middleware('auth:sanctum');
Route::post('debitMoney', [WalletController::class, 'debitMoney'])->middleware('auth:sanctum');
Route::post('transactions', [transactionController::class, 'transactionHistory'])->middleware('auth:sanctum');

use App\Http\Controllers\AdminController;
Route::middleware(['auth:sanctum', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get(
            '/dashboard',
            [AdminController::class, 'dashboard']
        );

        Route::get(
            '/users',
            [AdminController::class, 'users']
        );

        Route::get(
            '/user/{userId}/wallet',
            [AdminController::class, 'wallet']
        );

        Route::post(
            '/user/{userId}/add-money',
            [AdminController::class, 'addMoney']
        );

        Route::post(
            '/user/{userId}/debit-money',
            [AdminController::class, 'debitMoney']
        );

        Route::get(
            '/transactions',
            [AdminController::class, 'transactions']
        );

    });

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp']);

Route::post('/reset-password', [AuthController::class, 'resetPassword']);