<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\DisaggregationController;
use App\Http\Controllers\OrganizationalIndicatorController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgrammaticIndicatorController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

// Public routes (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // barangay routes
    Route::get('/barangays',[BarangayController::class, 'index']);
    Route::get('/barangays/{id}/edit',[BarangayController::class, 'edit']);
    Route::put('/barangays/{id}/update',[BarangayController::class, 'update']);
    Route::get('/barangays/{id}/organizational-indicators/manage',[BarangayController::class, 'manageOrganizationalIndicators']);
    Route::post('/barangays/{id}/organizational-indicators/save',[BarangayController::class, 'saveOrganizationalIndicators']);
    Route::get('/barangays/{id}/priority-programs/manage',[BarangayController::class, 'managePriorityPrograms']);
    Route::post('/barangays/{id}/priority-programs/add',[BarangayController::class, 'addPriorityProgram']);
    Route::delete('/barangays/{id}/priority-programs/remove',[BarangayController::class, 'removePriorityProgram']);
    Route::put('/barangays/{id}/priority-programs/update',[BarangayController::class, 'updatePriorityProgram']);
    Route::put('/barangays/{id}/priority-programs/save-order',[BarangayController::class, 'savePriorityProgramsOrder']);

    // org indicator routes
    Route::get('/org-indicators',[OrganizationalIndicatorController::class, 'index']);
    Route::get('/org-indicators/create',[OrganizationalIndicatorController::class, 'create']);
    Route::post('/org-indicators/store',[OrganizationalIndicatorController::class, 'store']);
    Route::get('/org-indicators/{id}/edit',[OrganizationalIndicatorController::class, 'edit']);
    Route::put('/org-indicators/{id}/update',[OrganizationalIndicatorController::class, 'update']);

    // program routes
    Route::get('/programs',[ProgramController::class, 'index']);
    Route::get('/programs/create',[ProgramController::class, 'create']);
    Route::post('/programs/store',[ProgramController::class, 'store']);
    Route::get('/programs/{id}/edit',[ProgramController::class, 'edit']);
    Route::put('/programs/{id}/update',[ProgramController::class, 'update']);

    // prog indicator routes
    Route::get('/prog-indicators',[ProgrammaticIndicatorController::class, 'index']);
    Route::get('/prog-indicators/create',[ProgrammaticIndicatorController::class, 'create']);
    Route::post('/prog-indicators/store',[ProgrammaticIndicatorController::class, 'store']);
    Route::get('/prog-indicators/{id}/edit',[ProgrammaticIndicatorController::class, 'edit']);
    Route::put('/prog-indicators/{id}/update',[ProgrammaticIndicatorController::class, 'update']);
    Route::get('/prog-indicators/{id}/disaggregations/manage',[ProgrammaticIndicatorController::class, 'manageDisaggregations']);
    Route::post('/prog-indicators/{id}/disaggregations/add',[ProgrammaticIndicatorController::class, 'addDisaggregation']);
    Route::post('/prog-indicators/{id}/disaggregations/remove',[ProgrammaticIndicatorController::class, 'removeDisaggregation']);
    Route::post('/prog-indicators/{id}/disaggregations/totalable',[ProgrammaticIndicatorController::class, 'setDisaggregationTotalable']);

    // disaggregation routes
    Route::get('/disaggregations',[DisaggregationController::class, 'index']);
    Route::get('/disaggregations/create',[DisaggregationController::class, 'create']);
    Route::post('/disaggregations/store',[DisaggregationController::class, 'store']);
    Route::get('/disaggregations/{id}/edit',[DisaggregationController::class, 'edit']);
    Route::put('/disaggregations/{id}/update',[DisaggregationController::class, 'update']);

    // user routes
    Route::get('/users',[UserController::class, 'index']);
    Route::get('/users/create',[UserController::class, 'create']);
    Route::post('/users/store',[UserController::class, 'store']);
    Route::get('/users/{id}/edit',[UserController::class, 'edit']);

    // team routes
    Route::get('/teams',[TeamController::class, 'index']);
    Route::get('/teams/{id}/edit',[TeamController::class, 'edit']);
    Route::put('/teams/{id}/update',[TeamController::class, 'update']);
    Route::get('/teams/{id}/members',[TeamController::class, 'members']);
    Route::post('/teams/members/add',[TeamController::class, 'addmember']);
    Route::delete('/teams/members/{id}/delete',[TeamController::class, 'deleteMember']);
    Route::put('/teams/members/{id}/update',[TeamController::class, 'updateMember']);


});
