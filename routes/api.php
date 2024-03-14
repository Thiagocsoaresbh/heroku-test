<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\AdminController;


// Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Protected routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    // Accounts
    Route::apiResource('/accounts', AccountController::class);

    // Transactions
    Route::apiResource('/transactions', TransactionController::class);

    // Account Transfers
    Route::post('/accounts/transfer', [AccountController::class, 'transfer']);
    Route::get('/accounts/{account}/transactions', [TransactionController::class, 'index']);

    // Checks
    Route::apiResource('/checks', CheckController::class);

    // Specific operations
    Route::post('/accounts/{account}/deposit', [AccountController::class, 'deposit'])->middleware('auth:sanctum');
    Route::post('/accounts/deposit', [AccountController::class, 'deposit']);
    Route::post('/accounts/{account}/withdraw', [AccountController::class, 'withdraw']);
    Route::get('/accounts/{account}/balance', [AccountController::class, 'balance']);

    // Admin routes
    Route::get('/admin/checks', [AdminController::class, 'listChecks'])->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/approve', [AdminController::class, 'approveCheck'])->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/reject', [AdminController::class, 'rejectCheck'])->middleware('can:isAdmin');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
