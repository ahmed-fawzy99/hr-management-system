<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['role:admin', 'auth']], function () {

    Route::get('employees/find', [\App\Http\Controllers\EmployeeController::class, 'find'])->name('employees.find');
    Route::get('employees/archived', [\App\Http\Controllers\EmployeeController::class, 'archivedIndex'])->name('employees.archived');
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    Route::resource('branches', \App\Http\Controllers\BranchController::class);
    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
    Route::resource('positions', \App\Http\Controllers\PositionController::class);
    Route::resource('shifts', \App\Http\Controllers\ShiftController::class);
    Route::resource('metrics', \App\Http\Controllers\MetricsController::class);
    Route::resource('requests', \App\Http\Controllers\RequestController::class);

    // Payroll
    Route::put('payrolls/{id}/updateStatus', [\App\Http\Controllers\PayrollController::class, 'updateStatus'])->name('payrolls.updateStatus');
    Route::resource('payrolls', \App\Http\Controllers\PayrollController::class);

    Route::get('attendance/{date}', [\App\Http\Controllers\AttendanceController::class, 'dayShow'])->name('attendance.show');
    Route::delete('attendance', [\App\Http\Controllers\AttendanceController::class, 'dayDelete'])->name('attendance.destroy');
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);

    // Globals
    Route::get('globals', [\App\Http\Controllers\GlobalsController::class, 'index'])->name('globals.index');
    Route::get('globals/edit', [\App\Http\Controllers\GlobalsController::class, 'edit'])->name('globals.edit');
    Route::put('globals/edit', [\App\Http\Controllers\GlobalsController::class, 'update'])->name('globals.update');

    // Logs
    Route::get('logs',[\App\Http\Controllers\LogsController::class, 'index'])->name('logs.index');

    // Calendar
    Route::get('calendar', [\App\Http\Controllers\CalendarController::class, 'calendarIndex'])->name('calendar.index');
    Route::resource('calendars', \App\Http\Controllers\CalendarController::class);


});

// Logged
Route::group(['middleware' => ['auth']], function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('my-profile', [\App\Http\Controllers\EmployeeController::class, 'showMyProfile'])->name('my-profile');
    Route::resource('requests', \App\Http\Controllers\RequestController::class)->only(['index', 'show', 'create', 'store']);
    Route::resource('payrolls', \App\Http\Controllers\PayrollController::class)->only(['index', 'show']);
    Route::get('calendar', [\App\Http\Controllers\CalendarController::class, 'calendarIndex'])->name('calendar.index');

    Route::get('my-attendance', [\App\Http\Controllers\AttendanceController::class, 'attendanceDashboard'])->name('attendance.dashboard');
    Route::post('attendance/signin', [\App\Http\Controllers\AttendanceController::class, 'dashboardSignInAttendance'])->name('attendance.dashboardSignIn');
    Route::post('attendance/signoff', [\App\Http\Controllers\AttendanceController::class, 'dashboardSignOffAttendance'])->name('attendance.dashboardSignOff');

});

// Redirect authenticated users to the dashboard
Route::redirect('/', '/dashboard')->middleware('auth');

// Language Switching
Route::get('language/{language}', function ($language) {
    Session()->put('locale', $language);
    return redirect()->back();
})->name('language');

require __DIR__.'/auth.php';
