<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send an admin notification to this user.
     *
     * @param string $title
     * @param string $message
     * @param string|null $url
     * @param int|null $taskId
     * @return void
     */
    public function sendAdminNotification($title, $message, $url = null, $taskId = null)
    {
        $this->notify(new AdminNotification($title, $message, $url, $taskId));
    }

    /**
     * Get all tasks created by this user (for backward compatibility)
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all projects owned by this user
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get all workspaces owned by this user
     */
    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class);
    }

    /**
     * Get all tasks created by this user (as creator/admin)
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Get the tasks assigned to the user (many-to-many relationship)
     * This is the main relationship for user task assignments
     */
    public function assignedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')
            ->withTimestamps();
    }

    /**
     * Get all submissions made by this user
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(UserTaskSubmission::class);
    }

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get total tasks assigned to this user
     *
     * @return int
     */
    public function getTotalAssignedTasksAttribute(): int
    {
        return $this->assignedTasks()->count();
    }

    /**
     * Get completed tasks count
     *
     * @return int
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->submissions()
            ->distinct('task_id')
            ->count('task_id');
    }

    /**
     * Get pending tasks count
     *
     * @return int
     */
    public function getPendingTasksCountAttribute(): int
    {
        return $this->submissions()
            ->distinct('task_id')
            ->count('task_id');
    }

    /**
     * Get todo tasks count (not yet submitted)
     *
     * @return int
     */
    public function getTodoTasksCountAttribute(): int
    {
        $assignedTaskIds = $this->assignedTasks()->pluck('tasks.id');
        $submittedTaskIds = $this->submissions()->pluck('task_id');
        
        return $assignedTaskIds->diff($submittedTaskIds)->count();
    }
}