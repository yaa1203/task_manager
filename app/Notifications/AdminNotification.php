<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    protected string $title;
    protected string $message;
    protected ?string $url;
    protected ?int $taskId;

    public function __construct(string $title, string $message, ?string $url = null, ?int $taskId = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->taskId = $taskId;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'task_id' => $this->taskId,
            'read_at' => null,
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'task_id' => $this->taskId,
        ];
    }

    // ------------------------------
    // Helper statis untuk mengirim
    // ------------------------------

    /**
     * Kirim notifikasi ke admin yang membuat tugas
     * 
     * @param \App\Models\Task $task
     * @param string $message
     * @param string|null $url
     */
    public static function notifyTaskCreator($task, string $message, ?string $url = null)
    {
        // Ambil admin pembuat tugas
        $creator = $task->creator; // pastikan relasi creator di Task sudah ada
        if ($creator && $creator->isAdmin()) {
            $creator->notify(new self(
                'Tugas Dikirim oleh ' . ($task->assignedUsers->pluck('name')->implode(', ')),
                $message,
                $url,
                $task->id
            ));
        }

        // Jika tugas punya beberapa admin kolaborator, bisa ditambahkan loop disini
        if ($task->collaborators && $task->collaborators->isNotEmpty()) {
            foreach ($task->collaborators as $admin) {
                if ($admin->isAdmin()) {
                    $admin->notify(new self(
                        'Tugas Dikirim oleh ' . ($task->assignedUsers->pluck('name')->implode(', ')),
                        $message,
                        $url,
                        $task->id
                    ));
                }
            }
        }
    }
}
