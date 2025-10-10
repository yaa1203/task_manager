<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'file_path',
        'link',
        'notes',
    ];

    /**
     * Get the task that owns the submission.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function taskSubmissions()
    {
        return $this->hasMany(UserTaskSubmission::class);
    }

    /**
     * Get the user that owns the submission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the file URL
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}