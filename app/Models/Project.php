<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'user_id',
    ];

    // Relasi: 1 project bisa punya banyak task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Relasi: project dimiliki oleh user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
