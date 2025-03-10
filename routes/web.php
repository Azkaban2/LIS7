<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Models\SelectedMachine; 
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\InventoryController;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication routes (auth.php)
require __DIR__.'/auth.php';

// User and Admin Dashboards
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard'); // User Dashboard
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard'); // Admin Dashboard
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/activity-log', [AdminController::class, 'activityLog'])->name('admin.activity-log');
    Route::delete('/activity-log/clear', [AdminController::class, 'clearLogs'])->name('admin.clearLogs');
    Route::get('/activity-log/export-pdf', [AdminController::class, 'exportLogsPdf'])->name('admin.exportLogsPdf');


});

// Profile Management
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.updatePicture');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// OrderRequest Routes for Authenticated Users
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/order-requests/create', [OrderRequestController::class, 'create'])->name('order-requests.create');
    Route::post('/order-requests', [OrderRequestController::class, 'store'])->name('order-requests.store');
    Route::get('/order-requests', [OrderRequestController::class, 'requestlog'])->name('order-requests.requestlog');
    Route::get('/order-requests/{id}/edit', [OrderRequestController::class, 'edit'])->name('order-requests.edit');
    Route::patch('/order-requests/{id}', [OrderRequestController::class, 'update'])->name('order-requests.update');
    Route::delete('/order-requests/{id}', [OrderRequestController::class, 'destroy'])->name('order-requests.destroy');
    
    // New route for checking if a patient exists and retrieving their ID
    Route::get('/check-patient', [OrderRequestController::class, 'checkPatient'])->name('check.patient');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard');

    // Order Requests (Request Log Only)
    Route::get('/order-requests', [OrderRequestController::class, 'requestlog'])->name('admin.order-requests.requestlog');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');

    // Patient Log
    Route::get('/patient-log', [ResultController::class, 'patientLog'])->name('admin.patient_log');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('inventory', InventoryController::class);
});

// Result Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/results/instrument-import', [ResultController::class, 'showInstrumentImportForm'])->name('results.instrument_import');
    Route::get('/get-patients-by-machine', [ResultController::class, 'getPatientsByMachine'])->name('getPatientsByMachine');
    Route::post('/load-automatic-data', [ResultController::class, 'loadAutomaticData'])->name('load-automatic-data');
    Route::get('/get-patient-details', [ResultController::class, 'getPatientDetails'])->name('getPatientDetails');
    Route::get('/get-test-form', [ResultController::class, 'getTestForm'])->name('getTestForm');
    Route::post('/validate-results', [ResultController::class, 'validateResults'])->name('validate-results');
    

    // PDF Generation and Download Routes
    Route::post('/generate-pdf', [ResultController::class, 'saveValidationAndGeneratePDF'])->name('generate-pdf');
    Route::get('/patient-log', [ResultController::class, 'patientLog'])->name('patient_log');
    Route::get('/download-pdf/{id}', [ResultController::class, 'downloadPDF'])->name('download.pdf');
    Route::delete('/patient-log/{id}', [ResultController::class, 'deleteResult'])->name('delete-result');
});

// Store Selected Machine
Route::post('/store-selected-machine', function (Request $request) {
    Log::info('Request payload:', $request->all());

    $request->validate([
        'machine' => 'required|string',
    ]);

    $selectedMachine = SelectedMachine::updateOrCreate(
        ['user_id' => auth()->id()], // Match the current user's record
        ['machine' => $request->input('machine')] // Update the machine value
    );

    Log::info('Machine saved:', $selectedMachine->toArray());

    return response()->json([
        'success' => true,
        'message' => 'Machine saved successfully',
        'data' => $selectedMachine,
    ]);
});









