<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\AdminController;

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/sanctum/token', [AuthController::class, 'createToken']); // New route for creating token

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('/accounts', AccountController::class);
    Route::apiResource('/transactions', TransactionController::class);
<<<<<<< Updated upstream

    // Account Transfers
    Route::post('/accounts/transfer', [AccountController::class, 'transfer']);
=======
    Route::get('/transactions/incomes', [TransactionController::class, 'incomes']);
    Route::get('/transactions/expenses', [TransactionController::class, 'expenses']);
    Route::post('/accounts/transfer', [AccountController::class, 'transfer'])->middleware('verify.balance');
>>>>>>> Stashed changes
    Route::get('/accounts/{account}/transactions', [TransactionController::class, 'index']);
    Route::apiResource('/checks', CheckController::class);
<<<<<<< Updated upstream

    // Specific operations
    Route::post('/accounts/{account}/deposit', [AccountController::class, 'deposit'])->middleware('auth:sanctum');
=======
    Route::get('/checks/status/{status}', [CheckController::class, 'checksByStatus']);
    Route::post('/accounts/{account}/deposit', [AccountController::class, 'deposit']);
>>>>>>> Stashed changes
    Route::post('/accounts/deposit', [AccountController::class, 'deposit']);
    Route::post('/accounts/{account}/withdraw', [AccountController::class, 'withdraw']);
    Route::get('/accounts/{account}/balance', [AccountController::class, 'balance']);
    Route::get('/admin/checks', [AdminController::class, 'listChecks'])->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/approve', [AdminController::class, 'approveCheck'])->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/reject', [AdminController::class, 'rejectCheck'])->middleware('can:isAdmin');
});
