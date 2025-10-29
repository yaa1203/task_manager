<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];


    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function userUsers()
    {
        return $this->hasMany(User::class, 'category_id')->where('role', 'user');
    }

    public function workspaces()
    {
        return $this->hasManyThrough(Workspace::class, User::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, User::class);
    }

    // Relasi hanya ke admin
    public function adminUsers()
    {
        return $this->hasMany(User::class, 'category_id')->where('role', 'admin');
    }

}
