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
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\SuperAdminCategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Versi disesuaikan agar redirect & middleware role berfungsi sempurna.
| SuperAdmin, Admin, dan User hanya bisa mengakses halaman sesuai rolenya.
|--------------------------------------------------------------------------
*/

// =============================================================
// 🔸 Halaman Utama
// =============================================================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            default      => redirect()->route('dashboard'),
        };
    }
    return view('welcome');
})->name('home')->middleware('no.cache');

// // =============================================================
// 🔸 Dashboard Umum (User biasa)
// =============================================================
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  
    // Analytics (User)
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/data', [AnalyticsController::class, 'data'])->name('analytics.data');

    // Notifikasi User
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');

    // Workspace User
    Route::prefix('my-workspaces')->name('my-workspaces.')->group(function () {
        Route::get('/', [WorkspaceController::class, 'userIndex'])->name('index');
        
        // Personal workspace routes
        Route::get('/create-personal', [WorkspaceController::class, 'createPersonal'])->name('create-personal');
        Route::post('/store-personal', [WorkspaceController::class, 'storePersonal'])->name('store-personal');
        
        Route::get('/{workspace}', [WorkspaceController::class, 'userShow'])->name('show');
        Route::get('/{workspace}/task/{task}', [WorkspaceController::class, 'userShowTask'])->name('task.show');
        Route::post('/{workspace}/task/{task}/submit', [WorkspaceController::class, 'submitTask'])->name('task.submit');

        // Personal workspace task management
        Route::get('/{workspace}/tasks/create', [WorkspaceController::class, 'createPersonalTask'])->name('tasks.create');
        Route::post('/{workspace}/tasks', [WorkspaceController::class, 'storePersonalTask'])->name('tasks.store');
        Route::get('/{workspace}/tasks/{task}/edit', [WorkspaceController::class, 'editPersonalTask'])->name('tasks.edit');
        Route::put('/{workspace}/tasks/{task}', [WorkspaceController::class, 'updatePersonalTask'])->name('tasks.update');
        Route::delete('/{workspace}/tasks/{task}', [WorkspaceController::class, 'deletePersonalTask'])->name('tasks.delete');
        Route::post('/{workspace}/tasks/{task}/toggle-complete', [WorkspaceController::class, 'toggleCompletePersonalTask'])->name('tasks.toggle-complete');

        // File Akses
        Route::get('/{workspace}/tasks/{task}/view-file', [WorkspaceController::class, 'viewTaskFile'])->name('task.view-file');
        Route::get('{workspace}/tasks/{task}/download', [WorkspaceController::class, 'downloadTaskFile'])->name('task.download');
        Route::get('{workspace}/tasks/{task}/submissions/{submission}/view', [WorkspaceController::class, 'viewSubmissionFile'])->name('submission.view-file');
        Route::get('{workspace}/tasks/{task}/submissions/{submission}/download', [WorkspaceController::class, 'downloadSubmissionFile'])->name('submission.download');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePasswordUser'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Calendar (User)
    Route::get('/calendar', [WorkspaceController::class, 'userCalendar'])->name('calendar.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePasswordUser'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =============================================================
// 🔸 Admin Register (Guest only)
// =============================================================
Route::middleware('guest')->group(function () {
    Route::get('/register/admin', [AdminRegisterController::class, 'create'])->name('register.admin');
    Route::post('/register/admin', [AdminRegisterController::class, 'store']);
    Route::get('/register/superadmin', [SuperAdminRegisterController::class, 'create'])->name('register.superadmin');
    Route::post('/register/superadmin', [SuperAdminRegisterController::class, 'store']);
});

// =============================================================
// 🔸 Admin Area (hanya untuk role admin)
// =============================================================


Route::middleware(['auth', 'role:admin', 'no.cache'])->group(function () {

    Route::get('/admin/profile', [ProfileController::class, 'profileAdmin'])->name('admin.profile');
    Route::patch('/admin/profile', [ProfileController::class, 'updateAdmin'])->name('admin.profile.update');
    Route::put('/admin/profile/password', [ProfileController::class, 'updatePasswordAdmin'])->name('admin.password.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroyAdmin'])->name('admin.profile.destroy');
    Route::post('/admin/profile/send-verification', [ProfileController::class, 'sendVerificationAdmin'])->name('admin.profile.verify');

 
    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
    Route::post('/users/{user}/block', [UserController::class, 'block'])->name('users.block');
    Route::post('/users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');

    // Analytics Admin
    Route::prefix('analytict')->name('analytict.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'adminIndex'])->name('index');
        Route::get('/data', [AnalyticsController::class, 'adminData'])->name('data');
    });
    Route::post('/analytics/export', [AnalyticsController::class, 'exportReport'])->name('analytics.export');

    // Notifikasi Admin
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('readAll');
    });

    // Workspace Admin
    Route::middleware('admin.owns')->group(function () {
        Route::resource('workspaces', WorkspaceController::class);
        Route::post('/workspaces/{workspace}/toggle-archive', [WorkspaceController::class, 'toggleArchive'])->name('workspaces.toggle-archive');

        Route::prefix('workspaces/{workspace}/tasks')->name('workspace.tasks.')->group(function () {
            Route::get('/create', [WorkspaceController::class, 'createTask'])->name('create');
            Route::post('/', [WorkspaceController::class, 'storeTask'])->name('store');
            Route::get('/{task}/edit', [WorkspaceController::class, 'editTask'])->name('edit');
            Route::put('/{task}', [WorkspaceController::class, 'updateTask'])->name('update');
            Route::delete('/{task}', [WorkspaceController::class, 'destroyTask'])->name('destroy');
            Route::get('/{task}', [WorkspaceController::class, 'showTask'])->name('show');

            // File Akses
            Route::get('/{task}/view', [WorkspaceController::class, 'viewTaskFile'])->name('view-file');
            Route::get('/{task}/download', [WorkspaceController::class, 'downloadTaskFile'])->name('download');

            // Submission
            Route::get('/{task}/submissions/{submission}/view', [WorkspaceController::class, 'viewSubmissionFile'])->name('submissions.view');
            Route::get('/{task}/submissions/{submission}/download', [WorkspaceController::class, 'downloadSubmissionFile'])->name('submissions.download');
        });
    });
});

// =============================================================
// 🔸 SuperAdmin Area
// =============================================================
Route::middleware(['auth', 'role:superadmin'])->group(function () {

    Route::get('/analitik', [AnalyticsController::class, 'superAdminIndex'])->name('analitik.index');
    Route::get('/analitik/data', [AnalyticsController::class, 'superAdminData'])->name('analitik.data');

    Route::get('/superadmin/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('superadmin.dashboard');
    Route::resource('categories', SuperAdminCategoryController::class);

    // Manajemen Pengguna
    Route::prefix('pengguna')->group(function () {
        Route::get('/admin', [UserController::class, 'superAdminIndex'])->name('pengguna.admin');
        Route::get('/admin/{user}', [UserController::class, 'superAdminShow'])->name('pengguna.admin.show');
        Route::delete('/admin/{user}', [UserController::class, 'superAdminDestroy'])->name('pengguna.admin.destroy');
        // Routes untuk blokir/unblokir admin oleh superadmin
        Route::post('/pengguna/admin/{user}/block', [UserController::class, 'superAdminBlock'])
            ->name('pengguna.admin.block');
        Route::post('/pengguna/admin/{user}/unblock', [UserController::class, 'superAdminUnblock'])
            ->name('pengguna.admin.unblock');

        Route::get('/user', [UserController::class, 'superUserIndex'])->name('pengguna.user');
        Route::get('/user/{user}', [UserController::class, 'superUserShow'])->name('pengguna.user.show');
        Route::delete('/user/{user}', [UserController::class, 'superUserDestroy'])->name('pengguna.user.destroy');
        // Routes untuk Super Admin - Blokir/Unblokir User
        Route::post('/pengguna/user/{user}/block', [UserController::class, 'superUserBlock'])
            ->name('pengguna.user.block');
        Route::post('/pengguna/user/{user}/unblock', [UserController::class, 'superUserUnblock'])
            ->name('pengguna.user.unblock');
    });

    // Workspace SuperAdmin
    Route::prefix('space')->name('space.')->group(function () {
        Route::get('/', [WorkspaceController::class, 'superadminIndex'])->name('index');
        Route::get('/{workspace}', [WorkspaceController::class, 'superadminShow'])->name('show');
        Route::post('/{workspace}/toggle-archive', [WorkspaceController::class, 'superadminToggleArchive'])->name('toggle-archive');
        Route::delete('/{workspace}', [WorkspaceController::class, 'superadminDestroy'])->name('destroy');
    });

    // Profile Routes
    Route::get('/superadmin/profile', [ProfileController::class, 'profileSuperAdmin'])->name('superadmin.profile');
    Route::patch('/superadmin/profile', [ProfileController::class, 'updateSuperAdmin'])->name('superadmin.profile.update');
    Route::put('/superadmin/password', [ProfileController::class, 'updatePasswordSuperAdmin'])->name('superadmin.password.update');
    Route::delete('/superadmin/profile', [ProfileController::class, 'destroySuperAdmin'])->name('superadmin.profile.destroy');
    Route::post('/superadmin/email/verification-notification', [ProfileController::class, 'sendVerificationSuperAdmin'])->name('superadmin.verification.send');
});

// =============================================================
// 🔸 Auth Scaffolding
// =============================================================
require __DIR__ . '/auth.php';
