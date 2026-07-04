<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// Auth routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/lecturer/dashboard', [DashboardController::class, 'lecturerDashboard'])->name('dashboard.lecturer');
    Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])->name('dashboard.student');

    // Class management (resource + custom actions)
    Route::resource('classes', ClassController::class)->except(['show']);
    Route::get('/classes/{class}', [ClassController::class, 'show'])->name('classes.show');
    Route::post('/classes/join', [ClassController::class, 'join'])->name('classes.join');
    Route::delete('/classes/{class}/leave', [ClassController::class, 'leave'])->name('classes.leave');

    // Task management
    Route::prefix('classes/{class}')->group(function () {
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    });

    // Task management
    Route::prefix('tasks')->group(function () {
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });

    // Announcement Management
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/classes/{class}/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/classes/{class}/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::resource('announcements', AnnouncementController::class)->except(['index', 'create', 'store']);

    // Student Tasks
    Route::get('/student/tasks', [TaskController::class, 'studentIndex'])->name('student.tasks.index');

    // Notifications
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
});