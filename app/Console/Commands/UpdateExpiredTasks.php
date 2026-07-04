<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateExpiredTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update task status to Expired if deadline has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai update status tugas...');

        $tasks = Task::where('deadline_at', '<=', Carbon::now())
                     ->where('status', '!=', 'Expired')
                     ->get();

        if ($tasks->isEmpty()) {
            $this->info('Tidak ada tugas yang perlu diupdate.');
            return 0;
        }

        $updatedCount = 0;

        foreach ($tasks as $task) {
            $task->status = 'Expired';
            $task->save();
            $updatedCount++;
            $this->line("Tugas #{$task->id} ({$task->title}) diupdate menjadi Expired.");
        }

        $this->info("Selesai. {$updatedCount} tugas berhasil diupdate.");

        return 0;
    }
}