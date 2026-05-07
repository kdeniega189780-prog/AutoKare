<?php

use App\Http\Controllers\Admin\AdminAppointmentsController;
use App\Http\Controllers\Admin\AdminMaintenanceController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Customer\CustomerPortalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaintenanceScheduleController;
use App\Http\Controllers\Mechanic\MechanicPortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Suggest endpoint must be declared before Route::resource so it isn't captured as {vehicle}.
    Route::get('vehicles/suggest', [VehicleController::class, 'suggest'])->name('vehicles.suggest');

    Route::resource('vehicles', VehicleController::class);
    Route::resource('schedules', MaintenanceScheduleController::class);

    // Modal endpoints (HTML partials)
    Route::get('vehicles/create/modal', [VehicleController::class, 'createModal'])->name('vehicles.createModal');
    Route::get('vehicles/{vehicle}/modal', [VehicleController::class, 'showModal'])->name('vehicles.showModal');
    Route::get('vehicles/{vehicle}/edit/modal', [VehicleController::class, 'editModal'])->name('vehicles.editModal');

    Route::get('schedules/create/modal', [MaintenanceScheduleController::class, 'createModal'])->name('schedules.createModal');
    Route::get('schedules/{schedule}/modal', [MaintenanceScheduleController::class, 'showModal'])->name('schedules.showModal');
    Route::get('schedules/{schedule}/edit/modal', [MaintenanceScheduleController::class, 'editModal'])->name('schedules.editModal');
    Route::get('schedules/{schedule}/start/modal', [MaintenanceScheduleController::class, 'startModal'])->name('schedules.startModal');
    Route::get('schedules/{schedule}/assign/modal', [MaintenanceScheduleController::class, 'assignModal'])->name('schedules.assignModal');

    Route::get('schedules/{schedule}/service-record/edit', [ServiceRecordController::class, 'edit'])
        ->name('service-records.edit');
    Route::get('schedules/{schedule}/service-record/edit/modal', [ServiceRecordController::class, 'editModal'])
        ->name('service-records.editModal');
    Route::put('schedules/{schedule}/service-record', [ServiceRecordController::class, 'update'])
        ->name('service-records.update');

    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    /** -------------------------------------------------------------------
     * Mechanic portal
     * ------------------------------------------------------------------- */
    Route::prefix('mechanic')->name('mechanic.')->middleware('role:mechanic')->group(function () {
        Route::get('tasks', [MechanicPortalController::class, 'assigned'])->name('tasks');
        Route::get('in-progress', [MechanicPortalController::class, 'inProgress'])->name('inProgress');
        Route::get('completed', [MechanicPortalController::class, 'completed'])->name('completed');
        Route::get('team-overview', [MechanicPortalController::class, 'teamOverview'])->name('teamOverview');
        Route::get('team-reports', [MechanicPortalController::class, 'teamReports'])->name('teamReports');
        Route::get('customer-vehicles', [MechanicPortalController::class, 'customerVehicles'])->name('customerVehicles');

        // Modals
        Route::get('tasks/{schedule}/details/modal', [MechanicPortalController::class, 'detailsModal'])->name('tasks.detailsModal');
        Route::get('tasks/{schedule}/start/modal', [MechanicPortalController::class, 'startModal'])->name('tasks.startModal');
        Route::get('tasks/{schedule}/assign/modal', [MechanicPortalController::class, 'assignModal'])->name('tasks.assignModal');
        Route::get('tasks/{schedule}/note/modal', [MechanicPortalController::class, 'noteModal'])->name('tasks.noteModal');
        Route::get('tasks/{schedule}/complete/modal', [MechanicPortalController::class, 'completeModal'])->name('tasks.completeModal');
        Route::get('tasks/{schedule}/report/modal', [MechanicPortalController::class, 'reportModal'])->name('tasks.reportModal');

        // Actions
        Route::post('tasks/{schedule}/start', [MechanicPortalController::class, 'startTask'])->name('tasks.start');
        Route::post('tasks/{schedule}/assign', [MechanicPortalController::class, 'assignTask'])->name('tasks.assign');
        Route::post('tasks/{schedule}/note', [MechanicPortalController::class, 'addNote'])->name('tasks.note');
        Route::post('tasks/{schedule}/complete', [MechanicPortalController::class, 'completeTask'])->name('tasks.complete');
        Route::post('tasks/{schedule}/team-status', [MechanicPortalController::class, 'updateTeamStatus'])->name('tasks.teamStatus');
    });

    /** -------------------------------------------------------------------
     * Admin
     * ------------------------------------------------------------------- */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Suggest endpoint declared before resource so 'suggest' isn't captured as {user}.
        Route::get('users/suggest', [UserManagementController::class, 'suggest'])->name('users.suggest');

        Route::resource('users', UserManagementController::class)->except(['show']);

        Route::get('users/create/modal', [UserManagementController::class, 'createModal'])->name('users.createModal');
        Route::get('users/{user}/edit/modal', [UserManagementController::class, 'editModal'])->name('users.editModal');

        Route::get('customers/search', [UserManagementController::class, 'searchCustomers'])->name('customers.search');

        Route::get('appointments/suggest', [AdminAppointmentsController::class, 'suggest'])->name('appointments.suggest');
        Route::get('appointments', [AdminAppointmentsController::class, 'index'])->name('appointments.index');
        Route::get('appointments/create/modal', [AdminAppointmentsController::class, 'createModal'])->name('appointments.createModal');
        Route::get('appointments/{schedule}/modal', [AdminAppointmentsController::class, 'viewModal'])->name('appointments.viewModal');
        Route::get('appointments/{schedule}/edit/modal', [AdminAppointmentsController::class, 'editModal'])->name('appointments.editModal');

        Route::get('maintenance', [AdminMaintenanceController::class, 'index'])->name('maintenance.index');
        Route::get('maintenance/{schedule}/modal', [AdminMaintenanceController::class, 'viewModal'])->name('maintenance.viewModal');
        Route::get('maintenance/{schedule}/edit/modal', [AdminMaintenanceController::class, 'editModal'])->name('maintenance.editModal');
        Route::put('maintenance/{schedule}', [AdminMaintenanceController::class, 'update'])->name('maintenance.update');
    });

    /** -------------------------------------------------------------------
     * Customer portal
     * ------------------------------------------------------------------- */
    Route::middleware('role:owner')->prefix('customer')->name('customer.')->group(function () {
        Route::get('vehicles', [CustomerPortalController::class, 'vehicles'])->name('vehicles');
        Route::get('vehicles/add/modal', [CustomerPortalController::class, 'vehicleAddModal'])->name('vehicles.addModal');
        Route::get('vehicles/{vehicle}/modal', [CustomerPortalController::class, 'vehicleViewModal'])->name('vehicles.viewModal');
        Route::get('vehicles/{vehicle}/schedule/modal', [CustomerPortalController::class, 'vehicleScheduleModal'])->name('vehicles.scheduleModal');
        Route::post('vehicles', [CustomerPortalController::class, 'vehicleStore'])->name('vehicles.store');

        Route::get('appointments', [CustomerPortalController::class, 'appointments'])->name('appointments');
        Route::get('appointments/book/modal', [CustomerPortalController::class, 'appointmentBookModal'])->name('appointments.bookModal');
        Route::post('appointments/{schedule}/cancel', [CustomerPortalController::class, 'appointmentCancel'])->name('appointments.cancel');

        Route::get('service-history', [CustomerPortalController::class, 'serviceHistory'])->name('serviceHistory');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
