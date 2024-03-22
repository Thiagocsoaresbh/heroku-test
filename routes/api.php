<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\AdminController;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('/account', AccountController::class);
    Route::apiResource('/transactions', TransactionController::class);
    Route::get('/transactions/incomes', [TransactionController::class, 'incomes']);
    Route::get('/transactions/expenses', [TransactionController::class, 'expenses']);
    Route::post('/account/transfer', [AccountController::class, 'transfer'])->middleware('verify.balance');
    Route::get('/account/{account}/transactions', [TransactionController::class, 'index']);
    Route::apiResource('/checks', CheckController::class);
    Route::get('/checks/status/{status}', [CheckController::class, 'checksByStatus']);
    Route::post('/account/{account}/deposit', [AccountController::class, 'deposit']);
    Route::post('/account/{account}/withdraw', [AccountController::class, 'withdraw']);
    Route::get('/account/{account}/balance', [AccountController::class, 'getBalance'])->middleware('auth:sanctum');
    Route::get('/admin/checks', [AdminController::class, 'listChecks'])->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/approve', [AdminController::class, 'approveCheck'])->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/reject', [AdminController::class, 'rejectCheck'])->middleware('can:isAdmin');
});
