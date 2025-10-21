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
        'original_filename', // TAMBAHKAN INI
        'link',
        'notes',
        'status',
        'admin_notes',
        'submitted_at',
        'reviewed_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
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

    /**
     * Get display name - prioritas ke original_filename
     */
    public function getDisplayNameAttribute()
    {
        // Jika ada original_filename dan tidak kosong, gunakan itu
        if (!empty($this->original_filename)) {
            return $this->original_filename;
        }
        
        // Jika tidak ada, gunakan basename dari file_path
        if ($this->file_path) {
            return basename($this->file_path);
        }
        
        return 'File tidak tersedia';
    }

    /**
     * Get file extension
     */
    public function getFileExtensionAttribute()
    {
        if ($this->file_path) {
            return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        }
        return null;
    }

    /**
     * Check if file is image
     */
    public function isImage()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        return in_array($this->file_extension, $imageExtensions);
    }

    /**
     * Check if file is previewable
     */
    public function isPreviewable()
    {
        $previewableExtensions = ['pdf', 'txt', 'md', 'csv', 'html', 'htm', 'doc', 'docx'];
        return in_array($this->file_extension, $previewableExtensions);
    }

    /**
     * Check if submission has file
     */
    public function hasFile()
    {
        return !empty($this->file_path);
    }

    /**
     * Get file icon type
     */
    public function getFileIconAttribute()
    {
        $extension = $this->file_extension;
        
        if (in_array($extension, ['pdf'])) {
            return 'pdf';
        } elseif (in_array($extension, ['doc', 'docx'])) {
            return 'word';
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            return 'excel';
        } elseif (in_array($extension, ['ppt', 'pptx'])) {
            return 'powerpoint';
        } elseif (in_array($extension, ['zip', 'rar', '7z'])) {
            return 'archive';
        }
        
        return 'document';
    }

    /**
     * Get file icon color
     */
    public function getFileIconColorAttribute()
    {
        $icon = $this->file_icon;
        
        switch ($icon) {
            case 'pdf':
                return 'red';
            case 'word':
                return 'blue';
            case 'excel':
                return 'green';
            case 'powerpoint':
                return 'orange';
            case 'archive':
                return 'yellow';
            default:
                return 'gray';
        }
    }
}