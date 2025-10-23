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
        'admin_id',
        'created_by',
        'title',
        'original_filename',
        'description',
        'file_path',
        'link',
        'status',
        'priority',
        'due_date',
        'due_time', // ✅ Tambahkan kolom waktu
        'completed_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'original_filename' => 'string',
        'file_path' => 'string',
    ];

    /**
     * ✅ Accessor gabungan (tanggal + waktu)
     * Contoh: $task->full_due_datetime
     */
    public function getFullDueDatetimeAttribute()
    {
        if (!$this->due_date) {
            return null;
        }

        $dateTimeString = $this->due_date->format('Y-m-d') . ' ' . ($this->due_time ?? '00:00:00');
        return Carbon::parse($dateTimeString);
    }

    // ================================
    // 🔗 Relationships
    // ================================
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user')->withTimestamps();
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
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

    // ================================
    // 🧩 Scopes
    // ================================
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

    // ================================
    // 🕒 Custom Methods
    // ================================
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'done',
            'completed_at' => now()
        ]);
    }

    public function isOverdue()
    {
        $due = $this->full_due_datetime;
        return $due && $due->isPast() && $this->status !== 'done';
    }

    public function isAssignedTo($userId)
    {
        return $this->assignedUsers()->where('user_id', $userId)->exists();
    }

    public function getProgressPercentage(): int
    {
        $totalUsers = $this->assignedUsers()->count();
        if ($totalUsers === 0) return 0;

        $completedSubmissions = $this->submissions()->count();
        return (int) round(($completedSubmissions / $totalUsers) * 100);
    }

    public function isCompleted(): bool
    {
        return $this->getProgressPercentage() === 100;
    }

    public function hasAttachments(): bool
    {
        return !empty($this->file_path) || !empty($this->link);
    }
}
