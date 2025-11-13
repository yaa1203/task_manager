<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTaskSubmission;

class TaskSubmittedNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $user;
    protected $submission;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, User $user, UserTaskSubmission $submission)
    {
        $this->task = $task;
        $this->user = $user;
        $this->submission = $submission;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Mengembalikan representasi array dari notifikasi untuk penyimpanan di database.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Pengumpulan Tugas Baru',
            'message' => "{$this->user->name} telah mengumpulkan tugas: {$this->task->title}",
            'url' => route('workspace.tasks.show', [
                'workspace' => $this->task->workspace_id,
                'task' => $this->task->id
            ]),
            'task_id' => $this->task->id,
            'user_id' => $this->user->id,
            'submission_id' => $this->submission->id,
            'type' => 'tugas_dikumpulkan',
        ];
    }

    /**
     * Mengembalikan representasi array dari notifikasi.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pengumpulan Tugas Baru',
            'message' => "{$this->user->name} telah mengumpulkan tugas: {$this->task->title}",
            'url' => route('workspaces.tasks.show', [
                'workspace' => $this->task->workspace_id,
                'task' => $this->task->id
            ]),
            'task_id' => $this->task->id,
            'user_id' => $this->user->id,
            'submission_id' => $this->submission->id,
            'type' => 'tugas_dikumpulkan',
        ];
    }
}