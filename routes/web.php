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

// Barcode Generation (Public access for image display)
Route::get('/barcode/{code}', [\App\Http\Controllers\Admin\BarcodeController::class, 'show'])->name('admin.barcode.show');

// Public Website Routes
Route::get('/', [\App\Http\Controllers\SiteController::class, 'home'])->name('home');
Route::get('/page/{code}', [\App\Http\Controllers\SiteController::class, 'page'])->name('site.page');
Route::get('/sitemap', [\App\Http\Controllers\SiteController::class, 'sitemap'])->name('site.sitemap');
Route::get('/sitemap.xml', [\App\Http\Controllers\SiteController::class, 'xmlSitemap'])->name('site.sitemap.xml');

// Public News Routes
Route::prefix('tin-tuc')->name('news.')->group(function () {
    Route::get('/', [\App\Http\Controllers\NewsController::class, 'index'])->name('index');
    Route::get('/{slug}', [\App\Http\Controllers\NewsController::class, 'show'])->name('show');
    Route::get('/chuyen-muc/{slug}', [\App\Http\Controllers\NewsController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [\App\Http\Controllers\NewsController::class, 'tag'])->name('tag');
    Route::get('/noi-bat', [\App\Http\Controllers\NewsController::class, 'featured'])->name('featured');
    Route::get('/tim-kiem', [\App\Http\Controllers\NewsController::class, 'search'])->name('search');
    Route::get('/rss', [\App\Http\Controllers\NewsController::class, 'rss'])->name('rss');
    Route::get('/sitemap.xml', [\App\Http\Controllers\NewsController::class, 'sitemap'])->name('sitemap');
    Route::get('/api', [\App\Http\Controllers\NewsController::class, 'api'])->name('api');
    Route::post('/{news}/like', [\App\Http\Controllers\NewsController::class, 'like'])->name('like');
});

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PatronGroupController;
use App\Http\Controllers\Admin\ActivityLogController;

// Admin Panel Routes (Consolidated for all staff levels)
Route::middleware(['auth', 'role:admin'])->prefix('topsecret')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Statistics
    Route::get('/statistics', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('admin.statistics.index');

    // Site Management
    Route::prefix('site-nodes')->name('admin.site-nodes.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SiteNodeController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\SiteNodeController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\SiteNodeController::class, 'store'])->name('store');
        Route::get('/{siteNode}/edit', [\App\Http\Controllers\Admin\SiteNodeController::class, 'edit'])->name('edit');
        Route::get('/{siteNode}', function (\App\Models\SiteNode $siteNode) {
            return redirect()->route('admin.site-nodes.edit', $siteNode);
        })->name('show');
        Route::put('/{siteNode}', [\App\Http\Controllers\Admin\SiteNodeController::class, 'update'])->name('update');
        Route::delete('/{siteNode}', [\App\Http\Controllers\Admin\SiteNodeController::class, 'destroy'])->name('destroy');
        Route::post('/{siteNode}/toggle-status', [\App\Http\Controllers\Admin\SiteNodeController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/update-order', [\App\Http\Controllers\Admin\SiteNodeController::class, 'updateOrder'])->name('update-order');
        
        // Tree Structure Management Routes
        Route::get('/tree', [\App\Http\Controllers\Admin\SiteNodeController::class, 'tree'])->name('tree');
        Route::post('/tree/move', [\App\Http\Controllers\Admin\SiteNodeController::class, 'moveNode'])->name('tree.move');
        Route::post('/tree/rebuild', [\App\Http\Controllers\Admin\SiteNodeController::class, 'rebuildTree'])->name('tree.rebuild');
        Route::get('/tree/json', [\App\Http\Controllers\Admin\SiteNodeController::class, 'treeJson'])->name('tree.json');
        Route::post('/{siteNode}/duplicate', [\App\Http\Controllers\Admin\SiteNodeController::class, 'duplicate'])->name('duplicate');
        Route::post('/bulk-action', [\App\Http\Controllers\Admin\SiteNodeController::class, 'bulkAction'])->name('bulk-action');
        
        // Page Builder Routes
        Route::get('/{siteNode}/page-builder', [\App\Http\Controllers\Admin\PageBuilderController::class, 'edit'])->name('page-builder');
        Route::put('/{siteNode}/page-builder', [\App\Http\Controllers\Admin\PageBuilderController::class, 'update'])->name('page-builder.update');
    });

    // Media Categories Management
    Route::resource('media-categories', \App\Http\Controllers\Admin\MediaCategoryController::class)->names('admin.media-categories');
    Route::resource('media-items', \App\Http\Controllers\Admin\MediaItemController::class)->names('admin.media-items');

    // Digital Documents Management
    Route::resource('digital-categories', \App\Http\Controllers\Admin\DigitalCategoryController::class)->names('admin.digital-categories')->except(['show']);
    Route::resource('digital-documents', \App\Http\Controllers\Admin\DigitalDocumentController::class)->names('admin.digital-documents')->except(['show']);

    // News Management
    Route::prefix('news')->name('admin.news.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\NewsController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\NewsController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\NewsController::class, 'store'])->name('store');
        Route::get('/{news}', [\App\Http\Controllers\Admin\NewsController::class, 'show'])->name('show');
        Route::get('/{news}/edit', [\App\Http\Controllers\Admin\NewsController::class, 'edit'])->name('edit');
        Route::put('/{news}', [\App\Http\Controllers\Admin\NewsController::class, 'update'])->name('update');
        Route::delete('/{news}', [\App\Http\Controllers\Admin\NewsController::class, 'destroy'])->name('destroy');
        
        // AJAX Routes
        Route::post('/{news}/publish', [\App\Http\Controllers\Admin\NewsController::class, 'publish'])->name('publish');
        Route::post('/{news}/archive', [\App\Http\Controllers\Admin\NewsController::class, 'archive'])->name('archive');
        Route::post('/{news}/toggle-featured', [\App\Http\Controllers\Admin\NewsController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/bulk-action', [\App\Http\Controllers\Admin\NewsController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/statistics', [\App\Http\Controllers\Admin\NewsController::class, 'statistics'])->name('statistics');
    });

    // News Categories Management
    Route::prefix('news-categories')->name('admin.news-categories.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'store'])->name('store');
        Route::get('/{newsCategory}', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'show'])->name('show');
        Route::get('/{newsCategory}/edit', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'edit'])->name('edit');
        Route::put('/{newsCategory}', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'update'])->name('update');
        Route::delete('/{newsCategory}', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'destroy'])->name('destroy');
        
        // AJAX Routes
        Route::post('/{newsCategory}/toggle-status', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/update-order', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'updateOrder'])->name('update-order');
        Route::get('/json', [\App\Http\Controllers\Admin\NewsCategoryController::class, 'json'])->name('json');
    });

    // News Tags Management
    Route::prefix('news-tags')->name('admin.news-tags.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\NewsTagController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\NewsTagController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\NewsTagController::class, 'store'])->name('store');
        Route::get('/{newsTag}', [\App\Http\Controllers\Admin\NewsTagController::class, 'show'])->name('show');
        Route::get('/{newsTag}/edit', [\App\Http\Controllers\Admin\NewsTagController::class, 'edit'])->name('edit');
        Route::put('/{newsTag}', [\App\Http\Controllers\Admin\NewsTagController::class, 'update'])->name('update');
        Route::delete('/{newsTag}', [\App\Http\Controllers\Admin\NewsTagController::class, 'destroy'])->name('destroy');
        
        // AJAX Routes
        Route::post('/{newsTag}/toggle-status', [\App\Http\Controllers\Admin\NewsTagController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/json', [\App\Http\Controllers\Admin\NewsTagController::class, 'json'])->name('json');
        Route::get('/popular', [\App\Http\Controllers\Admin\NewsTagController::class, 'popular'])->name('popular');
        Route::post('/merge', [\App\Http\Controllers\Admin\NewsTagController::class, 'merge'])->name('merge');
        Route::post('/cleanup', [\App\Http\Controllers\Admin\NewsTagController::class, 'cleanup'])->name('cleanup');
        Route::post('/bulk-action', [\App\Http\Controllers\Admin\NewsTagController::class, 'bulkAction'])->name('bulk-action');
    });

    // Identity and Privilege Management
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/privileges', [UserController::class, 'privileges'])->name('admin.users.privileges');
        Route::get('/check-username', [UserController::class, 'checkUsername'])->name('admin.users.check');
        Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::post('/roles', [UserController::class, 'storeRole'])->name('admin.users.roles.store');
        Route::delete('/roles/{id}', [UserController::class, 'removeRole'])->name('admin.users.roles.remove');
        Route::post('/roles/{id}/tabs', [UserController::class, 'assignTabs'])->name('admin.users.tabs');
        Route::post('/roles/{id}/sync-tabs', [UserController::class, 'syncTabs'])->name('admin.users.tabs.sync');
    });

    // Role Management (Security Clearance Templates)
    Route::resource('roles', RoleController::class)->names([
        'index' => 'admin.roles.index',
        'create' => 'admin.roles.create',
        'store' => 'admin.roles.store',
        'edit' => 'admin.roles.edit',
        'update' => 'admin.roles.update',
        'destroy' => 'admin.roles.destroy',
    ]);

    // Activity Monitoring
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
    Route::get('/activity-logs/{log}', [ActivityLogController::class, 'show'])->name('admin.activity-logs.show');

    // Metadata Configuration (MARC Frameworks & Definitions)
    Route::get('/marc-definitions', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'index'])->name('admin.marc.index');
    Route::post('/marc-definitions/framework', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'storeFramework'])->name('admin.marc.framework.store');
    Route::put('/marc-definitions/framework/{framework}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'updateFramework'])->name('admin.marc.framework.update');
    Route::delete('/marc-definitions/framework/{framework}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'destroyFramework'])->name('admin.marc.framework.destroy');

    Route::post('/marc-definitions/tag', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'storeTag'])->name('admin.marc.tag.store');
    Route::post('/marc-definitions/subfield', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'storeSubfield'])->name('admin.marc.subfield.store');
    Route::put('/marc-definitions/tag/{tag}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'updateTag'])->name('admin.marc.tag.update');
    Route::put('/marc-definitions/subfield/{subfield}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'updateSubfield'])->name('admin.marc.subfield.update');
    Route::delete('/marc-definitions/tag/{tag}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'destroyTag'])->name('admin.marc.tag.destroy');
    Route::delete('/marc-definitions/subfield/{subfield}', [\App\Http\Controllers\Admin\MarcDefinitionController::class, 'destroySubfield'])->name('admin.marc.subfield.destroy');

    Route::get('/marc-books', [\App\Http\Controllers\Admin\MarcBookController::class, 'index'])->name('admin.marc.book');
    Route::get('/marc-books/form/{record?}', [\App\Http\Controllers\Admin\MarcBookController::class, 'form'])
        ->whereNumber('record')
        ->name('admin.marc.book.form');
    Route::get('/marc-books/{record}', [\App\Http\Controllers\Admin\MarcBookController::class, 'show'])->name('admin.marc.book.show');
    Route::post('/marc-books', [\App\Http\Controllers\Admin\MarcBookController::class, 'store'])->name('admin.marc.book.store');
    Route::put('/marc-books/{record}', [\App\Http\Controllers\Admin\MarcBookController::class, 'update'])->name('admin.marc.book.update');
    Route::put('/marc-books/{record}/status', [\App\Http\Controllers\Admin\MarcBookController::class, 'updateStatus'])->name('admin.marc.book.status');
    Route::delete('/marc-books/{record}', [\App\Http\Controllers\Admin\MarcBookController::class, 'destroy'])->name('admin.marc.book.destroy');

    // MARC Import & Export
    Route::get('/marc-import', [\App\Http\Controllers\Admin\MarcImportController::class, 'index'])->name('admin.marc.import.index');
    Route::get('/marc-import/template', [\App\Http\Controllers\Admin\MarcImportController::class, 'downloadTemplate'])->name('admin.marc.import.template');
    Route::post('/marc-import/upload', [\App\Http\Controllers\Admin\MarcImportController::class, 'upload'])->name('admin.marc.import.upload');
    
    // MARC Export
    Route::get('/marc-export', [\App\Http\Controllers\Admin\MarcBookController::class, 'exportIndex'])->name('admin.marc.export.index');
    Route::get('/marc-export/download', [\App\Http\Controllers\Admin\MarcBookController::class, 'export'])->name('admin.marc.export.download');
    Route::post('/marc-import/process', [\App\Http\Controllers\Admin\MarcImportController::class, 'process'])->name('admin.marc.import.process');
    Route::post('/marc-import/create-framework', [\App\Http\Controllers\Admin\MarcImportController::class, 'createFrameworkFromFile'])->name('admin.marc.import.create-framework');

    // MARC Reports
    Route::get('/marc-reports', [\App\Http\Controllers\Admin\MarcReportController::class, 'index'])->name('admin.marc.reports.index');
    Route::post('/marc-reports/generate', [\App\Http\Controllers\Admin\MarcReportController::class, 'generate'])->name('admin.marc.reports.generate');

    // Distribution & Inventory
    Route::get('/marc-books/{record}/distribution', [\App\Http\Controllers\Admin\BookDistributionController::class, 'index'])->name('admin.marc.book.distribution');
    Route::post('/marc-books/{record}/distribution', [\App\Http\Controllers\Admin\BookDistributionController::class, 'store'])->name('admin.marc.book.distribution.store');
    Route::get('/distribution/check-barcode', [\App\Http\Controllers\Admin\BookDistributionController::class, 'checkBarcode'])->name('admin.marc.book.distribution.check');

    // Patron Management (Library Users)
    Route::get('/patrons', [\App\Http\Controllers\Admin\PatronController::class, 'index'])->name('admin.patrons.index');
    Route::get('/patrons/create', [\App\Http\Controllers\Admin\PatronController::class, 'create'])->name('admin.patrons.create');
    Route::post('/patrons', [\App\Http\Controllers\Admin\PatronController::class, 'store'])->name('admin.patrons.store');
    Route::get('/patrons/{id}/edit', [\App\Http\Controllers\Admin\PatronController::class, 'edit'])->name('admin.patrons.edit');
    Route::patch('/patrons/{id}', [\App\Http\Controllers\Admin\PatronController::class, 'update'])->name('admin.patrons.update');
    Route::patch('/patrons/{id}/toggle-status', [\App\Http\Controllers\Admin\PatronController::class, 'toggleStatus'])->name('admin.patrons.toggle-status');
    Route::patch('/patrons/{id}/renew', [\App\Http\Controllers\Admin\PatronController::class, 'renew'])->name('admin.patrons.renew');
    Route::delete('/patrons/{id}', [\App\Http\Controllers\Admin\PatronController::class, 'destroy'])->name('admin.patrons.destroy');
    
    // Bulk Actions
    Route::post('/patrons/bulk-update', [\App\Http\Controllers\Admin\PatronController::class, 'bulkUpdate'])->name('admin.patrons.bulk.update');
    Route::delete('/patrons/bulk-delete', [\App\Http\Controllers\Admin\PatronController::class, 'bulkDelete'])->name('admin.patrons.bulk.delete');

    // Patron Import (Batch Import)
    Route::get('/patrons/import', [\App\Http\Controllers\Admin\PatronImportController::class, 'index'])->name('admin.patrons.import.index');
    Route::get('/patrons/import/template', [\App\Http\Controllers\Admin\PatronImportController::class, 'template'])->name('admin.patrons.import.template');
    Route::post('/patrons/import/upload', [\App\Http\Controllers\Admin\PatronImportController::class, 'upload'])->name('admin.patrons.import.upload');
    Route::get('/patrons/import/preview', [\App\Http\Controllers\Admin\PatronImportController::class, 'preview'])->name('admin.patrons.import.preview');
    Route::post('/patrons/import/process', [\App\Http\Controllers\Admin\PatronImportController::class, 'process'])->name('admin.patrons.import.process');
    Route::post('/patrons/import/images', [\App\Http\Controllers\Admin\PatronImportController::class, 'uploadImages'])->name('admin.patrons.import.images');

    // Patron Cards (ID Card Printing)
    Route::get('/patrons/cards', [\App\Http\Controllers\Admin\PatronCardController::class, 'index'])->name('admin.patrons.cards.index');
    Route::post('/patrons/cards/generate', [\App\Http\Controllers\Admin\PatronCardController::class, 'generateCards'])->name('admin.patrons.cards.generate');
    Route::get('/patrons/cards/{patron}/preview', [\App\Http\Controllers\Admin\PatronCardController::class, 'previewCard'])->name('admin.patrons.cards.preview');

    // Patron Management Features
    Route::patch('/patrons/{id}/lock', [\App\Http\Controllers\Admin\PatronController::class, 'lock'])->name('admin.patrons.lock');
    Route::patch('/patrons/{id}/unlock', [\App\Http\Controllers\Admin\PatronController::class, 'unlock'])->name('admin.patrons.unlock');
    
    // Financial Transactions
    Route::get('/patrons/{id}/transactions', [\App\Http\Controllers\Admin\PatronTransactionController::class, 'index'])->name('admin.patrons.transactions.index');
    Route::post('/patrons/{id}/transactions', [\App\Http\Controllers\Admin\PatronTransactionController::class, 'store'])->name('admin.patrons.transactions.store');
    
    // Print Queue Management
    Route::get('/patrons/print-queue', [\App\Http\Controllers\Admin\PrintQueueController::class, 'index'])->name('admin.patrons.print-queue.index');
    Route::post('/patrons/{id}/add-to-print-queue', [\App\Http\Controllers\Admin\PatronController::class, 'addToPrintQueue'])->name('admin.patrons.add-to-print-queue');
    Route::delete('/patrons/{id}/remove-from-print-queue', [\App\Http\Controllers\Admin\PatronController::class, 'removeFromPrintQueue'])->name('admin.patrons.remove-from-print-queue');
    Route::post('/patrons/print-queue/{id}/mark-printed', [\App\Http\Controllers\Admin\PrintQueueController::class, 'markPrinted'])->name('admin.patrons.print-queue.mark-printed');
    Route::delete('/patrons/print-queue/{id}', [\App\Http\Controllers\Admin\PrintQueueController::class, 'destroy'])->name('admin.patrons.print-queue.destroy');
    
    // Lock History
    Route::get('/patrons/{id}/lock-history', [\App\Http\Controllers\Admin\PatronController::class, 'lockHistory'])->name('admin.patrons.lock-history');
    Route::get('/patrons/lock-history', [\App\Http\Controllers\Admin\PatronController::class, 'allLockHistory'])->name('admin.patrons.lock-history.all');
    
    // System Logs
    Route::get('/patrons/system-logs', [\App\Http\Controllers\Admin\PatronController::class, 'systemLogs'])->name('admin.patrons.system-logs');

    // Patron Configuration
    Route::get('/patron-groups', [PatronGroupController::class, 'index'])->name('admin.patrons.groups.index');
    Route::post('/patron-groups', [PatronGroupController::class, 'store'])->name('admin.patrons.groups.store');
    Route::put('/patron-groups/{patronGroup}', [PatronGroupController::class, 'update'])->name('admin.patrons.groups.update');
    Route::delete('/patron-groups/{patronGroup}', [PatronGroupController::class, 'destroy'])->name('admin.patrons.groups.destroy');
    Route::patch('/patron-groups/reorder', [PatronGroupController::class, 'updateOrder'])->name('admin.patrons.groups.reorder');

    // System Infrastructure Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'update'])->name('admin.settings.update');
    
    // Sidebar Management
    Route::get('/sidebar-management', [\App\Http\Controllers\Admin\SidebarManagementController::class, 'index'])->name('admin.sidebar.index');
    Route::post('/sidebar-management', [\App\Http\Controllers\Admin\SidebarManagementController::class, 'store'])->name('admin.sidebar.store');
    Route::put('/sidebar-management/order', [\App\Http\Controllers\Admin\SidebarManagementController::class, 'updateOrder'])->name('admin.sidebar.order');
    Route::put('/sidebar-management/parent', [\App\Http\Controllers\Admin\SidebarManagementController::class, 'updateParent'])->name('admin.sidebar.parent');
    Route::put('/sidebar-management/toggle-active', [\App\Http\Controllers\Admin\SidebarManagementController::class, 'toggleActive'])->name('admin.sidebar.toggle-active');
    Route::post('/settings/policy', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updatePolicy'])->name('admin.settings.policy.update');

    Route::post('/settings/barcode', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'storeBarcodeConfig'])->name('admin.settings.barcode.store');
    Route::put('/settings/barcode/{config}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updateBarcodeConfig'])->name('admin.settings.barcode.update');
    Route::delete('/settings/barcode/{config}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'deleteBarcodeConfig'])->name('admin.settings.barcode.destroy');

    Route::post('/settings/branches', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'storeBranch'])->name('admin.settings.branches.store');
    Route::put('/settings/branches/{branch}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updateBranch'])->name('admin.settings.branches.update');
    Route::delete('/settings/branches/{branch}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'deleteBranch'])->name('admin.settings.branches.destroy');

    Route::post('/settings/locations', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'storeLocation'])->name('admin.settings.locations.store');
    Route::put('/settings/locations/{location}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updateLocation'])->name('admin.settings.locations.update');
    Route::delete('/settings/locations/{location}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'deleteLocation'])->name('admin.settings.locations.destroy');

    Route::post('/settings/suppliers', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'storeSupplier'])->name('admin.settings.suppliers.store');
    Route::put('/settings/suppliers/{supplier}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'updateSupplier'])->name('admin.settings.suppliers.update');
    Route::delete('/settings/suppliers/{supplier}', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'deleteSupplier'])->name('admin.settings.suppliers.destroy');

    // Circulation & Fines
    Route::get('/circulation', [\App\Http\Controllers\Admin\CirculationController::class, 'index'])->name('admin.circulation.index');
    Route::post('/circulation/patron-groups', [\App\Http\Controllers\Admin\CirculationController::class, 'storePatronGroup'])->name('admin.circulation.patron-groups.store');
    Route::put('/circulation/patron-groups/{patronGroup}', [\App\Http\Controllers\Admin\CirculationController::class, 'updatePatronGroup'])->name('admin.circulation.patron-groups.update');
    Route::delete('/circulation/patron-groups/{patronGroup}', [\App\Http\Controllers\Admin\CirculationController::class, 'deletePatronGroup'])->name('admin.circulation.patron-groups.destroy');

    Route::post('/circulation/policies', [\App\Http\Controllers\Admin\CirculationController::class, 'storePolicy'])->name('admin.circulation.policies.store');
    Route::put('/circulation/policies/{policy}', [\App\Http\Controllers\Admin\CirculationController::class, 'updatePolicy'])->name('admin.circulation.policies.update');
    Route::delete('/circulation/policies/{policy}', [\App\Http\Controllers\Admin\CirculationController::class, 'deletePolicy'])->name('admin.circulation.policies.destroy');

    Route::get('/circulation/loan-desk', [\App\Http\Controllers\Admin\CirculationController::class, 'loanDesk'])->name('admin.circulation.loan-desk');
    Route::post('/circulation/checkout', [\App\Http\Controllers\Admin\CirculationController::class, 'checkout'])->name('admin.circulation.checkout');
    Route::post('/circulation/checkin', [\App\Http\Controllers\Admin\CirculationController::class, 'checkin'])->name('admin.circulation.checkin');
    Route::post('/circulation/renew/{loan}', [\App\Http\Controllers\Admin\CirculationController::class, 'renew'])->name('admin.circulation.renew');
    Route::post('/circulation/recall', [\App\Http\Controllers\Admin\CirculationController::class, 'recall'])->name('admin.circulation.recall');
    Route::post('/circulation/declare-lost', [\App\Http\Controllers\Admin\CirculationController::class, 'declareLost'])->name('admin.circulation.declare-lost');

    // AJAX Search Routes
    Route::get('/circulation/search-patron', [\App\Http\Controllers\Admin\CirculationController::class, 'searchPatron'])->name('admin.circulation.search-patron');
    Route::get('/circulation/search-book', [\App\Http\Controllers\Admin\CirculationController::class, 'searchBook'])->name('admin.circulation.search-book');

    Route::get('/circulation/fines', [\App\Http\Controllers\Admin\CirculationController::class, 'fines'])->name('admin.circulation.fines');
    Route::post('/circulation/fines/{fine}/pay', [\App\Http\Controllers\Admin\CirculationController::class, 'payFine'])->name('admin.circulation.fines.pay');
    Route::post('/circulation/fines/{fine}/waive', [\App\Http\Controllers\Admin\CirculationController::class, 'waiveFine'])->name('admin.circulation.fines.waive');
    
    // Circulation main operations
    Route::get('/circulation', [\App\Http\Controllers\Admin\CirculationController::class, 'loanDesk'])->name('admin.circulation.loan-desk');
    Route::post('/circulation/checkout', [\App\Http\Controllers\Admin\CirculationController::class, 'checkout'])->name('admin.circulation.checkout');
    Route::post('/circulation/checkin', [\App\Http\Controllers\Admin\CirculationController::class, 'checkin'])->name('admin.circulation.checkin');
    Route::post('/circulation/renew/{loan}', [\App\Http\Controllers\Admin\CirculationController::class, 'renew'])->name('admin.circulation.renew');
    Route::post('/circulation/recall', [\App\Http\Controllers\Admin\CirculationController::class, 'recall'])->name('admin.circulation.recall');
    Route::post('/circulation/declare-lost', [\App\Http\Controllers\Admin\CirculationController::class, 'declareLost'])->name('admin.circulation.declare-lost');

    // Reading Room Operations
    Route::post('/circulation/reading-room/checkout', [\App\Http\Controllers\Admin\CirculationController::class, 'readingRoomCheckout'])->name('admin.circulation.reading-room.checkout');
    Route::post('/circulation/reading-room/checkin', [\App\Http\Controllers\Admin\CirculationController::class, 'readingRoomCheckin'])->name('admin.circulation.reading-room.checkin');
    Route::get('/circulation/reading-room/transactions', [\App\Http\Controllers\Admin\CirculationController::class, 'getReadingRoomTransactions'])->name('admin.circulation.reading-room.transactions');
    Route::get('/circulation/reading-room/active', [\App\Http\Controllers\Admin\CirculationController::class, 'getActiveReadingRoomTransactions'])->name('admin.circulation.reading-room.active');

    // Hold/Reserve Operations
    Route::post('/circulation/hold/place', [\App\Http\Controllers\Admin\CirculationController::class, 'placeHold'])->name('admin.circulation.hold.place');
    Route::post('/circulation/hold/cancel', [\App\Http\Controllers\Admin\CirculationController::class, 'cancelHold'])->name('admin.circulation.hold.cancel');
    Route::get('/circulation/hold/patron', [\App\Http\Controllers\Admin\CirculationController::class, 'getPatronReservations'])->name('admin.circulation.hold.patron');
    Route::get('/circulation/hold/all', [\App\Http\Controllers\Admin\CirculationController::class, 'getAllActiveReservations'])->name('admin.circulation.hold.all');
    Route::post('/circulation/hold/fulfill', [\App\Http\Controllers\Admin\CirculationController::class, 'fulfillReservation'])->name('admin.circulation.hold.fulfill');

    // Circulation Tools
    Route::get('/circulation/tools', [\App\Http\Controllers\Admin\CirculationController::class, 'tools'])->name('admin.circulation.tools');
    Route::post('/circulation/patron-history', [\App\Http\Controllers\Admin\CirculationController::class, 'getPatronHistory'])->name('admin.circulation.patron-history');
    Route::post('/circulation/advanced-search', [\App\Http\Controllers\Admin\CirculationController::class, 'advancedBookSearch'])->name('admin.circulation.advanced-search');

    // Circulation Policies Management - DEDICATED CONTROLLER
    Route::prefix('circulation/policies')->name('admin.circulation.policies.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'store'])->name('store');
        Route::get('/{policy}', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'show'])->name('show');
        Route::get('/{policy}/edit', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'edit'])->name('edit');
        Route::put('/{policy}', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'update'])->name('update');
        Route::delete('/{policy}', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'destroy'])->name('destroy');
        Route::delete('/{policy}/force', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'forceDelete'])->name('force-delete');
        Route::post('/{policy}/toggle', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'toggleStatus'])->name('toggle');
        Route::post('/{policy}/duplicate', [\App\Http\Controllers\Admin\CirculationPolicyController::class, 'duplicate'])->name('duplicate');
    });

    // Circulation Distribution (placeholder for sidebar)
    Route::get('/circulation/distribution', [\App\Http\Controllers\Admin\CirculationController::class, 'loanDesk'])->name('admin.circulation.distribution');

    // Circulation Reports
    Route::prefix('circulation/reports')->name('admin.circulation.reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CirculationReportController::class, 'index'])->name('index');
        Route::post('/export', [\App\Http\Controllers\Admin\CirculationReportController::class, 'export'])->name('export');
        Route::get('/currently-borrowed', [\App\Http\Controllers\Admin\CirculationReportController::class, 'currentlyBorrowed'])->name('currently_borrowed');
        Route::get('/patron-service', [\App\Http\Controllers\Admin\CirculationReportController::class, 'patronService'])->name('patron_service');
        Route::get('/overdue', [\App\Http\Controllers\Admin\CirculationReportController::class, 'overdue'])->name('overdue');
        Route::get('/top-patrons', [\App\Http\Controllers\Admin\CirculationReportController::class, 'topPatrons'])->name('top_patrons');
        Route::get('/transaction-history', [\App\Http\Controllers\Admin\CirculationReportController::class, 'transactionHistory'])->name('transaction_history');
        Route::get('/never-borrowed', [\App\Http\Controllers\Admin\CirculationReportController::class, 'neverBorrowed'])->name('never_borrowed');
        Route::get('/library-entries', [\App\Http\Controllers\Admin\CirculationReportController::class, 'libraryEntries'])->name('library_entries');
        Route::get('/website-access', [\App\Http\Controllers\Admin\CirculationReportController::class, 'websiteAccess'])->name('website_access');
    });

    // Metadata Configuration
    Route::get('/document-types', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'index'])->name('admin.document-types.index');
    Route::post('/document-types', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'store'])->name('admin.document-types.store');
    Route::put('/document-types/{documentType}', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'update'])->name('admin.document-types.update');
    Route::delete('/document-types/{documentType}', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'destroy'])->name('admin.document-types.destroy');
    Route::post('/document-types/order', [\App\Http\Controllers\Admin\DocumentTypeController::class, 'updateOrder'])->name('admin.document-types.order');

    // External Protocol Integration (Z39.50)
    Route::get('/z3950', [\App\Http\Controllers\Admin\Z3950Controller::class, 'index'])->name('admin.z3950.index');
    Route::post('/z3950', [\App\Http\Controllers\Admin\Z3950Controller::class, 'store'])->name('admin.z3950.store');
    Route::put('/z3950/{server}', [\App\Http\Controllers\Admin\Z3950Controller::class, 'update'])->name('admin.z3950.update');
    Route::delete('/z3950/{server}', [\App\Http\Controllers\Admin\Z3950Controller::class, 'destroy'])->name('admin.z3950.destroy');
    Route::post('/z3950/{server}/test', [\App\Http\Controllers\Admin\Z3950Controller::class, 'testConnection'])->name('admin.z3950.test');
    Route::get('/z3950/search', [\App\Http\Controllers\Admin\Z3950Controller::class, 'search'])->name('admin.z3950.search');
    Route::post('/z3950/search', [\App\Http\Controllers\Admin\Z3950Controller::class, 'doSearch'])->name('admin.z3950.doSearch');
    Route::post('/z3950/import', [\App\Http\Controllers\Admin\Z3950Controller::class, 'import'])->name('admin.z3950.import');
});

// Visitor Routes
Route::middleware(['auth', 'role:visitor'])->group(function () {
    // Visitor-specific functionality
});
