<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'rootRedirect'])->name('root');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');
Route::get('/dashboard/student', [AuthController::class, 'studentDashboard'])->name('dashboard.student');
Route::get('/dashboard/lecturer', [AuthController::class, 'lecturerDashboard'])->name('dashboard.lecturer');
