<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/offline', 'offline')->name('offline');
    Route::resource('tasks', TaskController::class);
    Route::resource('projects', ProjectController::class);
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    Route::post('/calendar/quick-create', [CalendarController::class, 'quickCreateTask'])->name('calendar.quick-create');
    Route::put('/calendar/tasks/{task}/update-date', [CalendarController::class, 'updateTaskDate'])->name('calendar.tasks.update-date');
    Route::put('/calendar/projects/{project}/update-date', [CalendarController::class, 'updateProjectDate'])->name('calendar.projects.update-date');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/data', [AnalyticsController::class, 'data'])->name('analytics.data');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/register/admin', [AdminRegisterController::class, 'create'])
    ->middleware('guest')
    ->name('register.admin');

Route::post('/register/admin', [AdminRegisterController::class, 'store'])
    ->middleware('guest');

Route::middleware(['auth', 'role:admin'])->group(function () {
       // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    Route::get('/project', [ProjectController::class, 'adminIndex'])->name('project.index');
    Route::get('/project/{project}', [ProjectController::class, 'adminShow'])->name('project.show');
    Route::delete('/project/{project}', [ProjectController::class, 'adminDestroy'])->name('project.destroy');

    Route::get('/tugas', [TaskController::class, 'adminIndex'])->name('tugas.index');
    Route::get('/tugas/create', [TaskController::class, 'adminCreate'])->name('tugas.create');
    Route::post('/tugas', [TaskController::class, 'adminStore'])->name('tugas.store');
    Route::get('/tugas/{task}', [TaskController::class, 'adminShow'])->name('tugas.show');
    Route::delete('/tugas/{task}', [TaskController::class, 'adminDestroy'])->name('tugas.destroy');
    Route::get('/tugas/search', [TaskController::class, 'adminSearch'])->name('tugas.search');

    Route::get('/analytict', [AnalyticsController::class, 'adminIndex'])->name('analytict.index');
    Route::get('/analytict/data', [AnalyticsController::class, 'adminData'])->name('analytict.data');

    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'AdminIndex'])
        ->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
