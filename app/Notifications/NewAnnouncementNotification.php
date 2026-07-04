<?php

namespace App\Notifications;

use App\Models\Announcement;
use App\Models\Classroom;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewAnnouncementNotification extends Notification
{
    use Queueable;

    protected $announcement;
    protected $class;

    public function __construct(Announcement $announcement, Classroom $class)
    {
        $this->announcement = $announcement;
        $this->class = $class;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('classes.show', $this->class);

        return (new MailMessage)
            ->subject('Pengumuman Baru: ' . $this->announcement->title)
            ->greeting('Halo ' . $notifiable->fullname . '!')
            ->line('Dosen ' . $this->class->owner->fullname . ' telah membuat pengumuman baru di kelas **' . $this->class->name . '**')
            ->line('**Judul:** ' . $this->announcement->title)
            ->line($this->announcement->description ?? 'Tidak ada deskripsi')
            ->action('Lihat Pengumuman', $url)
            ->line('Terima kasih telah menggunakan Simple Classroom.');
    }

    public function toArray($notifiable)
    {
        return [
            'announcement_id' => $this->announcement->id,
            'class_id' => $this->class->id,
            'class_name' => $this->class->name,
            'title' => '📢 Pengumuman: ' . $this->announcement->title,
            'description' => $this->announcement->description ?? 'Tidak ada deskripsi',
            'owner_name' => $this->class->owner->fullname,
            'created_at' => $this->announcement->created_at->toISOString(),
            'url' => route('classes.show', $this->class) . '#announcements', // langsung ke tab pengumuman
        ];
    }
}