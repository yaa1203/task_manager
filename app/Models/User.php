<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Notifications\AdminNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'category_id',
        'is_blocked',
        'blocked_at',
        'blocked_by',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_blocked' => 'boolean',
        'blocked_at' => 'datetime',
    ];

    // ---------------------------------------------------
    // ---------------- RELATIONSHIPS -------------------
    // ---------------------------------------------------

    /**
     * Tasks created by this user (as creator/admin)
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Tasks assigned to this user (many-to-many)
     */
    public function assignedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')
            ->withTimestamps();
    }

    /**
     * Submissions made by this user
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(UserTaskSubmission::class);
    }

    /**
     * Workspaces owned by this user
     */
    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * Projects owned by this user
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    // ---------------------------------------------------
    // -------------------- HELPERS ---------------------
    // ---------------------------------------------------

    /**
     * Send admin notification to this user
     */
    public function sendAdminNotification($title, $message, $url = null, $taskId = null)
    {
        $this->notify(new AdminNotification($title, $message, $url, $taskId));
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'superadmin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // ---------------------------------------------------
    // ------------------ STATISTICS --------------------
    // ---------------------------------------------------

    /**
     * Total tasks assigned to this user
     */
    public function getTotalAssignedTasksAttribute(): int
    {
        return $this->assignedTasks()->count();
    }

    /**
     * Completed tasks count (submitted)
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->submissions()->distinct('task_id')->count('task_id');
    }

    /**
     * Pending tasks count (assigned but not submitted)
     */
    public function getPendingTasksCountAttribute(): int
    {
        $assignedTaskIds = $this->assignedTasks()->pluck('tasks.id');
        $submittedTaskIds = $this->submissions()->pluck('task_id');

        return $assignedTaskIds->diff($submittedTaskIds)->count();
    }

    /**
     * Todo tasks (overdue or upcoming)
     */
    public function getTodoTasksCountAttribute(): int
    {
        $now = now();

        $assignedTaskIds = $this->assignedTasks()->pluck('tasks.id');
        $submittedTaskIds = $this->submissions()->pluck('task_id');

        return $assignedTaskIds->diff($submittedTaskIds)->count();
    }

    /**
     * Completion rate in percentage
     */
    public function getCompletionRateAttribute(): float
    {
        $total = $this->total_assigned_tasks;
        return $total > 0 ? round(($this->completed_tasks_count / $total) * 100, 1) : 0;
    }

    /**
     * Diligence score: +10 for on-time, -5 for late
     */
    public function getDiligenceScoreAttribute(): int
    {
        $onTimeCount = $this->submissions()
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->whereColumn('user_task_submissions.created_at', '<=', 'tasks.due_date')
            ->count();

        $lateCount = $this->submissions()
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->whereColumn('user_task_submissions.created_at', '>', 'tasks.due_date')
            ->count();

        return max(0, ($onTimeCount * 10) - ($lateCount * 5));
    }

    // Di dalam Model User (App\Models\User.php)
    // Tambahkan method relasi ini jika belum ada

    /**
     * Tasks created by this admin (untuk admin yang membuat task)
     * Relasi untuk mengambil semua task yang dibuat oleh admin ini
     */
    public function adminCreatedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'admin_id');
    }

    /**
     * Workspaces created by this admin
     * Relasi untuk mengambil workspace yang dimiliki admin
     */
    public function adminWorkspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'admin_id');
    }

    /**
     * Get all team members (users) who have been assigned tasks by this admin
     * Method helper untuk mendapatkan anggota tim
     */
    public function getTeamMembersAttribute()
    {
        if ($this->role !== 'admin') {
            return collect([]);
        }

        return User::where('role', 'user')
            ->whereHas('assignedTasks', function($query) {
                $query->where('admin_id', $this->id);
            })
            ->get();
    }

    /**
     * Count total tasks created by this admin
     */
    public function getTotalTasksCreatedAttribute(): int
    {
        return $this->adminCreatedTasks()->count();
    }

    /**
     * Count total workspaces owned by this admin
     */
    public function getTotalWorkspacesAttribute(): int
    {
        return $this->adminWorkspaces()->count();
    }

    /**
     * Count total team members (unique users assigned to this admin's tasks)
     */
    public function getTotalTeamMembersAttribute(): int
    {
        if ($this->role !== 'admin') {
            return 0;
        }

        return User::where('role', 'user')
            ->whereHas('assignedTasks', function($query) {
                $query->where('admin_id', $this->id);
            })
            ->count();
    }

    /**
     * Get the admin who blocked this user
     */
    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    /**
     * Check if user is blocked
     */
    public function isBlocked()
    {
        return $this->is_blocked;
    }

    /**
     * Scope to filter only active (not blocked) users
     */
    public function scopeActive($query)
    {
        return $query->where('is_blocked', false);
    }

    /**
     * Scope to filter only blocked users
     */
    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }
}
