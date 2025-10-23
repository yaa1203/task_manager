<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;

class DeleteOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:delete-old {--days=7 : Jumlah hari untuk menghapus notifikasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus notifikasi yang lebih lama dari jumlah hari yang ditentukan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        $this->info("Menghapus notifikasi yang lebih lama dari {$days} hari...");

        $deletedCount = DatabaseNotification::where('created_at', '<', Carbon::now()->subDays($days))->delete();

        $this->info("✓ Berhasil menghapus {$deletedCount} notifikasi lama.");

        return Command::SUCCESS;
    }
}
