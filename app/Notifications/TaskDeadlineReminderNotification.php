<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskDeadlineReminderNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('classes.show', $this->task->class);

        return (new MailMessage)
            ->subject('⏰ Peringatan Deadline Tugas: ' . $this->task->title)
            ->greeting('Halo ' . $notifiable->fullname . '!')
            ->line('Jangan lupa! Tugas **' . $this->task->title . '** akan jatuh tempo besok.')
            ->line('**Deadline:** ' . $this->task->deadline_at->format('d M Y H:i'))
            ->line('**Kelas:** ' . $this->task->class->name)
            ->action('Lihat Tugas', $url)
            ->line('Jangan sampai ketinggalan! Kerjakan sekarang juga.');
    }

    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'class_id' => $this->task->class_id,
            'class_name' => $this->task->class->name,
            'title' => '⏰ Deadline Tugas: ' . $this->task->title,
            'description' => 'Tugas "' . $this->task->title . '" akan jatuh tempo besok!',
            'deadline_at' => $this->task->deadline_at->toISOString(),
            'url' => route('classes.show', $this->task->class) . '#classwork', // langsung ke tab tugas
        ];
    }
}