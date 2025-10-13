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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

// Root route - redirect berdasarkan status login dan role
Route::get('/', function () {
    if (auth()->check()) {
        // Jika user admin, arahkan ke admin dashboard
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        // Jika user biasa, ke dashboard biasa
        return redirect()->route('dashboard');
    }
    
    // Jika belum login, tampilkan halaman welcome
    return view('welcome');
})->name('home');

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
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');
    Route::get('/my-workspaces', [WorkspaceController::class, 'userIndex'])->name('my-workspaces.index');
    Route::get('/my-workspaces/{workspace}', [WorkspaceController::class, 'userShow'])->name('my-workspaces.show');
    Route::post('/my-workspaces/{workspace}/task/{task}/submit', [WorkspaceController::class, 'submitTask'])->name('my-workspaces.task.submit');
    Route::get('/my-workspaces/{workspace}/task/{task}', [WorkspaceController::class, 'userShowTask'])->name('my-workspaces.task.show');
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

    Route::get('/analytict', [AnalyticsController::class, 'adminIndex'])->name('analytict.index');
    Route::get('/analytict/data', [AnalyticsController::class, 'adminData'])->name('analytict.data');
    Route::post('/analytics/export', [AnalyticsController::class, 'exportReport'])->name('analytics.export');

    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Workspaces Routes
    Route::get('/workspaces', [WorkspaceController::class, 'index'])->name('workspaces.index');
    Route::get('/workspaces/create', [WorkspaceController::class, 'create'])->name('workspaces.create');
    Route::post('/workspaces', [WorkspaceController::class, 'store'])->name('workspaces.store');
    Route::get('/workspaces/{workspace}', [WorkspaceController::class, 'show'])->name('workspaces.show');
    Route::get('/workspaces/{workspace}/edit', [WorkspaceController::class, 'edit'])->name('workspaces.edit');
    Route::put('/workspaces/{workspace}', [WorkspaceController::class, 'update'])->name('workspaces.update');
    Route::delete('/workspaces/{workspace}', [WorkspaceController::class, 'destroy'])->name('workspaces.destroy');
    Route::post('/workspaces/{workspace}/toggle-archive', [WorkspaceController::class, 'toggleArchive'])->name('workspaces.toggle-archive');
    
    Route::get('/workspaces/{workspace}/tasks/create', [WorkspaceController::class, 'createTask'])
        ->name('workspace.tasks.create');
    Route::get('/workspaces/{workspace}/tasks/{task}/edit', [WorkspaceController::class, 'editTask'])
        ->name('workspace.tasks.edit');
    Route::put('/workspaces/{workspace}/tasks/{task}', [WorkspaceController::class, 'updateTask'])
        ->name('workspace.tasks.update');
    Route::post('/workspaces/{workspace}/tasks', [WorkspaceController::class, 'storeTask'])
        ->name('workspace.tasks.store');
    Route::delete('/workspaces/{workspace}/tasks/{task}', [WorkspaceController::class, 'destroyTask'])
        ->name('workspace.tasks.destroy');
    Route::get('/workspaces/{workspace}/tasks/{task}', [WorkspaceController::class, 'showTask'])
        ->name('workspace.tasks.show');
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