<?php

use App\Http\Controllers\Admin\AdminGuestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Buku Tamu BGTK Sumatera Barat
|--------------------------------------------------------------------------
*/

// Front-Office / Public Kiosk Routes
Route::get('/', [GuestController::class, 'create'])->name('guest.create');
Route::post('/tamu', [GuestController::class, 'store'])->name('guest.store');
Route::get('/tamu/sukses/{ticket}', [GuestController::class, 'success'])->name('guest.success');
Route::get('/checkout', [GuestController::class, 'checkout'])->name('guest.checkout');
Route::post('/checkout', [GuestController::class, 'storeCheckout'])->name('guest.checkout.store');
Route::get('/display', [GuestController::class, 'display'])->name('guest.display');
Route::get('/qr-flashdisk', [GuestController::class, 'qrDisplay'])->name('guest.qr-display');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin & Petugas Panel Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Guests Management
    Route::get('/guests', [AdminGuestController::class, 'index'])->name('guests.index');
    Route::get('/guests/export/csv', [AdminGuestController::class, 'exportCsv'])->name('guests.export');
    Route::get('/guests/report/print', [AdminGuestController::class, 'printReport'])->name('guests.print');
    Route::get('/guests/{guest}', [AdminGuestController::class, 'show'])->name('guests.show');
    Route::patch('/guests/{guest}/status', [AdminGuestController::class, 'updateStatus'])->name('guests.status');
    Route::delete('/guests/{guest}', [AdminGuestController::class, 'destroy'])->name('guests.destroy');

    // Departments / Tim Kerja
    Route::resource('departments', DepartmentController::class)->except(['create', 'show', 'edit']);

    // Reports & IKM
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/satisfaction', [ReportController::class, 'satisfaction'])->name('reports.satisfaction');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/institution', [SettingController::class, 'updateInstitution'])->name('settings.institution');
    Route::post('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/users', [SettingController::class, 'storeUser'])->name('settings.users.store');
    Route::patch('/settings/users/{user}/toggle', [SettingController::class, 'toggleUserStatus'])->name('settings.users.toggle');
});
