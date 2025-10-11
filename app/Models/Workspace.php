<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'color',
        'icon',
        'type',
        'is_archived'
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Ubah relasi tasks menjadi hasMany (bukan belongsToMany)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'workspace_project')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper methods
    public function getTasksCount()
    {
        return $this->tasks()->count();
    }

    public function getProjectsCount()
    {
        return $this->projects()->count();
    }

    public function getTotalItemsCount()
    {
        return $this->getTasksCount() + $this->getProjectsCount();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_workspace')->withTimestamps();
    }
}