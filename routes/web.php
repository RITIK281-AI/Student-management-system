<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes (authenticated users only)
Route::middleware('auth')->group(function () {
    // Dashboard route - redirects based on role
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Staff Dashboard
    Route::get('/staff-dashboard', [StudentController::class, 'staffDashboard'])
        ->middleware('role:staff')
        ->name('staff.dashboard');

    // Admin Dashboard
    Route::get('/admin-dashboard', [StudentController::class, 'adminDashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // CEO Dashboard
    Route::get('/ceo-dashboard', [StudentController::class, 'ceoDashboard'])
        ->middleware('role:ceo')
        ->name('ceo.dashboard');

    // Student routes - accessible to all authenticated users for viewing
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');

    // Student CRUD routes - only for staff
    Route::middleware('staff')->group(function () {
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    });
});
