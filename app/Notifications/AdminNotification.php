<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $url;
    protected $taskId;

    public function __construct($title, $message, $url = null, $taskId = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->taskId = $taskId;
    }

    public function via($notifiable)
    {
        return ['database']; // simpan di database
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'task_id' => $this->taskId,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'task_id' => $this->taskId,
        ];
    }
}