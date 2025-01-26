<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdated extends Notification
{
    use Queueable;

    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Task Status Updated')
            ->line('The status of your task has been updated.')
            ->line('Task: ' . $this->task->title)
            ->line('New Status: ' . ucfirst(str_replace('_', ' ', $this->task->status)))
            ->action('View Task', url('/tasks/' . $this->task->id));
    }
}
