<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskDeadlineReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendDeadlineReminders extends Command
{
    protected $signature = 'tasks:send-deadline-reminders';
    protected $description = 'Send deadline reminder notifications for tasks due tomorrow (H-1)';

    public function handle()
    {
        $this->info(' Memulai pengecekan deadline tugas...');

        $tomorrow = Carbon::tomorrow();
        $this->info(" Hari ini: " . Carbon::now()->toDateString());
        $this->info(" Deadline besok (H-1): " . $tomorrow->toDateString());

        $allTasks = Task::all();
        $this->info(" Total tugas di database: " . $allTasks->count());

        foreach ($allTasks as $t) {
            $this->line("   - {$t->title} | deadline: {$t->deadline_at} | status: {$t->status}");
        }

        $tasks = Task::whereDate('deadline_at', $tomorrow->toDateString())
                    ->where('status', '!=', 'Expired')
                    ->get();

        $this->info("Tugas deadline besok: " . $tasks->count());

        if ($tasks->isEmpty()) {
            $this->info('Tidak ada tugas yang deadline-nya besok.');
            return 0;
        }

        $this->info("Ditemukan {$tasks->count()} tugas dengan deadline besok.");

        $notifiedCount = 0;

        foreach ($tasks as $task) {
            $students = $task->class->participants()->where('role', 'student')->get();

            if ($students->isEmpty()) {
                $this->warn("Tidak ada mahasiswa terdaftar untuk tugas: {$task->title}");
                continue;
            }

            Notification::send($students, new TaskDeadlineReminderNotification($task));
            $notifiedCount += $students->count();

            $this->line("Notifikasi dikirim untuk tugas '{$task->title}' ke {$students->count()} mahasiswa.");
        }

        $this->info("Selesai! Total {$notifiedCount} notifikasi terkirim.");

        return 0;
    }
}