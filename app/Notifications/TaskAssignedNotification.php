<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Pastikan workspace ada
        $workspaceName = $this->task->workspace ? $this->task->workspace->name : 'Workspace Tidak Ditemukan';
        
        return [
            'title' => 'Tugas',
            'message' => 'Anda memiliki tugas baru: ' . $this->task->title,
            'task_id' => $this->task->id,
            'workspace_id' => $this->task->workspace_id,
            'task_name' => $this->task->title,
            'workspace_name' => $workspaceName,
            'type' => 'task_assigned',
        ];
    }
}