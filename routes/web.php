<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes (Authenticated users only)
Route::middleware('auth')->group(function () {
    // Dashboard route - redirects based on role
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Staff Dashboard
    Route::get('/staff-dashboard', [DashboardController::class, 'staffDashboard'])
        ->middleware('role:staff')
        ->name('staff.dashboard');

    // Admin Dashboard
    Route::get('/admin-dashboard', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // CEO Dashboard
    Route::get('/ceo-dashboard', [DashboardController::class, 'ceoDashboard'])
        ->middleware('role:ceo')
        ->name('ceo.dashboard');

    // Analytics API
    Route::get('/api/analytics', [DashboardController::class, 'getAnalyticsData'])
        ->name('analytics.data');

    // Student routes - accessible to all authenticated users for viewing
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

    // Student CRUD routes - only for staff
    Route::middleware('staff')->group(function () {
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        
        // Soft delete routes
        Route::post('/students/{id}/restore', [StudentController::class, 'restore'])->name('students.restore');
        Route::delete('/students/{id}/force-delete', [StudentController::class, 'forceDelete'])->name('students.force-delete');
    });

    // Course routes - accessible to all authenticated users for viewing
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

    // Course CRUD routes - only for staff
    Route::middleware('staff')->group(function () {
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    });

    // Export routes - accessible to all authenticated users
    Route::get('/export/students/csv', [ExportController::class, 'exportStudentsCSV'])->name('export.students.csv');
    Route::get('/export/students/pdf', [ExportController::class, 'exportStudentsPDF'])->name('export.students.pdf');
    Route::get('/export/courses/csv', [ExportController::class, 'exportCoursesCSV'])->name('export.courses.csv');
});
