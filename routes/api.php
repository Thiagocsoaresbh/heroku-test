<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\AdminController;

// Authentication
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::post('/logout', 'AuthController@logout')->middleware('auth:sanctum');

// Protected routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    // Accounts
    Route::apiResource('/accounts', 'AccountController');

    // Transactions
    Route::apiResource('/transactions', 'TransactionController');

    // Checks
    Route::apiResource('/checks', 'CheckController');

    // Especific operations
    Route::post('/accounts/{account}/deposit', 'AccountController@deposit');
    Route::post('/accounts/{account}/withdraw', 'AccountController@withdraw');
    Route::get('/accounts/{account}/balance', 'AccountController@balance');

    // Admins route
    Route::get('/admin/checks', 'AdminController@listChecks')->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/approve', 'AdminController@approveCheck')->middleware('can:isAdmin');
    Route::post('/admin/checks/{check}/reject', 'AdminController@rejectCheck')->middleware('can:isAdmin');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
