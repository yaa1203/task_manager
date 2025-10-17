<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TaskOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Bisa tambahkan 'mail' jika mau kirim email juga
    }

    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'workspace_id' => $this->task->workspace_id,
            'task_title' => $this->task->title,
            'title' => '⚠️ Task Overdue!',
            'message' => "Task '{$this->task->title}' is now overdue. Due date was " . $this->task->due_date->format('d M Y'),
            'type' => 'task_overdue',
            'priority' => $this->task->priority,
            'due_date' => $this->task->due_date->format('Y-m-d'),
            'days_overdue' => now()->diffInDays($this->task->due_date, false),
        ];
    }

    public function toMail($notifiable)
    {
        $daysOverdue = abs(now()->diffInDays($this->task->due_date, false));
        
        return (new MailMessage)
            ->subject('⚠️ Task Overdue: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your task is now overdue by ' . $daysOverdue . ' day(s).')
            ->line('**Task:** ' . $this->task->title)
            ->line('**Due Date:** ' . $this->task->due_date->format('d M Y'))
            ->line('**Priority:** ' . strtoupper($this->task->priority))
            ->action('View Task', route('my-workspaces.task.show', [
                'workspace' => $this->task->workspace_id,
                'task' => $this->task->id
            ]))
            ->line('Please complete this task as soon as possible.');
    }
}