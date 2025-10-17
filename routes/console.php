<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// ====================================
// TASK NOTIFICATION SCHEDULING
// ====================================

Schedule::command('tasks:check-overdue')
    ->dailyAt('08:00')
    ->timezone('Asia/Jakarta')
    ->before(function () {
        // Log sebelum mulai
        Log::info('Starting overdue tasks check', [
            'time' => now(),
            'timezone' => 'Asia/Jakarta'
        ]);
    })
    ->onSuccess(function () {
        // Log jika berhasil
        Log::info('Overdue tasks check completed successfully', [
            'time' => now()
        ]);
    })
    ->onFailure(function () {
        // Log jika gagal
        Log::error('Overdue tasks check failed', [
            'time' => now()
        ]);
    })
    ->emailOutputOnFailure(config('mail.admin_email', 'admin@example.com'))
    ->runInBackground(); // Jalankan di background agar tidak blocking

// Optional: Send daily summary report
Schedule::call(function () {
    $overdueCount = \App\Models\Task::whereNotNull('due_date')
        ->where('due_date', '<', now())
        ->where('status', '!=', 'done')
        ->count();
    
    $dueTodayCount = \App\Models\Task::whereNotNull('due_date')
        ->whereDate('due_date', today())
        ->where('status', '!=', 'done')
        ->count();
    
    Log::info('Daily Task Summary', [
        'overdue_tasks' => $overdueCount,
        'due_today_tasks' => $dueTodayCount,
        'date' => today()->format('Y-m-d')
    ]);
})->dailyAt('18:00')->name('daily-task-summary');

// Optional: Cleanup old read notifications (setiap minggu)
Schedule::command('notifications:cleanup')
    ->weekly()
    ->mondays()
    ->at('01:00')
    ->timezone('Asia/Jakarta');