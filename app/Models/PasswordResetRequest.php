<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    protected $fillable = ['user_id', 'admin_id', 'status']; // 👈 Tambahkan admin_id

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 👇 Tambahkan relasi admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}