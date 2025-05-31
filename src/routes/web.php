<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StampCorrectionController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StampCorrectionController as AdminStampCorrectionController;
use App\Http\Controllers\Admin\AdminLoginController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'index']);
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout']);
});

Route::middleware(['auth'])->group(function() {
    Route::get('/attendance', [AttendanceController::class, 'create']);
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn']);
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut']);
    Route::post('/break/start', [AttendanceController::class, 'startBreak']);
    Route::post('/break/end', [AttendanceController::class, 'endBreak']);
    Route::get('/attendance/list', [AttendanceController::class, 'index']);

    Route::get('/attendance/{attendance_id}', function($attendance_id) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return app(AdminAttendanceController::class)->show($attendance_id);
        } else {
            return app(AttendanceController::class)->show($attendance_id);
        }
    });
    Route::post('/attendance/{attendance_id}/request', [StampCorrectionController::class, 'store']);
    Route::post('/attendance/{attendance_id}/update', [AdminStampCorrectionController::class, 'update']);
    Route::get('/stamp_correction_request/list', function() {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return app(AdminStampCorrectionController::class)->index();
        } else {
            return app(StampCorrectionController::class)->index();
        }
    });
});

Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin/attendance/list', [AdminAttendanceController::class, 'index']);
    Route::get('/admin/staff/list', [StaffController::class, 'index']);
    Route::get('/admin/attendance/staff/{id}', [AdminAttendanceController::class, 'listStaffAttendance']);
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [AdminStampCorrectionController::class, 'show']);
});