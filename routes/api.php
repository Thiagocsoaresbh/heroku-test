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

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', function (Request $request) {
        // Get the authenticated user and revoke the token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    });

    // Remove the 'account' parameter from routes where it's no longer needed
    Route::get('/account', [AccountController::class, 'index']);
    Route::post('/account', [AccountController::class, 'store']);
    Route::get('/account/balance', [AccountController::class, 'balance']);
    Route::post('/account/transfer', [AccountController::class, 'transfer'])->middleware('verify.balance:transfer');
    Route::get('/account/transactions', [TransactionController::class, 'index']);
    Route::get('/account/transactions/incomes', [TransactionController::class, 'incomes']);
    Route::get('/account/transactions/expenses', [TransactionController::class, 'expenses']);
    Route::post('/account/deposit', [AccountController::class, 'deposit']);
    Route::post('/account/withdraw', [AccountController::class, 'withdraw'])->middleware('verify.balance:withdraw');

    Route::apiResource('/checks', CheckController::class);
    Route::get('/checks/status/{status}', [CheckController::class, 'checksByStatus']);

    // Admin specific routes
    Route::get('/admin/checks', [AdminController::class, 'listChecks'])->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/approve', [AdminController::class, 'approveCheck'])->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/reject', [AdminController::class, 'rejectCheck'])->middleware('can:isAdmin');
});
