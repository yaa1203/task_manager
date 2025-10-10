<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $adminName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task, $adminName)
    {
        $this->task = $task;
        $this->adminName = $adminName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'message' => "You have been assigned a new task by {$this->adminName}",
            'url' => route('tasks.show', $this->task),
            'created_at' => now(),
        ];
    }
}