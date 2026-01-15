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

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // MARC Framework Management
    Route::get('/marc-definitions', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'index'])->name('admin.marc.index');
    Route::post('/marc-definitions/tag', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'storeTag'])->name('admin.marc.tag.store');
    Route::post('/marc-definitions/subfield', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'storeSubfield'])->name('admin.marc.subfield.store');
    Route::put('/marc-definitions/tag/{tag}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'updateTag'])->name('admin.marc.tag.update');
    Route::put('/marc-definitions/subfield/{subfield}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'updateSubfield'])->name('admin.marc.subfield.update');
    Route::delete('/marc-definitions/tag/{tag}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'destroyTag'])->name('admin.marc.tag.destroy');
    Route::delete('/marc-definitions/subfield/{subfield}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'destroySubfield'])->name('admin.marc.subfield.destroy');

    // MARC Book Cataloging
    Route::get('/marc-books', [\App\Http\Controllers\Admin\MarcBookController::class, 'index'])->name('admin.marc.book');
    Route::get('/marc-books/create', [\App\Http\Controllers\Admin\MarcBookController::class, 'create'])->name('admin.marc.book.create');
    Route::get('/marc-books/{record}', [\App\Http\Controllers\Admin\MarcBookController::class, 'show'])->name('admin.marc.book.show');
    Route::get('/marc-books/{record}/edit', [\App\Http\Controllers\Admin\MarcBookController::class, 'edit'])->name('admin.marc.book.edit');
    Route::post('/marc-books', [\App\Http\Controllers\Admin\MarcBookController::class, 'store'])->name('admin.marc.book.store');
    Route::put('/marc-books/{record}', [\App\Http\Controllers\Admin\MarcBookController::class, 'update'])->name('admin.marc.book.update');
    Route::put('/marc-books/{record}/status', [\App\Http\Controllers\Admin\MarcBookController::class, 'updateStatus'])->name('admin.marc.book.status');

    // Distribution / Item Management
    Route::get('/marc-books/{record}/distribution', [\App\Http\Controllers\Admin\BookDistributionController::class, 'index'])->name('admin.marc.book.distribution');
    Route::post('/marc-books/{record}/distribution', [\App\Http\Controllers\Admin\BookDistributionController::class, 'store'])->name('admin.marc.book.distribution.store');
    Route::get('/distribution/check-barcode', [\App\Http\Controllers\Admin\BookDistributionController::class, 'checkBarcode'])->name('admin.marc.book.distribution.check');
});

// Protected Client Routes (Optional, protected by role:visitor)
Route::middleware(['auth', 'role:visitor'])->group(function () {
    // Thêm các route yêu cầu đăng nhập khách ở đây
});