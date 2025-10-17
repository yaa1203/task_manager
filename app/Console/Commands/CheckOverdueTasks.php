<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskOverdueNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue tasks and send notifications to assigned users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for overdue tasks...');

        // Ambil tasks yang sudah lewat due_date dan belum selesai
        $overdueTasks = Task::whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today())
            ->where('status', '!=', 'done')
            ->with('assignedUsers')
            ->get();

        if ($overdueTasks->isEmpty()) {
            $this->info('No overdue tasks found.');
            return Command::SUCCESS;
        }

        $notificationCount = 0;

        foreach ($overdueTasks as $task) {
            $this->info("Processing task: {$task->title}");
            
            // Kirim notifikasi ke semua user yang ditugaskan
            foreach ($task->assignedUsers as $user) {
                try {
                    // Cek apakah user sudah dapat notifikasi overdue hari ini
                    $alreadyNotifiedToday = $user->notifications()
                        ->where('type', TaskOverdueNotification::class)
                        ->where('data->task_id', $task->id)
                        ->whereDate('created_at', Carbon::today())
                        ->exists();

                    if (!$alreadyNotifiedToday) {
                        $user->notify(new TaskOverdueNotification($task));
                        $notificationCount++;
                        $this->info("  ✓ Notified: {$user->name}");
                    } else {
                        $this->info("  - Already notified today: {$user->name}");
                    }
                } catch (\Exception $e) {
                    $this->error("  ✗ Failed to notify {$user->name}: {$e->getMessage()}");
                }
            }
        }

        $this->info("\nTotal notifications sent: {$notificationCount}");
        $this->info('Overdue task check completed!');

        return Command::SUCCESS;
    }
}