<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;

class CleanupNotifications extends Command
{
    protected $signature = 'notifications:cleanup {--days=30 : Number of days to keep notifications}';
    protected $description = 'Cleanup old read notifications';

    public function handle()
    {
        $days = $this->option('days');
        $date = Carbon::now()->subDays($days);
        
        $this->info("Cleaning up read notifications older than {$days} days...");
        
        // Hapus notifikasi yang sudah dibaca dan lebih dari X hari
        $deleted = DatabaseNotification::whereNotNull('read_at')
            ->where('created_at', '<', $date)
            ->delete();
        
        $this->info("✅ Deleted {$deleted} old notifications");
        
        // Optional: Hapus juga unread notifications yang sangat lama (90 hari)
        if ($this->confirm('Also delete unread notifications older than 90 days?')) {
            $veryOldDate = Carbon::now()->subDays(90);
            $deletedUnread = DatabaseNotification::whereNull('read_at')
                ->where('created_at', '<', $veryOldDate)
                ->delete();
            
            $this->info("✅ Deleted {$deletedUnread} very old unread notifications");
        }
        
        return Command::SUCCESS;
    }
}