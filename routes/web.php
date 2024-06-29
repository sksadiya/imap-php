<?php

use App\Http\Controllers\EmailsFetchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminLoginController;
use App\Http\Controllers\mailActions;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\mail;
use App\Http\Controllers\menuItemController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role(1)) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    }
    return redirect()->route('admin.login');
});




Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [AdminLoginController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


        Route::post('/page/create', [AdminLoginController::class, 'store'])->name('page.create');
        Route::get('/page/edit/{id}', [AdminLoginController::class, 'edit'])->name('page.edit');
        Route::post('/page/update/{id}', [AdminLoginController::class, 'update'])->name('page.update');
        Route::get('/page/delete/{id}', [AdminLoginController::class, 'delete'])->name('page.delete');

        Route::get('/menus', [MenuController::class, 'list'])->name('menu.list');
        Route::post('/menu/create', [MenuController::class,  'store'])->name('menu.create');
        Route::get('/menu/edit/{id}', [MenuController::class,  'edit'])->name('menu.edit');
        Route::post('/menu/update/{id}', [MenuController::class,  'update'])->name('menu.update');
        Route::get('/menu/delete/{id}', [MenuController::class,  'delete'])->name('menu.delete');
        Route::get('/menu-settings', [MenuController::class,  'settings'])->name('menu.setting');

        Route::get('/emails', [EmailsFetchController::class, 'showEmailTabs']);
        Route::get('/folders', [EmailsFetchController::class, 'getFolders']);
        Route::get('/folders/{folderName}/messages', [EmailsFetchController::class, 'getFolderMessages']);
        Route::post('/email/action', [mailActions::class, 'handleEmailAction'])->name('mailAction');
        Route::post('/emails/send', [GmailController::class, 'send'])->name('emails.send');

        Route::post('/menu/add-pages', [MenuController::class, 'addPages'])->name('menu.addPages');
        Route::post('/menu/save-order', [MenuController::class, 'saveOrder'])->name('menu.saveOrder');
        Route::get('/welcome', function( )  {
            return view('welcome');
        });
        Route::post('/save-menu-items', [menuItemController::class, 'saveMenu'])->name('menu.save');
        // routes/web.php or routes/api.php

        Route::get('menu-items/{menuId}', [MenuItemController::class, 'getMenuItems'])->name('menu-items.get');
        
    });
});