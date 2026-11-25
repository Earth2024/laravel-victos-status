<?php

use Earth2024\VictosStatus\Http\Controllers\StatusCheckController;
use Earth2024\VictosStatus\Http\Controllers\VictosAdminController;
use Earth2024\VictosStatus\Http\Controllers\VictosAdminManagementController;
use Earth2024\VictosStatus\Http\Controllers\VictosAuthController;
use Earth2024\VictosStatus\Http\Controllers\VictosProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('victos-status.public_middleware', ['web', 'throttle:30,1']))
    ->prefix(config('victos-status.status_prefix', 'check-status'))
    ->group(function () {
        Route::get('/', [StatusCheckController::class, 'check'])->name('status.check');
        Route::get('/widget-config', [StatusCheckController::class, 'widgetConfig'])->name('status.widget-config');
    });

Route::prefix(config('victos-status.admin_prefix', 'victos'))
    ->name('victos.')
    ->group(function () {
        Route::middleware(config('victos-status.guest_middleware', ['web']))->group(function () {
            Route::get('/login', [VictosAuthController::class, 'showLogin'])->name('login');
            Route::post('/login', [VictosAuthController::class, 'login'])->name('login.submit');
        });

        Route::middleware(config('victos-status.admin_middleware', ['web', 'auth']))->group(function () {
            Route::get('/', [VictosAdminController::class, 'dashboard'])->name('dashboard');
            Route::post('/logout', [VictosAuthController::class, 'logout'])->name('logout');
            Route::get('/profile', [VictosProfileController::class, 'show'])->name('profile.show');
            Route::put('/profile', [VictosProfileController::class, 'update'])->name('profile.update');
            Route::put('/profile/password', [VictosProfileController::class, 'updatePassword'])->name('profile.password');
            Route::post('/settings', [VictosAdminController::class, 'updateSettings'])->name('settings.update');
            Route::get('/records/create', [VictosAdminController::class, 'createRecord'])->name('records.create');
            Route::post('/records', [VictosAdminController::class, 'storeRecord'])->name('records.store');
            Route::get('/records/{record}/edit', [VictosAdminController::class, 'editRecord'])->name('records.edit');
            Route::put('/records/{record}', [VictosAdminController::class, 'updateRecord'])->name('records.update');
            Route::delete('/records/{record}', [VictosAdminController::class, 'destroyRecord'])->name('records.destroy');

            Route::middleware('can:manage-victos-admins')->prefix('admins')->name('admins.')->group(function () {
                Route::get('/', [VictosAdminManagementController::class, 'index'])->name('index');
                Route::get('/create', [VictosAdminManagementController::class, 'create'])->name('create');
                Route::post('/', [VictosAdminManagementController::class, 'store'])->name('store');
            });
        });
    });
