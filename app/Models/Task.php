<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'project_id',
        'created_by',
        'title',
        'description',
        'file_path',      // ← TAMBAHKAN INI
        'link',           // ← TAMBAHKAN INI
        'status',
        'priority',
        'due_date',
        'completed_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->withTimestamps();
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(UserTaskSubmission::class);
    }

    // Scopes
    public function scopeTodo($query)
    {
        return $query->where('status', 'todo');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->whereHas('assignedUsers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'done',
            'completed_at' => now()
        ]);
    }

    public function isOverdue()
    {
        return $this->due_date && 
               Carbon::parse($this->due_date)->isPast() && 
               $this->status !== 'done';
    }

    public function isAssignedTo($userId)
    {
        return $this->assignedUsers()->where('user_id', $userId)->exists();
    }

    /**
     * Calculate progress percentage of the task
     */
    public function getProgressPercentage(): int
    {
        $totalUsers = $this->assignedUsers()->count();
        
        if ($totalUsers === 0) {
            return 0;
        }

        $completedSubmissions = $this->submissions()->count();
        $percentage = ($completedSubmissions / $totalUsers) * 100;
        
        return (int) round($percentage);
    }

    /**
     * Check if task is completed
     */
    public function isCompleted(): bool
    {
        return $this->getProgressPercentage() === 100;
    }

    /**
     * Check if task has attachments
     */
    public function hasAttachments(): bool
    {
        return !empty($this->file_path) || !empty($this->link);
    }
}