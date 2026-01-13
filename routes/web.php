<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\MetadataController;
use App\Http\Controllers\SecretLoginController;
use App\Http\Controllers\ClientLoginController;
use Illuminate\Support\Facades\Route;

// Language Switcher
Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Client Routes
Route::get('/', [ClientController::class, 'index'])->name('client.home');

// Authentication Links
Route::get('/login', [ClientLoginController::class, 'create'])->name('login');
Route::post('/login', [ClientLoginController::class, 'store'])->name('client.login.store');

Route::get('/topsecret/login', [SecretLoginController::class, 'create'])->name('agent.login');
Route::post('/topsecret/store', [SecretLoginController::class, 'store'])->name('agent.login.store');

Route::post('/logout', [SecretLoginController::class, 'destroy'])->name('logout');

use App\Http\Controllers\RootLoginController;
use App\Http\Controllers\Root\UserController;

// Root Authentication
Route::get('/root/login', [RootLoginController::class, 'create'])->name('root.login');
Route::post('/root/login', [RootLoginController::class, 'store'])->name('root.login.store');

// Root System Routes (Protected by role:root)
Route::middleware(['auth', 'role:root'])->prefix('root')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('root.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('root.users.store');
    Route::post('/users/roles', [UserController::class, 'storeRole'])->name('root.users.roles.store');
    Route::delete('/users/roles/{id}', [UserController::class, 'removeRole'])->name('root.users.roles.remove');
    Route::post('/users/roles/{id}/tabs', [UserController::class, 'assignTabs'])->name('root.users.tabs');
});

// Agent/Admin Routes (Protected by role:admin)
Route::middleware(['auth', 'role:admin'])->prefix('topsecret')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Metadata Management
    Route::get('/metadata', [MetadataController::class, 'index'])->name('admin.metadata.index');
    Route::post('/metadata', [MetadataController::class, 'store'])->name('admin.metadata.store');
    Route::delete('/metadata/{metadata}', [MetadataController::class, 'destroy'])->name('admin.metadata.destroy');

    Route::post('/metadata-values', [MetadataController::class, 'storeValue'])->name('admin.metadata-values.store');
    Route::delete('/metadata-values/{value}', [MetadataController::class, 'destroyValue'])->name('admin.metadata-values.destroy');
});

// Protected Client Routes (Optional, protected by role:visitor)
Route::middleware(['auth', 'role:visitor'])->group(function () {
    // Thêm các route yêu cầu đăng nhập khách ở đây
});