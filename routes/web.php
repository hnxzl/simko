<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Request;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OilChangeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BorrowReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InspectionController;


// ======================================================
// GUEST (BELUM LOGIN)
// ======================================================
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'storelogin'])->name('login.process');

});


// ======================================================
// AUTH (SUDAH LOGIN)
// ======================================================
Route::middleware('auth')->group(function () {

    // LOGOUT
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


    // REDIRECT DASHBOARD BERDASARKAN ROLE
    Route::get('/home', function () {
        return match (strtolower(Auth::user()->role)) {
            'admin' => redirect()->route('admin.dashboard'),
            'hrd' => redirect()->route('hrd.dashboard'),
            'karyawan' => redirect()->route('karyawan.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'bod' => redirect()->route('bod.dashboard'),
            default => redirect()->route('logout'),
        };
    })->name('home');


    // ======================================================
    // DASHBOARD PER ROLE
    // ======================================================
    Route::middleware(['userAccess:admin'])->get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::middleware(['userAccess:hrd'])->get('/hrd/dashboard', [DashboardController::class, 'hrd'])->name('hrd.dashboard');
    Route::middleware(['userAccess:karyawan'])->get('/karyawan/dashboard', [DashboardController::class, 'karyawan'])->name('karyawan.dashboard');
    Route::middleware(['userAccess:manager'])->get('/manager/dashboard', [DashboardController::class, 'manager'])->name('manager.dashboard');
    Route::middleware(['userAccess:bod'])->get('/bod/dashboard', [DashboardController::class, 'bod'])->name('bod.dashboard');


    // ======================================================
    // USERS (HANYA ADMIN)
    // ======================================================
    Route::resource('/users', UserController::class)->middleware(['userAccess:admin']);


    // ======================================================
    // PROFILE (semua role bisa edit profil sendiri)
    // ======================================================
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');


    // ======================================================
    // DRIVERS
    // Admin + HRD -> full access
    // Karyawan + Manager + BoD -> hanya index + show
    // ======================================================
    Route::middleware(['userAccess:admin,hrd'])->group(function () {
        Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
        Route::get('/drivers/manage', [DriverController::class, 'manage'])->name('drivers.manage');
        Route::post('/drivers/manage/save', [DriverController::class, 'saveManage'])->name('drivers.manage.save');
        Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
        Route::get('/drivers/{id}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
        Route::put('/drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');
        Route::delete('/drivers/{id}', [DriverController::class, 'destroy'])->name('drivers.destroy');
    });
    Route::get('/drivers', [DriverController::class, 'index'])
        ->middleware(['userAccess:admin,hrd,karyawan,manager,bod'])
        ->name('drivers.index');
    Route::get('/drivers/{id}', [DriverController::class, 'show'])
        ->middleware(['userAccess:admin,hrd,karyawan,manager,bod'])
        ->name('drivers.show');


    // ======================================================
    // VEHICLES
    // ======================================================
// All authenticated users can view vehicles
Route::middleware(['userAccess:admin,hrd,karyawan,manager,bod'])->group(function () {

    Route::get('/vehicles', [VehicleController::class, 'index'])
        ->name('vehicles.index');

    Route::get('/vehicles/create', [VehicleController::class, 'create'])
        ->name('vehicles.create');

    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])
        ->name('vehicles.show');
});


// Admin/HRD only - full CRUD access
Route::middleware(['userAccess:admin,hrd'])->group(function () {

    Route::post('/vehicles', [VehicleController::class, 'store'])
        ->name('vehicles.store');

    Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])
        ->name('vehicles.edit');

    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])
        ->name('vehicles.update');

    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])
        ->name('vehicles.destroy');

    Route::post('/vehicles/{id}/disable', [VehicleController::class, 'disable'])
        ->name('vehicles.disable');

    Route::post('/vehicles/{id}/enable', [VehicleController::class, 'enable'])
        ->name('vehicles.enable');
});

    Route::post('/vehicles/{id}/oil-change', [OilChangeController::class, 'store'])
        ->middleware(['userAccess:admin,hrd,karyawan,manager'])
        ->name('oilchange.store');


    // ======================================================
    // BORROWINGS
    // ======================================================
    Route::resource('/borrowings', BorrowingController::class)
        ->middleware(['userAccess:admin,hrd,karyawan,manager,bod']);
    
    // Borrowing workflow actions
    Route::patch('/borrowings/{id}/cancel', [BorrowingController::class, 'cancel'])
        ->name('borrowings.cancel');
    Route::post('/borrowings/{id}/manager-approve', [BorrowingController::class, 'managerApprove'])
        ->middleware(['userAccess:admin,manager'])
        ->name('borrowings.manager-approve');
    Route::post('/borrowings/{id}/manager-reject', [BorrowingController::class, 'managerReject'])
        ->middleware(['userAccess:admin,manager'])
        ->name('borrowings.manager-reject');
    Route::post('/borrowings/{id}/hrd-assign', [BorrowingController::class, 'hrdAssign'])
        ->middleware(['userAccess:admin,hrd'])
        ->name('borrowings.hrd-assign');
    Route::post('/borrowings/{id}/bod-approve', [BorrowingController::class, 'bodApprove'])
        ->middleware(['userAccess:bod'])
        ->name('borrowings.bod-approve');
    Route::post('/borrowings/{id}/complete', [BorrowingController::class, 'complete'])
        ->name('borrowings.complete');
    Route::get('/borrowings/{id}/download/{type?}', [BorrowingController::class, 'downloadSurat'])
        ->name('borrowings.download');


    // ======================================================
    // INSPECTIONS (HRD only)
    // ======================================================
    Route::middleware(['userAccess:admin,hrd'])->group(function () {
        Route::get('/inspections/create/{borrow_id}', [InspectionController::class, 'create'])->name('inspections.create');
        Route::post('/inspections/{borrow_id}', [InspectionController::class, 'store'])->name('inspections.store');
        Route::get('/inspections/{id}', [InspectionController::class, 'show'])->name('inspections.show');
    });


    // ======================================================
    // BORROW REPORTS (PDF, Excel, CSV, JSON)
    // ======================================================
    Route::get('/reports/borrow', [BorrowReportController::class, 'form'])
        ->name('reports.borrow.form');

    Route::post('/reports/borrow/generate', [BorrowReportController::class, 'generate'])
        ->name('reports.borrow.generate');


    // ======================================================
    // NOTIFICATIONS
    // ======================================================
    Route::get('/notifications/read/{id}', [NotificationController::class, 'markRead'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAll'])
        ->name('notifications.read-all');
});
