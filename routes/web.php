<?php

use App\Http\Controllers\{
    ProfileController,
    AdminNotificationController,
    AnalyticsController,
    AdminRegisterController,
    ProjectController,
    DashboardController,
    TaskController,
    UserController,
    NotificationController,
    WorkspaceController,
    SuperAdminRegisterController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Struktur sudah disesuaikan agar lebih rapi dan Laravel 11 friendly.
| Tidak ada route yang dihapus, hanya dikelompokkan ulang agar mudah dibaca.
|--------------------------------------------------------------------------
*/

// 🔹 Root Route (redirect berdasarkan role)
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            default      => redirect()->route('dashboard'), // user biasa
        };
    }

    return view('welcome');
})->name('home');


// =============================================================
// 🔸 Routes untuk User yang Sudah Login (auth umum)
// =============================================================
Route::middleware(['auth'])->group(function () {
    Route::view('/offline', 'offline')->name('offline');

    // Task dan Project umum (bukan admin)
    Route::resource('tasks', TaskController::class);
    Route::resource('projects', ProjectController::class);

    // Analytics (user biasa)
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/data', [AnalyticsController::class, 'data'])->name('analytics.data');

    // Notifikasi user biasa
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');

    // Workspace user
    Route::prefix('my-workspaces')->name('my-workspaces.')->group(function () {
        Route::get('/', [WorkspaceController::class, 'userIndex'])->name('index');
        Route::get('/{workspace}', [WorkspaceController::class, 'userShow'])->name('show');
        Route::get('/{workspace}/task/{task}', [WorkspaceController::class, 'userShowTask'])->name('task.show');
        Route::post('/{workspace}/task/{task}/submit', [WorkspaceController::class, 'submitTask'])->name('task.submit');

        // File (task/submission)
        Route::get('/{workspace}/tasks/{task}/view-file', [WorkspaceController::class, 'viewTaskFile'])->name('task.view-file');
        Route::get('{workspace}/tasks/{task}/download', [WorkspaceController::class, 'downloadTaskFile'])->name('task.download');
        Route::get('{workspace}/tasks/{task}/submissions/{submission}/view', [WorkspaceController::class, 'viewSubmissionFile'])->name('submission.view-file');
        Route::get('{workspace}/tasks/{task}/submissions/{submission}/download', [WorkspaceController::class, 'downloadSubmissionFile'])->name('submission.download');
    });

    // Calendar (user)
    Route::get('/calendar', [WorkspaceController::class, 'userCalendar'])->name('calendar.index');
});

// =============================================================
// 🔸 Dashboard Umum (User biasa)
// =============================================================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// =============================================================
// 🔸 Admin Register (Guest only)
// =============================================================
Route::middleware('guest')->group(function () {
    Route::get('/register/admin', [AdminRegisterController::class, 'create'])->name('register.admin');
    Route::post('/register/admin', [AdminRegisterController::class, 'store']);
});

// =============================================================
// 🔸 Admin Area (hanya untuk role admin)
// =============================================================
Route::middleware(['auth', 'role:admin'])->group(function () {

    // 🔹 Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'AdminIndex'])->name('admin.dashboard');

    // 🔹 User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // 🔹 Analytics Admin
    Route::prefix('analytict')->name('analytict.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'adminIndex'])->name('index');
        Route::get('/data', [AnalyticsController::class, 'adminData'])->name('data');
    });
    Route::post('/analytics/export', [AnalyticsController::class, 'exportReport'])->name('analytics.export');

    // 🔹 Notifikasi Admin
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('readAll');
    });

    // 🔹 Workspace Admin (dengan middleware admin.owns)
    Route::middleware('admin.owns')->group(function () {
        Route::resource('workspaces', WorkspaceController::class);
        Route::post('/workspaces/{workspace}/toggle-archive', [WorkspaceController::class, 'toggleArchive'])->name('workspaces.toggle-archive');

        // Tasks di dalam Workspace
        Route::prefix('workspaces/{workspace}/tasks')->name('workspace.tasks.')->group(function () {
            Route::get('/create', [WorkspaceController::class, 'createTask'])->name('create');
            Route::post('/', [WorkspaceController::class, 'storeTask'])->name('store');
            Route::get('/{task}/edit', [WorkspaceController::class, 'editTask'])->name('edit');
            Route::put('/{task}', [WorkspaceController::class, 'updateTask'])->name('update');
            Route::delete('/{task}', [WorkspaceController::class, 'destroyTask'])->name('destroy');
            Route::get('/{task}', [WorkspaceController::class, 'showTask'])->name('show');

            // File akses
            Route::get('/{task}/view', [WorkspaceController::class, 'viewTaskFile'])->name('view-file');
            Route::get('/{task}/download', [WorkspaceController::class, 'downloadTaskFile'])->name('download');

            // Submission
            Route::get('/{task}/submissions/{submission}/view', [WorkspaceController::class, 'viewSubmissionFile'])->name('submissions.view');
            Route::get('/{task}/submissions/{submission}/download', [WorkspaceController::class, 'downloadSubmissionFile'])->name('submissions.download');
        });
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/register/superadmin', [SuperAdminRegisterController::class, 'create'])->name('register.superadmin');
    Route::post('/register/superadmin', [SuperAdminRegisterController::class, 'store']);
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('superadmin.dashboard');
});

// =============================================================
// 🔸 Profile Routes
// =============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =============================================================
// 🔸 Auth Scaffolding
// =============================================================
require __DIR__ . '/auth.php';
