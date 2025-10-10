<?php
// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function isOverdue()
    {
        if (!$this->due_date || $this->status === 'done') {
            return false;
        }
        return $this->due_date->isPast();
    }

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_task')
                    ->withTimestamps();
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user')->withTimestamps();
    }

    public function submissions()
    {
        return $this->hasMany(UserTaskSubmission::class);
    }

}