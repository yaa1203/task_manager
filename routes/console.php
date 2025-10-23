<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Contoh command inspire, biar tetap ada
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Scheduler: hapus notifikasi lama setiap jam
Schedule::command('notifications:delete-old --days=7') // hapus notifikasi lebih dari 7 hari
    ->hourly() // setiap jam sekali
    ->timezone('Asia/Jakarta')
    ->onSuccess(function () {
        Log::info('Notifikasi lama berhasil dihapus pada ' . now());
    })
    ->onFailure(function () {
        Log::error('Gagal menghapus notifikasi lama pada ' . now());
    });
