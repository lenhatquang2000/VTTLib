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
    Route::get('/users/privileges', [UserController::class, 'privileges'])->name('root.users.privileges');
    Route::get('/users/check-username', [UserController::class, 'checkUsername'])->name('root.users.check');
    Route::post('/users', [UserController::class, 'store'])->name('root.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('root.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('root.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('root.users.destroy');
    Route::post('/users/roles', [UserController::class, 'storeRole'])->name('root.users.roles.store');
    Route::delete('/users/roles/{id}', [UserController::class, 'removeRole'])->name('root.users.roles.remove');
    Route::post('/users/roles/{id}/tabs', [UserController::class, 'assignTabs'])->name('root.users.tabs');
    Route::post('/users/roles/{id}/sync-tabs', [UserController::class, 'syncTabs'])->name('root.users.tabs.sync');

    // Role Management
    Route::get('/roles', [\App\Http\Controllers\Root\RoleController::class, 'index'])->name('root.roles.index');
    Route::get('/roles/create', [\App\Http\Controllers\Root\RoleController::class, 'create'])->name('root.roles.create');
    Route::post('/roles', [\App\Http\Controllers\Root\RoleController::class, 'store'])->name('root.roles.store');
    Route::get('/roles/{role}/edit', [\App\Http\Controllers\Root\RoleController::class, 'edit'])->name('root.roles.edit');
    Route::put('/roles/{role}', [\App\Http\Controllers\Root\RoleController::class, 'update'])->name('root.roles.update');
    Route::delete('/roles/{role}', [\App\Http\Controllers\Root\RoleController::class, 'destroy'])->name('root.roles.destroy');

    // Patron Categories (Nhóm độc giả)
    Route::get('/patrons/groups', [\App\Http\Controllers\Root\PatronGroupController::class, 'index'])->name('root.patrons.groups.index');
    Route::post('/patrons/groups', [\App\Http\Controllers\Root\PatronGroupController::class, 'store'])->name('root.patrons.groups.store');
    Route::put('/patrons/groups/{patronGroup}', [\App\Http\Controllers\Root\PatronGroupController::class, 'update'])->name('root.patrons.groups.update');
    Route::delete('/patrons/groups/{patronGroup}', [\App\Http\Controllers\Root\PatronGroupController::class, 'destroy'])->name('root.patrons.groups.destroy');
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

    // Patron Management
    Route::get('/patrons', [\App\Http\Controllers\Admin\PatronController::class, 'index'])->name('admin.patrons.index');
    Route::get('/patrons/create', [\App\Http\Controllers\Admin\PatronController::class, 'create'])->name('admin.patrons.create');
    Route::post('/patrons', [\App\Http\Controllers\Admin\PatronController::class, 'store'])->name('admin.patrons.store');
    Route::patch('/patrons/{id}/toggle-status', [\App\Http\Controllers\Admin\PatronController::class, 'toggleStatus'])->name('admin.patrons.toggle-status');
    Route::patch('/patrons/{id}/renew', [\App\Http\Controllers\Admin\PatronController::class, 'renew'])->name('admin.patrons.renew');
    Route::delete('/patrons/{id}', [\App\Http\Controllers\Admin\PatronController::class, 'destroy'])->name('admin.patrons.destroy');

    // System Settings (topsecret)
    Route::get('/settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings/library', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updateLibraryInfo'])->name('admin.settings.library.update');
    Route::post('/settings/policy', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updatePolicy'])->name('admin.settings.policy.update');
    
    // Barcode Configs
    Route::post('/settings/barcode', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'storeBarcodeConfig'])->name('admin.settings.barcode.store');
    Route::put('/settings/barcode/{config}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updateBarcodeConfig'])->name('admin.settings.barcode.update');
    Route::delete('/settings/barcode/{config}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'deleteBarcodeConfig'])->name('admin.settings.barcode.destroy');

    // Branches & Locations
    Route::post('/settings/branches', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'storeBranch'])->name('admin.settings.branches.store');
    Route::put('/settings/branches/{branch}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updateBranch'])->name('admin.settings.branches.update');
    Route::delete('/settings/branches/{branch}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'deleteBranch'])->name('admin.settings.branches.destroy');
    
    Route::post('/settings/locations', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'storeLocation'])->name('admin.settings.locations.store');
    Route::put('/settings/locations/{location}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updateLocation'])->name('admin.settings.locations.update');
    Route::delete('/settings/locations/{location}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'deleteLocation'])->name('admin.settings.locations.destroy');

    // Supplier Management
    Route::post('/settings/suppliers', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'storeSupplier'])->name('admin.settings.suppliers.store');
    Route::put('/settings/suppliers/{supplier}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updateSupplier'])->name('admin.settings.suppliers.update');
    Route::delete('/settings/suppliers/{supplier}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'deleteSupplier'])->name('admin.settings.suppliers.destroy');

    // Circulation Management
    Route::get('/circulation', [\App\Http\Controllers\Admin\CirculationController::class, 'index'])->name('admin.circulation.index');
    
    // Patron Groups
    Route::post('/circulation/patron-groups', [\App\Http\Controllers\Admin\CirculationController::class, 'storePatronGroup'])->name('admin.circulation.patron-groups.store');
    Route::put('/circulation/patron-groups/{patronGroup}', [\App\Http\Controllers\Admin\CirculationController::class, 'updatePatronGroup'])->name('admin.circulation.patron-groups.update');
    Route::delete('/circulation/patron-groups/{patronGroup}', [\App\Http\Controllers\Admin\CirculationController::class, 'deletePatronGroup'])->name('admin.circulation.patron-groups.destroy');
    
    // Circulation Policies
    Route::post('/circulation/policies', [\App\Http\Controllers\Admin\CirculationController::class, 'storePolicy'])->name('admin.circulation.policies.store');
    Route::put('/circulation/policies/{policy}', [\App\Http\Controllers\Admin\CirculationController::class, 'updatePolicy'])->name('admin.circulation.policies.update');
    Route::delete('/circulation/policies/{policy}', [\App\Http\Controllers\Admin\CirculationController::class, 'deletePolicy'])->name('admin.circulation.policies.destroy');
    
    // Loan Operations
    Route::get('/circulation/loan-desk', [\App\Http\Controllers\Admin\CirculationController::class, 'loanDesk'])->name('admin.circulation.loan-desk');
    Route::post('/circulation/checkout', [\App\Http\Controllers\Admin\CirculationController::class, 'checkout'])->name('admin.circulation.checkout');
    Route::post('/circulation/checkin', [\App\Http\Controllers\Admin\CirculationController::class, 'checkin'])->name('admin.circulation.checkin');
    Route::post('/circulation/renew/{loan}', [\App\Http\Controllers\Admin\CirculationController::class, 'renew'])->name('admin.circulation.renew');
    
    // Fines
    Route::get('/circulation/fines', [\App\Http\Controllers\Admin\CirculationController::class, 'fines'])->name('admin.circulation.fines');
    Route::post('/circulation/fines/{fine}/pay', [\App\Http\Controllers\Admin\CirculationController::class, 'payFine'])->name('admin.circulation.fines.pay');
    Route::post('/circulation/fines/{fine}/waive', [\App\Http\Controllers\Admin\CirculationController::class, 'waiveFine'])->name('admin.circulation.fines.waive');

    // Document Types
    Route::get('/document-types', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'index'])->name('admin.document-types.index');
    Route::post('/document-types', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'store'])->name('admin.document-types.store');
    Route::put('/document-types/{documentType}', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'update'])->name('admin.document-types.update');
    Route::delete('/document-types/{documentType}', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'destroy'])->name('admin.document-types.destroy');
    Route::post('/document-types/order', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'updateOrder'])->name('admin.document-types.order');

    // Z39.50 Integration
    Route::get('/z3950', [\App\Http\Controllers\Admin\Z3950Controller::class, 'index'])->name('admin.z3950.index');
    Route::post('/z3950', [\App\Http\Controllers\Admin\Z3950Controller::class, 'store'])->name('admin.z3950.store');
    Route::put('/z3950/{server}', [\App\Http\Controllers\Admin\Z3950Controller::class, 'update'])->name('admin.z3950.update');
    Route::delete('/z3950/{server}', [\App\Http\Controllers\Admin\Z3950Controller::class, 'destroy'])->name('admin.z3950.destroy');
    Route::post('/z3950/{server}/test', [\App\Http\Controllers\Admin\Z3950Controller::class, 'testConnection'])->name('admin.z3950.test');
    Route::get('/z3950/search', [\App\Http\Controllers\Admin\Z3950Controller::class, 'search'])->name('admin.z3950.search');
    Route::post('/z3950/search', [\App\Http\Controllers\Admin\Z3950Controller::class, 'doSearch'])->name('admin.z3950.doSearch');
    Route::post('/z3950/import', [\App\Http\Controllers\Admin\Z3950Controller::class, 'import'])->name('admin.z3950.import');

    // User Management (Admin)
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
});

// Protected Client Routes (Optional, protected by role:visitor)
Route::middleware(['auth', 'role:visitor'])->group(function () {
    // Thêm các route yêu cầu đăng nhập khách ở đây
});