<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\DeceasedPersonController;
use App\Http\Controllers\BurialPermitController;
use App\Http\Controllers\CemeteryMapController;
use App\Http\Controllers\CemeteryGridController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Super Admin only ──
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/superadmin/changelog', [SuperAdminDashboardController::class, 'changelog'])->name('superadmin.changelog');
        Route::get('/superadmin/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');
        Route::get('/superadmin/export', [SuperAdminDashboardController::class, 'export'])->name('superadmin.export');
        Route::get('/superadmin/export-excel', [SuperAdminDashboardController::class, 'exportExcel'])->name('superadmin.export-excel');
        Route::get('/superadmin/activity', [SuperAdminDashboardController::class, 'activityLog'])->name('superadmin.activity');

        Route::get('/superadmin/reports', [ReportController::class, 'superAdminIndex'])->name('superadmin.reports');
        Route::get('/superadmin/reports/excel', [ReportController::class, 'exportExcel'])->name('superadmin.reports.excel');
        Route::get('/superadmin/reports/pdf', [ReportController::class, 'export'])->name('superadmin.reports.pdf');
        Route::get('/reports', fn() => redirect()->route('superadmin.reports'));
        Route::resource('users', UserController::class)->names('superadmin.users');
        Route::get('/superadmin/geomap', [SuperAdminDashboardController::class, 'geomap'])
            ->name('superadmin.geomap');
        Route::get('/superadmin/data-quality', [SettingsController::class, 'dataQualityPage'])
            ->name('superadmin.dataquality');
    });
 
    // ── Super Admin + Cemetery Manager ──
    Route::middleware(['role:super_admin|cemetery_manager'])->group(function () {
        Route::apiResource('/niche-grids', CemeteryGridController::class)->except(['index'])->names('superadmin.nichegrids');
    });

    // ── Admin Only ──
    Route::middleware(['role:admin'])->group(function () {
        // Data Quality
        Route::get('/admin/data-quality', [SettingsController::class, 'dataQualityPage'])
            ->name('admin.dataquality');

         // Settings
        Route::get('/settings',                   [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings/{section}',         [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/reset/{target}',   [SettingsController::class, 'reset'])->name('settings.reset');
        Route::post('/settings/users',            [SettingsController::class, 'storeUser'])->name('settings.users.store');
        Route::delete('/settings/users/{user}',   [SettingsController::class, 'destroyUser'])->name('settings.users.destroy');
        Route::get('/settings/dataquality/scan',  [SettingsController::class, 'dataQualityScan'])->name('settings.dataquality.scan');
        Route::post('/settings/dataquality/update', [SettingsController::class, 'updateRecord'])->name('settings.dataquality.update');
        Route::post('/settings/dataquality/merge', [SettingsController::class, 'mergeRecords'])->name('settings.dataquality.merge');
        Route::post('/settings/dataquality/preview', [SettingsController::class, 'previewMerge'])->name('settings.dataquality.preview');
        Route::post('/settings/profile-photo',       [SettingsController::class, 'updateProfilePhoto'])->name('settings.profile.update');
        Route::delete('/settings/profile-photo',     [SettingsController::class, 'deleteProfilePhoto'])->name('settings.profile.delete');
    });
    // ── Admin + Super Admin + Cemetery Manager ──
    Route::middleware(['role:admin|super_admin|cemetery_manager'])->group(function () {
        
        // Niche Grids (Read-Only for Admin)
        Route::get('/niche-grids', [CemeteryGridController::class, 'index'])->name('nichegrids.index');

        // Permits
        Route::resource('permits', BurialPermitController::class);
        Route::post('permits/{permit}/renew',   [BurialPermitController::class, 'renew'])->name('permits.renew');
        Route::get('permits/{permit}/print',     [BurialPermitController::class, 'print'])->name('permits.print');

        // Deceased
        Route::resource('deceased', DeceasedPersonController::class);

        // Documents
        Route::post('permits/{permit}/documents',   [DocumentController::class, 'upload'])->name('documents.upload');
        Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::delete('documents/{document}',       [DocumentController::class, 'destroy'])->name('documents.destroy');

        // Cemetery
        Route::get('/cemetery/map',             [CemeteryMapController::class, 'index'])->name('cemetery.map');
        Route::get('/cemetery/plots',           [CemeteryMapController::class, 'plots'])->name('cemetery.plots');
        Route::post('/cemetery/plots',          [CemeteryMapController::class, 'store'])->name('cemetery.store');
        Route::patch('/cemetery/plots/{plot}',  [CemeteryMapController::class, 'update'])->name('cemetery.update');
        Route::delete('/cemetery/plots/{plot}', [CemeteryMapController::class, 'destroy'])->name('cemetery.destroy');
        Route::get('/cemetery/search-deceased', [CemeteryMapController::class, 'searchDeceased'])->name('cemetery.search-deceased');
        Route::get('/cemetery/search-permits',  [CemeteryMapController::class, 'searchPermits'])->name('cemetery.search-permits');
        Route::get('/cemetery/deceased/{deceased}/info', [DeceasedPersonController::class, 'infoJson'])->name('cemetery.deceased.info');


        // Import
        Route::get('/import/excel',        [ImportController::class, 'showImport'])->name('import.show');
        Route::post('/import/excel',       [ImportController::class, 'importExcel'])->name('import.excel');
        Route::get('/import/history-json', [ImportController::class, 'historyJson'])->name('import.history-json');

        // Reports (admin only — superadmin is redirected above)
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');


        // SMS
        Route::post('permits/{permit}/sms', [SmsController::class, 'send'])->name('sms.send');

        // Support
        Route::get('/support/manual', [SupportController::class, 'manual'])->name('support.manual');
    });
});