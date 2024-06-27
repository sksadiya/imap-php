<?php

use App\Http\Controllers\EmailsFetchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminLoginController;
use App\Http\Controllers\mailActions;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\mail;
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role(1)) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    }
    return redirect()->route('admin.login');
});

Route::get('/emails', [EmailsFetchController::class, 'showEmailTabs']);
Route::get('/folders', [EmailsFetchController::class, 'getFolders']);
Route::get('/folders/{folderName}/messages', [EmailsFetchController::class, 'getFolderMessages']);
Route::post('/email/action', [mailActions::class, 'handleEmailAction'])->name('mailAction');

Route::get('/gmail', [GmailController::class, 'index'])->name('gmail');

Route::get('/test-connection', [GmailController::class, 'testConnection']);

Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [AdminLoginController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    });
});